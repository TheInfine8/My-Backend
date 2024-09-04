<?php
// Add CORS headers to allow requests from your frontend
header("Access-Control-Allow-Origin: https://my-frontend-fg14.onrender.com"); // Replace with your actual frontend URL
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true"); // If credentials like cookies are required

// Handle preflight requests (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(204); // Preflight request is successful
  exit;
}

// Existing backend logic for Google OAuth validation goes here

// Example: Get the incoming request payload and validate the token
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Your token validation logic
if (isset($data['credential'])) {
  // Validate the token with Google
  $credential = $data['credential'];
  $url = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . $credential;
  $response = file_get_contents($url);
  $googleUser = json_decode($response, true);

  if (isset($googleUser['email'])) {
    // Proceed with login logic
    echo json_encode(['success' => true, 'message' => 'User logged in successfully']);
  } else {
    echo json_encode(['success' => false, 'message' => 'Invalid Google token']);
  }
} else {
  echo json_encode(['success' => false, 'message' => 'No credential received']);
}
