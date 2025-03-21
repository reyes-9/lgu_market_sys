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
$url = "https://router.huggingface.co/hf-inference/models/cardiffnlp/twitter-xlm-roberta-base-sentiment";

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
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
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
if ($http_code !== 200 || !is_array($response_data) || isset($response_data['error'])) {
    echo json_encode([
        "success" => false,
        "error" => "API Error",
        "status_code" => $http_code,
        "response" => $response_data ?? "Invalid JSON response"
    ]);
    exit;
}

// Ensure valid response format
if (!isset($response_data[0]) || !is_array($response_data[0]) || empty($response_data[0])) {
    echo json_encode(["success" => false, "error" => "Unexpected API response", "raw_response" => $response_data]);
    exit;
}

$scores = $response_data[0];

$best_sentiment = getHighestScoringSentiment($scores);

// Check if valid sentiment was found
if (!$best_sentiment['label']) {
    echo json_encode(["success" => false, "error" => "No valid sentiment data"]);
    exit;
}

// Output best sentiment
echo json_encode([
    "success" => true,
    "sentiment" => $best_sentiment['label'],
    "confidence_score" => $best_sentiment['score']
]);

// Load environment variables from .env file
function loadEnv($path)
{
    if (!file_exists($path)) {
        error_log("Warning: .env file not found at: $path");
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) continue;
        list($key, $value) = array_map('trim', explode('=', $line, 2));

        // Remove quotes if value is enclosed in single or double quotes
        $value = trim($value, "\"'");

        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

function getHighestScoringSentiment(array $sentiments): array
{
    $highest = ["label" => null, "score" => 0];

    foreach ($sentiments as $sentiment) {
        if ($sentiment['score'] > $highest['score']) {
            $highest = $sentiment;
        }
    }

    return $highest;
}

$data =
    array(
        array(
            array(
                "label" => "neutral",
                "score" => 0.8043944239616394
            ),
            array(
                "label" => "negative",
                "score" => 0.10720713436603546
            ),
            array(
                "label" => "positive",
                "score" => 0.08839844912290573
            )
        )
    );
