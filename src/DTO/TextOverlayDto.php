<?php

namespace LeaReift\OcrSpace\DTO;

readonly class TextOverlayDto
{
    public function __construct(
        public array $lines,
        public bool $has_overlay,
        public string $message,
    )
    { 
    }

    public static function make(
        array $lines,
        bool $has_overlay,
        string $message,
    ): self 
    {
        return new TextOverlayDto(
            $lines,
            $has_overlay,
            $message,
        );
    }
}
