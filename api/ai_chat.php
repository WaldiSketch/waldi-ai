<?php
error_reporting(0);
header("Content-Type: application/json");

// =====================
// KONFIGURASI
// =====================
// DAPATKAN API KEY DI: https://aistudio.google.com/
$API_KEY = getenv('GEMINI_API_KEY') ?: 'AIzaSyDu_4P5d4ubBCshGHasGzPK8m9GHTUyo_Q'; 
$model = "gemma-3-27b-it";

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
        "maxOutputTokens" => 100
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
    CURLOPT_TIMEOUT => 60,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => 0
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

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
if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
    $reply = $result['candidates'][0]['content']['parts'][0]['text'];
} else {
    $apiError = $result['error']['message'] ?? 'Tidak ada pesan error dari Google';
    $reply = "Error ($httpCode): " . $apiError . " | Full Response: " . $response;
}

// =====================
// FILTER PAKSA
// =====================
$reply = preg_replace('/[*#_`]/', '', $reply);
$reply = trim($reply);

// =====================
// OUTPUT
// =====================
echo json_encode([
    "response" => $reply
]);
