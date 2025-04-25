<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    private static array $generatedImages = [];

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass(): void
    {
        if(empty(self::$generatedImages)) return;
        foreach (self::$generatedImages as $image) {
            if(file_exists($image)){
                unlink($image);
            }    
        }

        parent::tearDownAfterClass();

    }
    
    protected function generateRandomString(int $length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    protected function createImage(string $imageText)
    {
        $image = imagecreatetruecolor(300, 150);
        $black = imagecolorallocate($image, 0, 0, 0);
        $white = imagecolorallocate($image, 255, 255, 255);
        
        imagefilledrectangle($image, 0, 0, 299, 299, $white);
        
        $font = __DIR__."/resources/arial.ttf";
        
        $bbox = imagettfbbox(30, 0, $font, $imageText);
        
        $x = ceil($bbox[0] + (imagesx($image) / 2) - ($bbox[4] / 2) - 25);
        $y = ceil($bbox[1] + (imagesy($image) / 2) - ($bbox[5] / 2) - 5);
        
        imagettftext($image, 30, 0, $x, $y, $black, $font, $imageText);

        $imagePath = sys_get_temp_dir()."/{$imageText}.png";

        imagepng($image, $imagePath);

        self::$generatedImages[] = $imagePath;

        return $imagePath;
    }
}
