<?php

namespace LeaReift\OcrSpace\DTO;

use LeaReift\OcrSpace\Support\Collection;

readonly class TextOverlayDto
{
    public Collection $lines;

    public function __construct(
        array $lines,
        public bool $has_overlay,
        public string $message,
    )
    {
        $this->lines = Collection::make($lines)
            ->mapIntoCollection()
            ->map(fn(Collection $line) => OverlayLineDto::make(
                line_text: $line->get("LineText"),
                words: $line->get("Words"),
                max_height: floatval($line->get("MaxHeight")),
                min_top: floatval($line->get("MinTop"))
            ));
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
