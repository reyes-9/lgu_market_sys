<?php
header("Content-Type: application/json");

loadEnv(__DIR__ . '/../../.env');

$api_key = getenv("HUGGINGFACE_API_KEY") ?: ($_ENV["HUGGINGFACE_API_KEY"] ?? null);
if (!$api_key) {
    echo json_encode(["error" => "API key not configured"]);
    exit;
}

// Get POST data safely
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['feedback']) || empty(trim($data['feedback']))) {
    echo json_encode(["error" => "No feedback provided"]);
    exit;
}

$feedback = htmlspecialchars(trim($data['feedback']), ENT_QUOTES, 'UTF-8');
$url = "https://api-inference.huggingface.co/models/cardiffnlp/twitter-xlm-roberta-base-sentiment";
$headers = [
    "Authorization: Bearer " . $api_key,
    "Content-Type: application/json"
];
$payload = json_encode(["inputs" => [$feedback]]);

// ✅ Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Handle cURL failures
if ($response === false) {
    echo json_encode(["error" => "API request failed"]);
    exit;
}

// Decode API response
$response_data = json_decode($response, true);

// Handle API errors
if ($http_code !== 200 || isset($response_data['error'])) {
    echo json_encode(["error" => "API Error", "status_code" => $http_code, "response" => $response_data]);
    exit;
}

// Ensure valid response format
if (!isset($response_data[0]) || !is_array($response_data[0])) {
    echo json_encode(["error" => "Unexpected API response", "raw_response" => $response_data]);
    exit;
}

// Extract sentiment with the highest confidence score
$scores = $response_data[0];
if (empty($scores)) {
    echo json_encode(["error" => "No sentiment data returned"]);
    exit;
}

$max_score = max(array_column($scores, 'score'));
$best_label = null;

foreach ($scores as $item) {
    if ($item['score'] == $max_score) {
        $best_label = $item['label'];
        break;
    }
}

echo json_encode([
    "sentiment" => $best_label,
    "confidence_score" => $max_score
]);

// Load environment variables from .env file
function loadEnv($path)
{
    if (!file_exists($path)) {
        error_log("⚠️ .env file not found at: $path");
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        // Set environment variables for different PHP environments
        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}
