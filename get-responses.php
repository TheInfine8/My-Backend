<?php
header("Access-Control-Allow-Origin: https://my-frontend-fg14.onrender.com/"); // Replace with your frontend URL
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

$user = $_GET['user'];
if (!$user) {
    echo json_encode(["error" => "No user specified"]);
    exit;
}

// Fetch messages from storage (database, session, etc.)
$messages = fetchMessagesForUser($user); // Implement your storage fetching logic here

// For example purposes, return a static message:
// $messages = ["Teams message 1", "Teams message 2"]; // Replace with real fetch logic

echo json_encode(["messages" => $messages]);

function fetchMessagesForUser($user) {
    // TODO: Implement your message retrieval logic from the database, session, or file.
    // This is where you'll return the messages stored for the given $user
    // For now, return some placeholder messages
    return ["Hello {$user}, this is a response from Teams!", "This is another message for {$user}"];
}
?>
