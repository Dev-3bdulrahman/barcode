# 🏷️ Barcode — Laravel ERP Module

[![Latest Version](https://img.shields.io/packagist/v/dev-3bdulrahman/barcode.svg?style=flat-square)](https://packagist.org/packages/dev-3bdulrahman/barcode)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue?style=flat-square)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11%2B%20%7C%2012%2B-red?style=flat-square)](https://laravel.com)
[![License](https://img.shields.io/badge/license-MIT-green?style=flat-square)](LICENSE)

A complete **Barcode Printing & Label Designer** module for Laravel ERP systems. Generate 1D and 2D barcodes, create customized print label templates, and manage bulk label printing — with full API and Livewire admin interface.

---

## Features

- Barcode Generation (Code 128, QR Code, EAN-13, EAN-8, UPC, etc.)
- Label Template Designer (custom dimensions, fields, logos)
- Batch Barcode Printing & Export
- PDF & Direct Thermal Printer Support
- Item & SKU Barcode Mapping
- REST API endpoints
- Arabic & English translations

## Requirements

| Dependency | Version |
|---|---|
| PHP | ^8.2 \| ^8.3 |
| Laravel | ^11.0 \| ^12.0 |

## Installation

```bash
composer require dev-3bdulrahman/barcode
```

Publish and run migrations:

```bash
php artisan vendor:publish --provider="Dev3bdulrahman\Barcode\BarcodeServiceProvider"
php artisan migrate
```

## Service Provider

Auto-discovered via Laravel package discovery. Manual registration in `bootstrap/providers.php`:

```php
Dev3bdulrahman\Barcode\BarcodeServiceProvider::class,
```

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for release history.

## License

MIT License © [Abdulrahman](https://3bdulrahman.com)
