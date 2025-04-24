<?php

namespace LeaReift\OcrSpace\Enums;

enum RequestParameterEnum: string
{
    case URL = "url";
    case FILE = "file";
    case BASE_64_IMAGE = "base64Image";
    case LANGUAGE = "language";
    case IS_OVERLAY_REQUIRED = "isOverlayRequired";
    case FILETYPE = "filetype";
    case DETECT_ORIENTATION = "detectOrientation";
    case IS_CREATE_SEARCHABLE_PDF = "isCreateSearchablePdf";
    case IS_SEARCHABLE_PDF_HIDE_TEXT_LAYER = "isSearchablePdfHideTextLayer";
    case SCALE = "scale";
    case IS_TABLE = "isTable";
    case OCR_ENGINE = "OCREngine";
}
