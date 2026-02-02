<?php
header("Content-Type: application/json");

// =====================
// KONFIGURASI
// =====================
// DAPATKAN API KEY DI: https://aistudio.google.com/
$API_KEY = getenv('GEMINI_API_KEY'); 
$model = "gemini-3-flash-preview";

// =====================
// AMBIL INPUT
// =====================
$input = json_decode(file_get_contents("php://input"), true);
$message = trim($input['message'] ?? '');

if ($message === '') {
    echo json_encode(["response" => "Pesan kosong"]);
    exit;
}

// =====================
// DATA KE AI (FORMAT GEMINI)
// =====================
$url = "https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent?key=$API_KEY";

$data = [
    "system_instruction" => [
        "parts" => [
            ["text" => "Jawab hanya satu kalimat pendek. Jangan pakai bold, tanda bintang, markdown, daftar, atau gaya artikel. Jawab santai seperti chat."]
        ]
    ],
    "contents" => [
        [
            "role" => "user",
            "parts" => [
                ["text" => $message]
            ]
        ]
    ],
    "generationConfig" => [
        "maxOutputTokens" => 40
    ]
];

// =====================
// CURL REQUEST
// =====================
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json"
    ],
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_TIMEOUT => 60
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(["response" => "Error koneksi: " . curl_error($ch)]);
    curl_close($ch);
    exit;
}
curl_close($ch);

$result = json_decode($response, true);

// =====================
// AMBIL JAWABAN
// =====================
// Parsing response Gemini
if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
    $reply = $result['candidates'][0]['content']['parts'][0]['text'];
} else {
    // Cek jika ada error message dari API
    $errorMsg = $result['error']['message'] ?? 'AI tidak membalas';
    $reply = "Error: " . $errorMsg;
}

// =====================
// FILTER PAKSA (ANTI ARTIKEL)
// =====================

// hapus markdown (** ## _ `)
$reply = preg_replace('/[*#_`]/', '', $reply);

// ambil 1 kalimat saja (optional, Gemini biasanya patuh system instruction tapi jaga-jaga)
$reply = preg_split('/[.!?]/', $reply)[0];

// rapikan spasi
$reply = trim($reply);

// =====================
// OUTPUT
// =====================
echo json_encode([
    "response" => $reply
]);
