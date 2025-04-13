<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'vendor/autoload.php';

// Log the start of the script
error_log("Starting checkout session creation");

// Load environment variables
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    error_log("Environment variables loaded successfully");
} catch (Exception $e) {
    error_log("Error loading environment variables: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Configuration error']);
    exit;
}

// Set Stripe API key from environment variable
try {
    \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
    error_log("Stripe API key set successfully");
} catch (Exception $e) {
    error_log("Error setting Stripe API key: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Stripe configuration error']);
    exit;
}

// Set content type to JSON
header('Content-Type: application/json');

try {
    // Get JSON input
    $input = file_get_contents('php://input');
    error_log("Received input: " . $input);
    
    $formData = json_decode($input, true);

    // Validate input
    if (!$formData) {
        throw new Exception('Invalid form data received');
    }

    error_log("Form data decoded successfully: " . print_r($formData, true));

    // Get the current domain
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $domain = $protocol . $_SERVER['HTTP_HOST'];

    // Create a Stripe checkout session
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => 'Custom iPhone Case',
                    'description' => 'Personalized iPhone case with your photo',
                ],
                'unit_amount' => 1799, // €17.99
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => $domain . '/success.html',
        'cancel_url' => $domain . '/cancel.html',
        'metadata' => [
            'phone_model' => $formData['phone_model'] ?? '',
            'delivery_method' => $formData['delivery_method'] ?? '',
            'customer_name' => $formData['name'] ?? '',
            'customer_address' => $formData['address'] ?? '',
            'customer_phone' => $formData['phone'] ?? '',
        ],
    ]);

    error_log("Stripe session created successfully with ID: " . $checkout_session->id);

    // Return the session ID
    echo json_encode(['id' => $checkout_session->id]);
    exit;
} catch (Exception $e) {
    // Log the error
    error_log("Stripe error: " . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
} 