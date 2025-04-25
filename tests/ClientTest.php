<?php

namespace Tests;

use LeaReift\OcrSpace\Client;
use LeaReift\OcrSpace\DTO\ApiResponseDTO;
use LeaReift\OcrSpace\DTO\MediaParsingResultDto;
use LeaReift\OcrSpace\Enums\OCRExitCodeEnum;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('curl')]
class ClientTest extends BaseTestCase
{
    protected Client $client;
    protected string $testImagePath;
    protected string $testImageText;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client("helloworld");
        $this->testImagePath = realpath(__DIR__ . "/resources/image.png");
        $this->testImageText = "OCRSpace";
    }

    public function testImageFromFileReturnsSuccess(): void
    {
        $response =  $this->client
            ->fromFile($this->testImagePath)
            ->get();

        $result = $response->parsed_results->first();

        $this->assertInstanceOf(ApiResponseDTO::class, $response);
        $this->assertInstanceOf(MediaParsingResultDto::class, $result);
        $this->assertSame($response->ocr_exit_code, OCRExitCodeEnum::PARSED_SUCCESSFULLY);
        $this->assertSame($this->testImageText, $result->parsed_text);

    }

    public function testImageFromUrlReturnsSuccess(): void
    {
        $response =  $this->client
            ->fromUrl("https://ocr.space/Content/Images/ocrspacelogo2020b.png")
            ->get();

        $result = $response->parsed_results->first();

        $this->assertInstanceOf(ApiResponseDTO::class, $response);
        $this->assertInstanceOf(MediaParsingResultDto::class, $result);
        $this->assertSame($response->ocr_exit_code, OCRExitCodeEnum::PARSED_SUCCESSFULLY);
        $this->assertSame($this->testImageText, $result->parsed_text);

    }

    public function testImageFromBase64ReturnsSuccess(): void
    {
        $payload = base64_encode(file_get_contents($this->testImagePath));

        $response =  $this->client
            ->fromBase64String($payload)
            ->get();

        $result = $response->parsed_results->first();

        $this->assertInstanceOf(ApiResponseDTO::class, $response);
        $this->assertInstanceOf(MediaParsingResultDto::class, $result);
        $this->assertSame($response->ocr_exit_code, OCRExitCodeEnum::PARSED_SUCCESSFULLY);
        $this->assertSame($this->testImageText, $result->parsed_text);
    }
}
