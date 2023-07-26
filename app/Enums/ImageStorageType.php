<?php

namespace App\Enums;

class ImageStorageType
{

    const AWS = array('title' => 'AWS', 'slug' => 'aws');
    const CLOUDINARY = array('title' => 'CLOUDINARY', 'slug' => 'cloudinary');

    public static function toArray()
    {
        return [
            self::AWS,
            self::CLOUDINARY,

        ];
    }

}