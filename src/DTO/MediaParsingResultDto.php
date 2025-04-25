<?php

namespace LeaReift\OcrSpace\DTO;

use LeaReift\OcrSpace\Support\Collection;
use LeaReift\OcrSpace\Enums\FileParseExitCodeEnum;

readonly class MediaParsingResultDto
{
    public TextOverlayDto $text_overlay;

    public function __construct(
        public FileParseExitCodeEnum $file_parse_exit_code,
        public ?string $parsed_text,
        ?array $text_overlay,
        public ?string $error_message,
        public ?string $error_details,
    ) {
        $overlay = Collection::make($text_overlay);
        $this->text_overlay = TextOverlayDto::make(
            lines: $overlay->get("Lines"),
            has_overlay: $overlay->get("HasOverlay"),
            message: $overlay->get("Message"),
        );
    }

    public static function make(
        FileParseExitCodeEnum $file_parse_exit_code,
        ?string $parsed_text,
        ?array $text_overlay,
        ?string $error_message,
        ?string $error_details,
    ): self {
        return new MediaParsingResultDto(
            $file_parse_exit_code,
            $parsed_text,
            $text_overlay,
            $error_message,
            $error_details,
        );
    }
}
