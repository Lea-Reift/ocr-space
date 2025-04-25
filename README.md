
# OCR Space API Wrapper

A simple wrapper for [OCR Space](https://ocr.space/) API

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/) 
[![Build Status](https://github.com/Lea-Reift/ocr-space/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/Lea-Reift/ocr-space/actions?query=workflow%3A"PHP+Composer")


## Installation

This wrapper requires:
* PHP ^8.2
* CURL extension enabled

You can easily install the wrapper with [Composer](https://getcomposer.org/):

```bash
  composer require lea-reift/ocr-space
```
## Usage

The only required parameter for the Client is your apikey (this will be used in the requests). Then, you can chain the desired options in helper methods for the accepted request parameters.

```php
require_once 'vendor/autoload.php'; // Don't forget the autoload

use LeaReift\OcrSpace\Client;

$response = (new Client('your-apikey'))
    ->fromUrl('https://dl.a9t9.com/ocr/solarcell.jpg')
    ->get();
```

## API Reference

The `Client` class provides an interface to interact with the OCR.space API, enabling optical character recognition on images from different sources. Check [OCR Space Documentation](https://ocr.space/OCRAPI#PostParameters) to dig deeper with the accepted values, but the library also counts with typed parameters methods, so use it won't be to difficult.

### Constructor

```php
public function __construct(string $apiKey, string $baseUrl = "https://api.ocr.space", string $endpoint = "/parse/image")
```

Creates a new instance of the OCR Space client.

**Parameters:**
- `$apiKey` - The API key for authenticating with the OCR.space service
- `$baseUrl` - The base URL of the API (optional, defaults to "https://api.ocr.space")
- `$endpoint` - The API endpoint (optional, defaults to "/parse/image")

```php
use LeaReift\OcrSpace\Client;

$client = new Client('your-api-key-here');
```

### Data Input Methods

#### fromUrl

```php
public function fromUrl(string $url): self
```

Sets an image URL as input for OCR processing.

**Parameters:**
- `$url` - Valid URL of the image to be processed

```php
$client->fromUrl('https://example.com/image.jpg');
```

#### fromFile

```php
public function fromFile(string|CURLFile $file): self
```

Sets a local file as input for OCR processing.

**Parameters:**
- `$file` - File path as string or CURLFile instance

```php
// Using string
$client->fromFile('/path/to/image.png');

// Using CURLFile
$curlFile = new CURLFile('/path/to/image.png');
$client->fromFile($curlFile);
```

#### fromBase64String

```php
public function fromBase64String(string $base64String): self
```

Sets a base64 string representing an image as input for OCR processing.

**Parameters:**
- `$base64String` - Base64 string representing an image

```php
$base64Image = base64_encode(file_get_contents('image.jpg'));
$client->fromBase64String($base64Image);
```

## Configuration Methods

#### language

```php
public function language(string|LanguageCodeEnum $language): self
```

Sets the language for OCR recognition.

**Parameters:**
- `$language` - Language code as string or LanguageCodeEnum instance

```php
use LeaReift\OcrSpace\Enums\LanguageCodeEnum;

// Using enum
$client->language(LanguageCodeEnum::ENGLISH);

// Using string
$client->language('eng');
```

#### isOverlayRequired

```php
public function isOverlayRequired(bool $isRequired): self
```

Determines if overlay is required on the processed image.

**Parameters:**
- `$isRequired` - Boolean indicating if overlay is required

```php
$client->isOverlayRequired(true);
```

#### detectOrientation

```php
public function detectOrientation(bool $detectOrientation): self
```

Configures whether to automatically detect the image orientation.

**Parameters:**
- `$detectOrientation` - Boolean indicating if orientation should be detected

```php
$client->detectOrientation(true);
```

#### fileType

```php
public function fileType(string|FileTypeEnum $fileType): self
```

Sets the file type of the input image.

**Parameters:**
- `$fileType` - File type as string or FileTypeEnum instance

```php
use LeaReift\OcrSpace\Enums\FileTypeEnum;

// Using enum
$client->fileType(FileTypeEnum::PDF);

// Using string
$client->fileType('pdf');
```

#### isCreateSearchablePdf

```php
public function isCreateSearchablePdf(bool $isCreateSearchablePdf): self
```

Determines if a searchable PDF should be created.

**Parameters:**
- `$isCreateSearchablePdf` - Boolean indicating if a searchable PDF should be created

```php
$client->isCreateSearchablePdf(true);
```

#### isSearchablePdfHideTextLayer

```php
public function isSearchablePdfHideTextLayer(bool $isSearchablePdfHideTextLayer): self
```

Determines if the text layer should be hidden in the searchable PDF.

**Parameters:**
- `$isSearchablePdfHideTextLayer` - Boolean indicating if the text layer should be hidden

```php
$client->isSearchablePdfHideTextLayer(false);
```

#### scale

```php
public function scale(bool $scale): self
```

Determines if the image should be scaled to improve OCR recognition.

**Parameters:**
- `$scale` - Boolean indicating if the image should be scaled

```php
$client->scale(true);
```

#### isTable

```php
public function isTable(bool $isTable): self
```

Configures if the image contains data in table format.

**Parameters:**
- `$isTable` - Boolean indicating if the image contains tables

```php
$client->isTable(true);
```

#### engine

```php
public function engine(int $engine = 1): self
```

Sets the OCR engine to use.

**Parameters:**
- `$engine` - Integer representing the OCR engine (defaults to 1)

```php
$client->engine(2);
```

#### options

```php
public function options(): array
```

Returns an array with all currently configured options.

```php
$options = $client->options();
print_r($options);
```

## Complete Usage Example

```php
use LeaReift\OcrSpace\Client;
use LeaReift\OcrSpace\Enums\LanguageCodeEnum;

// Create client
$client = new Client('your-api-key-here');

// Configure options
$response = $client
    ->fromUrl('https://example.com/invoice.jpg')
    ->language(LanguageCodeEnum::ENGLISH)
    ->detectOrientation(true)
    ->isTable(true)
    ->engine(2)
    ->get();

// Process the response
if (!$response->is_errored_on_processing) {
    echo "Recognized text count: " . $response->parsed_results->count();
} else {
    echo "Error: " . $response->error_message;
}
```


## Contributing

Contributions are always welcome!
Just open a Pull Request with your changes and I'll review it as soon as I can!

## Credits

OCR.space is a service of [a9t9 software GmbH](https://a9t9.com/about). They are also in [github](https://github.com/A9T9)

This package is an independent development that is in no way linked to a9t9 software. I want to acknowledge a9t9 for put this OCR service online.

## License

This wrapper is made available under the MIT License (MIT). Please see [License File](LICENSE.md) for more information.