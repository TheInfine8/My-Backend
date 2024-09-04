<?php
header('Content-Type: application/json');

// Add CORS headers to allow requests from your frontend
header("Access-Control-Allow-Origin: https://my-frontend-fg14.onrender.com"); // Replace with your actual frontend URL
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Handle preflight requests (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // Preflight request is successful
    exit;
}

// Get the incoming request payload
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate that the credential token was received
if (isset($data['credential'])) {
    $credential = $data['credential'];

    // Validate the token with Google
    $url = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . $credential;
    $response = file_get_contents($url);
    $googleUser = json_decode($response, true);

    // Check if the token is valid and contains the required fields
    if (isset($googleUser['email'])) {
        $email = $googleUser['email'];
        $name = $googleUser['name'];
        $googleId = $googleUser['sub']; // This is the unique Google ID for the user

        // Example response from the backend to the frontend
        echo json_encode([
            'success' => true,
            'user' => [
                'email' => $email,
                'name' => $name,
                'googleId' => $googleId
            ]
        ]);
    } else {
        // Handle invalid token case
        echo json_encode(['success' => false, 'message' => 'Invalid Google token']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No credential received']);
}
?>
