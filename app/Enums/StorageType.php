<?php

namespace App\Enums;

class StorageType
{
    const AWS = array('title' => 'AWS', 'slug' => 'aws');
    const WOWZA = array('title' => 'WOWZA', 'slug' => 'wowza');

    public static function toArray()
    {
        return [
            self::AWS,
            self::WOWZA,
        ];
    }
}