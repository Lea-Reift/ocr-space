<?php

namespace LeaReift\OcrSpace\Enums;

enum FileParseExitCodeEnum: int
{
    case FILE_NOT_FOUND = 0;
    case SUCCESS = 1;
    case OCR_ENGINE_PARSE_ERROR = -10;
    case TIMEOUT = -20;
    case VALIDATION_ERROR = -30;
    case UNKNOWN_ERROR = -99;
}
