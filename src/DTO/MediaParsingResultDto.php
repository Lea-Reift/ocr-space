<?php

namespace LeaReift\OcrSpace\DTO;

use LeaReift\OcrSpace\Enums\FileParseExitCodeEnum;

readonly class MediaParsingResultDto
{
    public function __construct(
        public FileParseExitCodeEnum $fiile_parse_exit_code,
        public ?string $parsed_text,
        public ?array $text_overlay,
        public bool $has_overlay,
        public ?string $error_message,
        public ?string $error_details,
    )
    {
    }

    public static function make(
        FileParseExitCodeEnum $fiile_parse_exit_code,
        ?string $parsed_text,
        ?array $text_overlay,
        bool $has_overlay,
        ?string $error_message,
        ?string $error_details,
    ): self 
    {
        return new MediaParsingResultDto(
            $fiile_parse_exit_code,
            $parsed_text,
            $text_overlay,
            $has_overlay,
            $error_message,
            $error_details,
        );
    }
}
