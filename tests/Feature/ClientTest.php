<?php

namespace Tests;

use CURLFile;
use InvalidArgumentException;
use LeaReift\OcrSpace\Client;
use PHPUnit\Framework\TestCase;
use LeaReift\OcrSpace\DTO\ApiResponseDTO;
use LeaReift\OcrSpace\Enums\FileTypeEnum;
use LeaReift\OcrSpace\Enums\OCRExitCodeEnum;
use LeaReift\OcrSpace\Enums\LanguageCodeEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('curl')]
class ClientTest extends TestCase
{
    protected Client $client;
    protected string $testImagePath;
    protected string $testImageText;
    protected string $testBase64Image;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client("helloworld");
        $this->testImageText = "OCRSpace";
        $this->testImagePath = realpath(__DIR__ . "/../resources/image.png");
        $this->testBase64Image = base64_encode(file_get_contents($this->testImagePath));
    }

    public function testImageFromFileReturnsSuccess(): void
    {
        $response = $this->client
            ->fromFile($this->testImagePath)
            ->get();

        $result = $response->parsed_results->first();

        $this->assertInstanceOf(ApiResponseDTO::class, $response);
        $this->assertNotNull($result);
        $this->assertSame($response->ocr_exit_code, OCRExitCodeEnum::PARSED_SUCCESSFULLY);
        $this->assertSame($this->testImageText, $result->parsed_text);
    }

    public function testImageFromFileThrowsExceptionForInvalidPath(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("'invalid/path' is not a valid path or does not exist");

        $this->client->fromFile('invalid/path');
    }

    public function testImageFromFileAcceptsCURLFile(): void
    {
        $curlFile = new CURLFile($this->testImagePath);
        $response = $this->client
            ->fromFile($curlFile)
            ->get();

        $result = $response->parsed_results->first();

        $this->assertInstanceOf(ApiResponseDTO::class, $response);
        $this->assertNotNull($result);
        $this->assertSame($response->ocr_exit_code, OCRExitCodeEnum::PARSED_SUCCESSFULLY);
        $this->assertSame($this->testImageText, $result->parsed_text);
    }

    public function testImageFromUrlReturnsSuccess(): void
    {
        $response = $this->client
            ->fromUrl("https://ocr.space/Content/Images/ocrspacelogo2020b.png")
            ->get();

        $result = $response->parsed_results->first();

        $this->assertInstanceOf(ApiResponseDTO::class, $response);
        $this->assertNotNull($result);
        $this->assertSame($response->ocr_exit_code, OCRExitCodeEnum::PARSED_SUCCESSFULLY);
        $this->assertSame($this->testImageText, $result->parsed_text);
    }

    public function testImageFromUrlThrowsExceptionForInvalidUrl(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("'invalid-url' is not a valid URL");

        $this->client->fromUrl('invalid-url');
    }

    public function testImageFromBase64ReturnsSuccess(): void
    {
        $response = $this->client
            ->fromBase64String($this->testBase64Image)
            ->get();

        $result = $response->parsed_results->first();

        $this->assertInstanceOf(ApiResponseDTO::class, $response);
        $this->assertNotNull($result);
        $this->assertSame($response->ocr_exit_code, OCRExitCodeEnum::PARSED_SUCCESSFULLY);
        $this->assertSame($this->testImageText, $result->parsed_text);
    }

    #[DataProvider('invalidBase64Provider')]
    public function testImageFromBase64ThrowsExceptionForInvalidBase64(string $invalidBase64): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Passed string is not a valid base 64 string");

        $this->client->fromBase64String($invalidBase64);
    }

    public static function invalidBase64Provider(): array
    {
        return [
            ['not-a-valid-base64'],
            ['/9j/invalid characters'],
        ];
    }

    #[DataProvider('languageDataProvider')]
    public function testLanguageSetsCorrectParameter(string|LanguageCodeEnum $language, string $expectedValue): void
    {
        $client = $this->client->language($language);
        $options = $client->options();
        $this->assertArrayHasKey('language', $options);
        $this->assertSame($expectedValue, $options['language']);
    }

    public static function languageDataProvider(): array
    {
        return [
            [LanguageCodeEnum::ENGLISH, 'eng'],
            ['spa', 'spa'],
        ];
    }

    public function testIsOverlayRequiredSetsCorrectParameter(): void
    {
        $client = $this->client->isOverlayRequired(true);
        $options = $client->options();
        $this->assertArrayHasKey('isOverlayRequired', $options);
        $this->assertSame(true, $options['isOverlayRequired']);

        $client = $this->client->isOverlayRequired(false);
        $options = $client->options();
        $this->assertArrayHasKey('isOverlayRequired', $options);
        $this->assertSame(false, $options['isOverlayRequired']);
    }

    public function testDetectOrientationSetsCorrectParameter(): void
    {
        $client = $this->client->detectOrientation(true);
        $options = $client->options();
        $this->assertArrayHasKey('detectOrientation', $options);
        $this->assertSame(true, $options['detectOrientation']);

        $client = $this->client->detectOrientation(false);
        $options = $client->options();
        $this->assertArrayHasKey('detectOrientation', $options);
        $this->assertSame(false, $options['detectOrientation']);
    }

    #[DataProvider('fileTypeDataProvider')]
    public function testFileTypeSetsCorrectParameter(string|FileTypeEnum $fileType, string $expectedValue): void
    {
        $client = $this->client->fileType($fileType);
        $options = $client->options();
        $this->assertArrayHasKey('filetype', $options);
        $this->assertSame($expectedValue, $options['filetype']);
    }

    public static function fileTypeDataProvider(): array
    {
        return [
            [FileTypeEnum::PNG, 'PNG'],
            ['JPG', 'JPG'],
        ];
    }

    public function testIsCreateSearchablePdfSetsCorrectParameter(): void
    {
        $client = $this->client->isCreateSearchablePdf(true);
        $options = $client->options();
        $this->assertArrayHasKey('isCreateSearchablePdf', $options);
        $this->assertSame(true, $options['isCreateSearchablePdf']);

        $client = $this->client->isCreateSearchablePdf(false);
        $options = $client->options();
        $this->assertArrayHasKey('isCreateSearchablePdf', $options);
        $this->assertSame(false, $options['isCreateSearchablePdf']);
    }

    public function testIsSearchablePdfHideTextLayerSetsCorrectParameter(): void
    {
        $client = $this->client->isSearchablePdfHideTextLayer(true);
        $options = $client->options();
        $this->assertArrayHasKey('isSearchablePdfHideTextLayer', $options);
        $this->assertSame(true, $options['isSearchablePdfHideTextLayer']);

        $client = $this->client->isSearchablePdfHideTextLayer(false);
        $options = $client->options();
        $this->assertArrayHasKey('isSearchablePdfHideTextLayer', $options);
        $this->assertSame(false, $options['isSearchablePdfHideTextLayer']);
    }

    public function testScaleSetsCorrectParameter(): void
    {
        $client = $this->client->scale(true);
        $options = $client->options();
        $this->assertArrayHasKey('scale', $options);
        $this->assertSame(true, $options['scale']);

        $client = $this->client->scale(false);
        $options = $client->options();
        $this->assertArrayHasKey('scale', $options);
        $this->assertSame(false, $options['scale']);
    }

    public function testIsTableSetsCorrectParameter(): void
    {
        $client = $this->client->isTable(true);
        $options = $client->options();
        $this->assertArrayHasKey('isTable', $options);
        $this->assertSame(true, $options['isTable']);

        $client = $this->client->isTable(false);
        $options = $client->options();
        $this->assertArrayHasKey('isTable', $options);
        $this->assertSame(false, $options['isTable']);
    }

    #[DataProvider('engineDataProvider')]
    public function testEngineSetsCorrectParameter(int $engine, int $expectedValue): void
    {
        $client = $this->client->engine($engine);
        $options = $client->options();
        $this->assertArrayHasKey('OCREngine', $options);
        $this->assertSame($expectedValue, $options['OCREngine']);
    }

    public static function engineDataProvider(): array
    {
        return [
            [1, 1],
            [2, 2],
        ];
    }

    public function testReturnInvalidResponseWhenUsingInvalidEngine(): void
    {
        $this->markTestSkipped("Tooks too long to return the time out response");

        $response = $this->client
            ->fromBase64String($this->testBase64Image)
            ->engine(3)
            ->get();

        $this->assertInstanceOf(ApiResponseDTO::class, $response);
        $this->assertSame($response->ocr_exit_code, OCRExitCodeEnum::TIME_OUT);
        $this->assertEmpty($response->parsed_results);
    }

    public function testOptionsReturnsCorrectParameters(): void
    {
        $this->client
            ->fromUrl("https://example.com/image.png")
            ->language(LanguageCodeEnum::ARABIC)
            ->isOverlayRequired(true)
            ->fileType(FileTypeEnum::PDF)
            ->engine(2);

        $options = $this->client->options();

        $this->assertArrayHasKey('url', $options);
        $this->assertSame('https://example.com/image.png', $options['url']);
        $this->assertArrayHasKey('language', $options);
        $this->assertSame('ara', $options['language']);
        $this->assertArrayHasKey('isOverlayRequired', $options);
        $this->assertSame(true, $options['isOverlayRequired']);
        $this->assertArrayHasKey('filetype', $options);
        $this->assertSame('PDF', $options['filetype']);
        $this->assertArrayHasKey('OCREngine', $options);
        $this->assertSame(2, $options['OCREngine']);
    }

    public function testSetLanguageAutoIfEngineIs2()
    {
        $this->client
            ->fromUrl("https://example.com/image.png")
            ->isOverlayRequired(true)
            ->fileType(FileTypeEnum::PDF)
            ->engine(2);

        $options = $this->client->options();
        $this->assertArrayHasKey('language', $options);
        $this->assertSame('auto', $options['language']);
    }

    public function testNotSetLanguageAutoIfEngineIs2AndLangWasSet()
    {
        $this->client
            ->fromUrl("https://example.com/image.png")
            ->language(LanguageCodeEnum::ENGLISH)
            ->isOverlayRequired(true)
            ->fileType(FileTypeEnum::PDF)
            ->engine(2);

        $options = $this->client->options();
        $this->assertArrayHasKey('language', $options);
        $this->assertSame('eng', $options['language']);
    }
}
