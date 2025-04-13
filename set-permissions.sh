#!/bin/bash

# Set directory permissions to 755
find . -type d -exec chmod 755 {} \;

# Set file permissions to 644
find . -type f -exec chmod 644 {} \;

# Make scripts executable
find . -name "*.sh" -exec chmod +x {} \;

# Set special permissions for .env
chmod 600 .env

# Set special permissions for vendor directory
chmod -R 755 vendor/
chmod -R 644 vendor/*

echo "Permissions set successfully!" 