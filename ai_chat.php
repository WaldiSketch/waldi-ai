<?php
header("Content-Type: application/json");

// =====================
// KONFIGURASI
// =====================
$API_KEY = "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"; // GANTI
$model = "mistralai/mistral-7b-instruct";

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
// DATA KE AI
// =====================
$data = [
    "model" => $model,
    "max_tokens" => 40,
    "messages" => [
        [
            "role" => "system",
            "content" => "Jawab hanya satu kalimat pendek. Jangan pakai bold, tanda bintang, markdown, daftar, atau gaya artikel. Jawab santai seperti chat."
        ],
        [
            "role" => "user",
            "content" => $message
        ]
    ]
];

// =====================
// CURL REQUEST
// =====================
$ch = curl_init("https://openrouter.ai/api/v1/chat/completions");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $API_KEY",
        "HTTP-Referer: http://localhost",
        "X-Title: Chat AI Simple"
    ],
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_TIMEOUT => 60
]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

// =====================
// AMBIL JAWABAN
// =====================
$reply = $result['choices'][0]['message']['content'] ?? 'AI tidak membalas';

// =====================
// FILTER PAKSA (ANTI ARTIKEL)
// =====================

// hapus markdown (** ## _ `)
$reply = preg_replace('/[*#_`]/', '', $reply);

// ambil 1 kalimat saja
$reply = preg_split('/[.!?]/', $reply)[0];

// rapikan spasi
$reply = trim($reply);

// =====================
// OUTPUT
// =====================
echo json_encode([
    "response" => $reply
]);
