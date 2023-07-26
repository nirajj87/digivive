<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends BaseModel
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'slug',
        'iso_code2',
        'iso_code3',
        'status',
        'country_code'
    ];

    public static function getUniqueSlug($slug)
    {
        $original = $slug;
        $count = 1;
        while (static::whereSlug($slug)->exists()) {
            $slug = "{$original}-" . $count++;
        }
        return $slug;
    }
}