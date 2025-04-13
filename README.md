# Deklink - Custom iPhone Cases

A responsive product landing page where users can create custom iPhone cases using their own photos.

## Features

- Modern, responsive design using Tailwind CSS
- Custom iPhone case ordering system
- Stripe payment integration
- Image upload functionality
- Multiple delivery options
- Support for various iPhone models

## Setup Instructions

1. Clone the repository
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Set up your Stripe API keys:
   - Create a `.env` file in the root directory
   - Add your Stripe secret key:
     ```
     STRIPE_SECRET_KEY=your_stripe_secret_key
     ```
4. Update the success and cancel URLs in `create-checkout-session.php` with your domain
5. Place your images in the `images` directory:
   - logo.png
   - hero-case.png
   - 1.png, 2.png, 3.png, 4.png (example cases)

## Directory Structure

```
.
├── index.html              # Main landing page
├── create-checkout-session.php  # Stripe payment processing
├── success.html           # Success page
├── cancel.html            # Cancel page
├── composer.json          # PHP dependencies
├── README.md              # Documentation
└── images/                # Image assets
    ├── logo.png
    ├── hero-case.png
    ├── 1.png
    ├── 2.png
    ├── 3.png
    └── 4.png
```

## Requirements

- PHP 7.4 or higher
- Composer
- Stripe account
- Web server (Apache/Nginx)

## License

MIT 