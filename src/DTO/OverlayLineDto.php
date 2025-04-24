<?php

namespace LeaReift\OcrSpace\DTO;

readonly class OverlayLineDto
{
    public function __construct(
        public string $line_text,
        public array $words,
        public float $max_height,
        public float $min_top,
    )
    {
    }

    public static function make(
        string $line_text,
        array $words,
        float $max_height,
        float $min_top,
    ): self 
    {
        return new OverlayLineDto(
            $line_text,
            $words,
            $max_height,
            $min_top,
        );
    }
}
