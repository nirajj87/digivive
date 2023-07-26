<?php

namespace App\Enums;

class BitFrameType
{

    const VIDEO = array('title' => 'Video', 'slug' => 'video');
    const AUDIO = array('title' => 'Audio', 'slug' => 'audio');

    public static function toArray()
    {
        return [
            self::VIDEO,
            self::AUDIO,

        ];
    }

}