<?php
// Add CORS headers to allow requests from the frontend
header("Access-Control-Allow-Origin: https://my-frontend-fg14.onrender.com"); // Allow only your frontend origin
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow specific HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow specific headers
header("Access-Control-Allow-Credentials: true"); // Allow credentials (optional)

// Handle preflight requests (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Preflight request successful, respond with 204 No Content
    http_response_code(204);
    exit;
}

// Verify the request from Teams using the security token (for outgoing webhook handling)
$teamsToken = 'EXx2p+95cU8v/WOupK917NcE7Nyt7IIUlvihL+8SKkU='; // Replace with your actual Teams outgoing webhook token
$incomingToken = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

// Capture incoming POST data
$input = file_get_contents("php://input");
$data = json_decode($input, true); // Decode to associative array

// Check if it's a request from Teams (outgoing webhook)
if ($incomingToken === $teamsToken && isset($data['text'])) {
    // This is an incoming message from Teams (outgoing webhook)
    
    // Log the incoming request from Teams
    error_log("Received message from Teams: " . $data['text']);
    
    // Extract user identifier from Teams message
    $userIdentifier = extractUserIdentifier($data['text']);
    
    // Store or handle the Teams response for the specific user
    storeTeamsResponseForUser($userIdentifier, $data['text']);
    
    echo json_encode(["status" => "success", "message" => "Message processed from Teams"]);
    exit;
}

// Continue with existing functionality if not from Teams
if (isset($data['user']) && isset($data['message'])) { // Ensure 'message' key is used instead of 'text'
    $user = $data['user'];
    $message = $data['message'];

    // Log the incoming request from chat
    error_log("Received message from user: " . $data['user']);
    error_log("Message content: " . $data['message']);
    
    // Debugging output
    error_log("Raw input: " . $input);
    error_log("Decoded data: " . print_r($data, true));

    // Webhook URL
    $webhookUrl = "https://filoffeesoftwarepvtltd.webhook.office.com/webhookb2/dce0c08f-a7b6-429f-9473-4ebfbb453002@0644003f-0b3f-4517-814d-768fa69ab4ae/IncomingWebhook/023b8776e0884ae9821430ccad34e0a8/108d16ad-07a3-4dcf-88a2-88f4fcf28183";

    // Create the message payload
    $teamsMessage = [
        "text" => "{$user}: {$message}"
    ];

    // HTTP options for the request
    $options = [
        "http" => [
            "header" => "Content-type: application/json\r\n",
            "method" => "POST",
            "content" => json_encode($teamsMessage),
        ],
    ];

    // Create the context for the HTTP request
    $context = stream_context_create($options);

    // Send the request to the webhook URL
    $result = file_get_contents($webhookUrl, false, $context);

    // Check for errors in the request
    if ($result === FALSE) {
        $error = error_get_last();
        error_log("HTTP request failed. Error: " . print_r($error, true));
        echo json_encode(["status" => "error", "message" => "Failed to send message"]);
    } else {
        echo json_encode(["status" => "success", "message" => "Message sent successfully"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
}

// Function to extract user identifier from the Teams message
function extractUserIdentifier($message) {
    // Implement logic to extract the user identifier from the message text
    // For example, if your message format is "UserID: message", you can extract it like this:
    $parts = explode(":", $message, 2);
    return trim($parts[0]); // Return the extracted user identifier
}

// Function to store the response for the user in a way that the chat window can retrieve it
function storeTeamsResponseForUser($userIdentifier, $message) {
    // Store the response in a database, file, or session associated with the userIdentifier
    // For example, you can store it in a session or a temporary file
    // Make sure the chat window can retrieve this data using AJAX or WebSockets
    error_log("Storing response for user {$userIdentifier}: {$message}");
}
?>

