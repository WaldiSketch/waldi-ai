<?php
error_reporting(0);
header("Content-Type: application/json");

// =====================
// KONFIGURASI
// =====================
// DAPATKAN API KEY DI: https://aistudio.google.com/
$API_KEY = getenv('GEMINI_API_KEY') ?: 'AIzaSyDu_4P5d4ubBCshGHasGzPK8m9GHTUyo_Q'; 
$model = "gemini-1.5-flash";

// ... (rest of input handling) ...

// =====================
// DATA KE AI (FORMAT GEMINI)
// =====================
$url = "https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent?key=$API_KEY";

// ... (data array) ...

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

// DEBUG: Log raw response
$maskedUrl = str_replace($API_KEY, 'HIDDEN_KEY', $url);
// file_put_contents('debug_log.txt', "URL: $maskedUrl\nResponse: " . $response . "\n\n", FILE_APPEND);

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
    $apiError = $result['error']['message'] ?? 'Tidak ada pesan error dari Google';
    $reply = "Error ($httpCode): " . $apiError . " | Raw: " . substr($response, 0, 100);
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
