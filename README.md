# Tawakkalna Message

A Laravel package for sending targeted and private messages to Tawakkalna users using the user's ID or residency number as the primary identifier, or the mobile number as an optional identifier. It also provides the ability to track the status of the sent message.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/abather/tawakkalna-message.svg?style=flat-square)](https://packagist.org/packages/abather/tawakkalna-message)
[![Total Downloads](https://img.shields.io/packagist/dt/abather/tawakkalna-message.svg?style=flat-square)](https://packagist.org/packages/abather/tawakkalna-message)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/abather/tawakkalna-message/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/abather/tawakkalna-message/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/abather/tawakkalna-message/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/abather/tawakkalna-message/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)

This package provides seamless integration with the Tawakkalna messaging API, allowing you to send notifications directly to users through the official Saudi Arabian government application. Perfect for government services, businesses, and organizations that need to communicate with users in Saudi Arabia.

## Features

- ✅ Send messages using national ID or residency number
- ✅ Optional phone number as secondary identifier
- ✅ Support for both Bearer token and Basic authentication
- ✅ Laravel notification channel integration
- ✅ On-demand notifications support
- ✅ Customizable user routing
- ✅ Comprehensive test suite
- ✅ PHP 8.3+ and Laravel 11+ support

## Installation

You can install the package via composer:

```bash
composer require abather/tawakkalna-message
```

You can publish and configure the package:

```bash
php artisan vendor:publish --tag="tawakkalna-message-config"
```

## Configuration

After publishing, configure your environment variables in `.env`:

```env
TAWAKKALNA_TOKEN=your_api_token_here
```

The published config file contains:

```php
return [
    "base_url" => env("TAWAKKALNA_BASE_URL", "https://external.api.tawakkalna.nic.gov.sa/messaging/v1"),

    // Authorization Method: bearer or basic
    'authorization_method' => env("TAWAKKALNA_AUTHORIZATION_METHOD", "bearer"),

    // Required when authorization method is bearer
    'token' => env("TAWAKKALNA_TOKEN"),

    // Required when authorization method is basic
    'username' => env("TAWAKKALNA_USERNAME"),
    'password' => env("TAWAKKALNA_PASSWORD"),

    // Default identifier attribute (national_id, residency_number, etc.)
    'identifier_attribute' => env("TAWAKKALNA_IDENTIFIER_ATTRIBUTE", "national_id")
];
```

## Usage

### Basic Usage

To send messages through Tawakkalna, implement the `toTawakkalna` method in your notification class:

```php
use Abather\TawakkalnaMessage\TawakkalnaMessage;
use Abather\TawakkalnaMessage\TawakkalnaMessageChannel;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    public function via($notifiable)
    {
        return [TawakkalnaMessageChannel::class];
    }

    public function toTawakkalna($notifiable): TawakkalnaMessage
    {
        return TawakkalnaMessage::make("Welcome to our service!")
            ->phone($notifiable->phone); // Optional: provide phone number
    }
}
```

### Simple Text Message

For simple messages, you can return a string directly:

```php
public function toTawakkalna($notifiable): string
{
    return "Your verification code is: 123456";
}
```

### Complete Example

```php
use App\Models\User;
use App\Notifications\WelcomeNotification;

// Send notification to a user
$user = User::find(1);
$user->notify(new WelcomeNotification());
```

### On-Demand Notifications

As per Laravel documentation, you can send notifications on-demand without associating them with a specific user:

```php
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Notification;

Notification::route('tawakkalna-message', '2018603478')
    ->notify(new WelcomeNotification());
```

## User Identification

The package uses user identifiers to route messages. You can configure this in several ways:

### Global Configuration

Set the default identifier attribute in your config file:

```php
'identifier_attribute' => 'national_id', // or 'residency_number'
```

### Per-User Routing

You can customize the identifier for each user by implementing routing methods in your User model:

```php
class User extends Authenticatable
{
    // Method 1: Specific to Tawakkalna
    public function routeNotificationForTawakkalna(): string
    {
        return $this->national_id; // or $this->residency_number
    }

    // Method 2: Using the channel class
    public function routeNotificationFor($driver)
    {
        if ($driver === TawakkalnaMessageChannel::class) {
            return $this->national_id;
        }
        return parent::routeNotificationFor($driver);
    }
}
```

## Check Message Status

You can track and monitor the delivery status of sent messages using the `messageStatus` method. This is essential for ensuring message delivery and handling failed deliveries appropriately.

### Basic Status Check

```php
use Abather\TawakkalnaMessage\TawakkalnaClient;

// Check status by message ID
$status = TawakkalnaClient::make()->messageStatus($messageId);
```

### Complete Example with Error Handling

```php
use Abather\TawakkalnaMessage\TawakkalnaClient;
use Illuminate\Support\Facades\Log;

try {
    $client = TawakkalnaClient::make();
    $status = $client->messageStatus($messageId);

    // Handle different status responses
    if ($status['success']) {
        Log::info('Message delivered successfully', ['message_id' => $messageId]);
    } else {
        Log::warning('Message delivery failed', [
            'message_id' => $messageId,
            'status' => $status
        ]);
    }
} catch (\Exception $e) {
    Log::error('Failed to check message status', [
        'message_id' => $messageId,
        'error' => $e->getMessage()
    ]);
}
```

### Status Response Format

The `messageStatus` method returns a structured response containing delivery information. Check the official Tawakkalna API documentation for detailed response format and status codes.

## API Documentation

For detailed information about the Tawakkalna API endpoints, authentication methods, and message formats, please refer to the official Tawakkalna API documentation.

## Error Handling

The package handles various error scenarios:

- **Invalid credentials**: Throws authentication exceptions
- **Invalid user identifiers**: Returns appropriate error responses
- **Network issues**: Provides retry mechanisms
- **Rate limiting**: Respects API rate limits

## Requirements

- PHP ^8.3
- Laravel ^11.0 || ^12.0
- Valid Tawakkalna API credentials

## Testing

Run the package tests:

```bash
composer test
```

Run tests with coverage:

```bash
composer test-coverage
```

## Available Scripts

The package includes several useful Composer scripts:

```bash
composer test           # Run PHPUnit/Pest tests
composer test-coverage  # Run tests with coverage report
composer format         # Format code with Laravel Pint
composer analyse        # Run static analysis with PHPStan
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

We welcome contributions! Please see [CONTRIBUTING](CONTRIBUTING.md) for details on how to contribute to this project.

Before contributing:
- Fork the repository
- Create a feature branch
- Run tests with `composer test`
- Ensure code style with `composer format`
- Submit a pull request

## Security Vulnerabilities

If you discover a security vulnerability within this package, please send an email to Mohammed Aljuraysh at m.abather@gmail.com. All security vulnerabilities will be promptly addressed.

Please review [our security policy](../../security/policy) for more details on how to report security vulnerabilities.

## Support

If you need help or have questions:

- Check the [documentation](README.md)
- Open an [issue on GitHub](https://github.com/abather/tawakkalna-message/issues)
- Contact the maintainer at m.abather@gmail.com

## Credits

- [Mohammed Aljuraysh](https://github.com/Abather) - Creator and maintainer
- [All Contributors](../../contributors) - Thank you for your contributions!

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
