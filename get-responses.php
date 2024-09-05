<?php
// Allow CORS for your frontend URL
header("Access-Control-Allow-Origin: https://my-frontend-fg14.onrender.com"); // Replace with your frontend URL
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Check if 'user' parameter is provided
if (!isset($_GET['user']) || empty($_GET['user'])) {
    echo json_encode(["error" => "No user specified"]);
    exit;
}

$user = $_GET['user'];

// Log that we're fetching messages for the user
error_log("Fetching responses for user: " . $user);

// Fetch messages from your storage mechanism (database, session, etc.)
$messages = fetchMessagesForUser($user);

// Log the fetched messages for debugging
error_log("Fetched responses for user {$user}: " . print_r($messages, true));

// Respond with the messages
echo json_encode(["messages" => $messages]);

/**
 * Function to fetch messages for a given user.
 * You should implement this function to pull messages from your actual storage,
 * such as a database, session, or file.
 */
function fetchMessagesForUser($user) {
    // TODO: Implement your actual message retrieval logic here (e.g., database query).
    
    // For now, return some placeholder messages
    return [
        "Hello {$user}, this is a response from Teams!",
        "This is another message for {$user}"
    ];
}
?>

