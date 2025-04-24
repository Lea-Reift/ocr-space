<?php

namespace LeaReift\OcrSpace\DTO;

use LeaReift\OcrSpace\Enums\OCRExitCodeEnum;

readonly class ApiResponseDTO
{
    public function __construct(
        public array $parsed_results,
        public OCRExitCodeEnum $ocr_exit_code,
        public bool $is_errored_on_processing,
        public string $error_message,
        public string $error_details,
        public ?string $searchable_pdf_url
    )
    {
    }

    public static function make(
        array $parsed_results,
        OCRExitCodeEnum $ocr_exit_code,
        bool $is_errored_on_processing,
        string $error_message,
        string $error_details,
        ?string $searchable_pdf_url
    ): static 
    {
        return new ApiResponseDTO(
            $parsed_results,
            $ocr_exit_code,
            $is_errored_on_processing,
            $error_message,
            $error_details,
            $searchable_pdf_url
        );
    }
}
