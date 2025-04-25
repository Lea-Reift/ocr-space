<?php

namespace LeaReift\OcrSpace\DTO;

readonly class OverlayLineWordDto
{
    public function __construct(
        public string $word_text,
        public float $left,
        public float $top,
        public float $height,
        public float $width,
    ) {
    }

    public static function make(
        string $word_text,
        float $left,
        float $top,
        float $height,
        float $width,
    ): self {
        return new OverlayLineWordDto(
            $word_text,
            $left,
            $top,
            $height,
            $width,
        );
    }
}
