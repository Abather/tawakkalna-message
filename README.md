# Tawakkalna Message

A Laravel package to send targeted and private messages to Tawakkalna users using the user's ID or residency number as the primary identifier, or the mobile number as an optional identifier. It also provides the ability to track the status of the sent message.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/abather/tawakkalna-message.svg?style=flat-square)](https://packagist.org/packages/abather/tawakkalna-message)
[![Total Downloads](https://img.shields.io/packagist/dt/abather/tawakkalna-message.svg?style=flat-square)](https://packagist.org/packages/abather/tawakkalna-message)

This package provides seamless integration with the Tawakkalna messaging API, allowing you to send notifications directly to users through the official Saudi Arabian government application. Perfect for government services, businesses, and organizations that need to communicate with users in Saudi Arabia.

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

## Requirements

- PHP ^8.4
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

```bash
composer test          # Run tests
composer test-coverage  # Run tests with coverage
composer format         # Format code with Pint
composer analyse        # Run static analysis
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Mohammed Aljuraysh](https://github.com/Abather)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
