<?php
header('Content-Type: application/json');

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

    // Check if the token is valid
    if (isset($googleUser['email'])) {
        $email = $googleUser['email'];
        $name = $googleUser['name'];
        $googleId = $googleUser['sub']; // This is the unique Google ID for the user

        // Since we're not using a database, we'll simulate a successful login
        echo json_encode([
            'success' => true,
            'message' => 'User logged in successfully.',
            'user' => [
                'name' => $name,
                'email' => $email,
                'googleId' => $googleId
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid Google token']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No credential received']);
}
?>

