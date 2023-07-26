<?php

namespace App\Enums;

class SearchType
{
    const ALGOLIA = array('title' => 'ALGOLIA', 'slug' => 'algolia');
    const TYPESENSE = array('title' => 'TYPESENSE','slug'=>'typesense');

    public static function toArray()
    {
        return [
            self::ALGOLIA,
            self::TYPESENSE,
          
        ];
    }
}
