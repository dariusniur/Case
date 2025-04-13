<?php
require_once 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Set Stripe API key from environment variable
\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

header('Content-Type: application/json');

// Get form data
$input = file_get_contents('php://input');
$formData = json_decode($input, true);

// Log the received data for debugging
error_log("Received form data: " . print_r($formData, true));

try {
    if (!$formData) {
        throw new Exception('Invalid form data received');
    }

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
        'success_url' => 'https://your-domain.com/success.html',
        'cancel_url' => 'https://your-domain.com/cancel.html',
        'metadata' => [
            'phone_model' => $formData['phone_model'] ?? '',
            'delivery_method' => $formData['delivery_method'] ?? '',
            'customer_name' => $formData['name'] ?? '',
            'customer_address' => $formData['address'] ?? '',
            'customer_phone' => $formData['phone'] ?? '',
        ],
    ]);

    echo json_encode(['id' => $checkout_session->id]);
} catch (Exception $e) {
    error_log("Stripe error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} 