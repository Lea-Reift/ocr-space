<?php

namespace LeaReift\OcrSpace\DTO;

use LeaReift\OcrSpace\Support\Collection;

readonly class OverlayLineDto
{
    /** @property Collection<int, OverlayLineWordDto> $words */
    public Collection $words;

    public function __construct(
        public string $line_text,
        array $words,
        public float $max_height,
        public float $min_top,
    )
    {
        $this->words = Collection::make($words)
            ->mapIntoCollection()
            ->map(fn(Collection $word) => OverlayLineWordDto::make(
                word_text: $word->get("WordText"),
                left: $word->get("Left"),
                top: $word->get("Top"),
                height: $word->get("Height"),
                width: $word->get("Width"),
            ));
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
