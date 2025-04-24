<?php

namespace LeaReift\OcrSpace\Enums;

enum OCRExitCodeEnum: int
{
    case PARSED_SUCCESSFULLY = 1;
    case PARSED_PARTIALLY = 2;
    case PARSED_FAILURE = 3;
    case FATAL_ERROR = 4;
}
