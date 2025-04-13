<?php
require_once 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Set Stripe API key from environment variable
\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

header('Content-Type: application/json');

// Get form data
$formData = json_decode(file_get_contents('php://input'), true);

try {
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => 'Custom iPhone Case',
                    'description' => 'Personalized iPhone case with your photo',
                    'images' => [$_ENV['SITE_URL'] . '/images/hero-case.png'],
                ],
                'unit_amount' => 1799, // €17.99
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => $_ENV['SITE_URL'] . '/success.html',
        'cancel_url' => $_ENV['SITE_URL'] . '/cancel.html',
        'customer_email' => $formData['email'] ?? null,
        'metadata' => [
            'phone_model' => $formData['phone_model'] ?? '',
            'delivery_method' => $formData['delivery_method'] ?? '',
            'customer_name' => $formData['name'] ?? '',
            'customer_address' => $formData['address'] ?? '',
            'customer_phone' => $formData['phone'] ?? '',
        ],
    ]);

    echo json_encode(['id' => $checkout_session->id]);
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} 