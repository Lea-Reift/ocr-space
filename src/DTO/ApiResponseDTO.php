<?php

namespace LeaReift\OcrSpace\DTO;

use LeaReift\OcrSpace\Support\Collection;
use LeaReift\OcrSpace\Enums\OCRExitCodeEnum;
use LeaReift\OcrSpace\Enums\FileParseExitCodeEnum;

/**
 * @property Collection<int, MediaParsingResultDto> $parsed_results
*/
readonly class ApiResponseDTO
{
    public Collection $parsed_results;

    public function __construct(
        ?array $parsed_results,
        public OCRExitCodeEnum $ocr_exit_code,
        public int $processing_time_in_miliseconds,
        public bool $is_errored_on_processing,
        public null|string|array $error_message,
        public null|string|array $error_details,
        public ?string $searchable_pdf_url
    ) {
        $this->parsed_results = Collection::make($parsed_results)
            ->mapIntoCollection()
            ->map(fn (Collection $parsedResult) => MediaParsingResultDto::make(
                file_parse_exit_code: FileParseExitCodeEnum::from($parsedResult->get("FileParseExitCode")),
                parsed_text: trim($parsedResult->get("ParsedText")),
                text_overlay: $parsedResult->get("TextOverlay"),
                error_message: $parsedResult->get("ErrorMessage"),
                error_details: $parsedResult->get("ErrorDetails"),
            ));
    }

    public static function make(
        ?array $parsed_results,
        OCRExitCodeEnum $ocr_exit_code,
        int $processing_time_in_miliseconds,
        bool $is_errored_on_processing,
        null|string|array $error_message,
        null|string|array $error_details,
        ?string $searchable_pdf_url
    ): static {
        return new ApiResponseDTO(
            $parsed_results,
            $ocr_exit_code,
            $processing_time_in_miliseconds,
            $is_errored_on_processing,
            $error_message,
            $error_details,
            $searchable_pdf_url
        );
    }
}
