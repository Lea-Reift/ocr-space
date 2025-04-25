<?php

namespace LeaReift\OcrSpace;

use ArrayObject;
use CURLFile;
use InvalidArgumentException;
use LeaReift\OcrSpace\DTO\ApiResponseDTO;
use LeaReift\OcrSpace\Enums\FileTypeEnum;
use LeaReift\OcrSpace\Enums\LanguageCodeEnum;
use LeaReift\OcrSpace\Enums\OCRExitCodeEnum;
use LeaReift\OcrSpace\Enums\RequestParameterEnum;
use LeaReift\OcrSpace\Support\Collection;
use SplObjectStorage;

class Client
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $endpoint;
    protected SplObjectStorage $requestParameters;

    public function __construct(string $apiKey, string $baseUrl = "https://api.ocr.space", string $endpoint = "/parse/image")
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = rtrim($baseUrl, "/");
        $this->endpoint = $endpoint;

        $this->requestParameters = new SplObjectStorage();
    }

    protected function sendRequest(string $path, string $method = 'post', array $params = []): ApiResponseDTO
    {
        $method = strtoupper($method);

        $url = $this->baseUrl . $path;

        $headers = [
            "apikey:" . $this->apiKey,
        ];

        $curlOptions = [
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => $headers,
            CURLOPT_URL             => $url,
            CURLOPT_CUSTOMREQUEST   => $method
        ];

        if (!empty($params)) {
            $curlOptions[CURLOPT_POSTFIELDS] = $params;
        }

        $curl = curl_init();

        $curlGeneration = curl_setopt_array($curl, $curlOptions);

        if (! $curlGeneration) {
            throw new \RuntimeException('curl_setopt_array failed. ' . curl_errno($curl) . ': ' . curl_error($curl));
        }

        $result = curl_exec($curl);

        curl_close($curl);

        return $this->parseResponse(new Collection(json_decode($result, true)));
    }

    protected function parseResponse(Collection $response): ApiResponseDTO
    {
        $parsedResults = $response->get('ParsedResults');
        $exitCode = OCRExitCodeEnum::from($response->get('OCRExitCode'));
        $isErroredOnProcessing = $response->get('IsErroredOnProcessing');
        $processingTimeInMilliseconds = $response->get('ProcessingTimeInMilliseconds');
        $searchablePDFURL = $response->get('SearchablePDFURL');
        $errorMessage = $response->get('ErrorMessage');
        $errorDetails = $response->get('ErrorDetails');

        return ApiResponseDTO::make(
            parsed_results: $parsedResults,
            ocr_exit_code: $exitCode,
            processing_time_in_miliseconds: $processingTimeInMilliseconds,
            is_errored_on_processing: $isErroredOnProcessing,
            error_message: $errorMessage,
            error_details: $errorDetails,
            searchable_pdf_url: $searchablePDFURL
        );
    }

    public function fromUrl(string $url): self
    {
        $url = filter_var($url, FILTER_VALIDATE_URL);

        if (!$url) {
            throw new InvalidArgumentException("'{$url}' is not a valid URL");
        }

        $this->detachInputFields();

        $this->requestParameters->attach(RequestParameterEnum::URL, $url);

        return $this;
    }

    public function fromFile(string|CURLFile $file): self
    {
        if (is_string($file)) {
            if (!file_exists($file)) {
                throw new InvalidArgumentException("'{$file}' is not a valid path or does not exist");
            }
            $file = new CURLFile($file);
        }

        $this->detachInputFields();

        $this->requestParameters->attach(RequestParameterEnum::FILE, $file);

        return $this;
    }


    public function fromBase64String(string $base64String): self
    {
        $exception = new InvalidArgumentException("Passed string is not a valid base 64 string");

        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $base64String)) throw $exception;

        // Decode the string in strict mode and check the results
        $decoded = base64_decode($base64String, true);
        if (false === $decoded || base64_encode($decoded) != $base64String) throw $exception;

        $this->detachInputFields();
        $this->requestParameters->attach(RequestParameterEnum::BASE_64_IMAGE, $base64String);

        return $this;
    }

    public function language(string|LanguageCodeEnum $language): self
    {
        if (is_string($language)) {
            $language = LanguageCodeEnum::from($language);
        }

        $this->requestParameters->attach(RequestParameterEnum::LANGUAGE, $language->value);
        return $this;
    }

    public function isOverlayRequired(bool $isRequired): self
    {
        $this->requestParameters->attach(RequestParameterEnum::IS_OVERLAY_REQUIRED, $isRequired);
        return $this;
    }

    public function detectOrientation(bool $detectOrientation): self
    {
        $this->requestParameters->attach(RequestParameterEnum::DETECT_ORIENTATION, $detectOrientation);
        return $this;
    }

    public function fileType(string|FileTypeEnum $fileType): self
    {
        if (is_string($fileType)) {
            $fileType = FileTypeEnum::from($fileType);
        }

        $this->requestParameters->attach(RequestParameterEnum::FILETYPE, $fileType->value);

        return $this;
    }

    public function isCreateSearchablePdf(bool $isCreateSearchablePdf): self
    {
        $this->requestParameters->attach(RequestParameterEnum::IS_CREATE_SEARCHABLE_PDF, $isCreateSearchablePdf);
        return $this;
    }

    public function isSearchablePdfHideTextLayer(bool $isSearchablePdfHideTextLayer): self
    {
        $this->requestParameters->attach(RequestParameterEnum::IS_SEARCHABLE_PDF_HIDE_TEXT_LAYER, $isSearchablePdfHideTextLayer);
        return $this;
    }

    public function scale(bool $scale): self
    {
        $this->requestParameters->attach(RequestParameterEnum::SCALE, $scale);
        return $this;
    }

    public function isTable(bool $isTable): self
    {
        $this->requestParameters->attach(RequestParameterEnum::IS_TABLE, $isTable);
        return $this;
    }


    public function engine(int $engine = 1): self
    {
        $this->requestParameters->attach(RequestParameterEnum::OCR_ENGINE, $engine);
        return $this;
    }

    protected function detachInputFields(): void
    {
        $this->requestParameters->detach(RequestParameterEnum::URL);
        $this->requestParameters->detach(RequestParameterEnum::FILE);
        $this->requestParameters->detach(RequestParameterEnum::BASE_64_IMAGE);
    }

    public function options(): array
    {
        $params = [];

        foreach ($this->requestParameters as $key) {
            $params[$key->value] = $this->requestParameters->offsetGet($key);
        }

        return $params;
    }

    public function get(): ApiResponseDTO
    {
        $params = $this->options();
        return $this->sendRequest($this->endpoint, params: $params);
    }
}
