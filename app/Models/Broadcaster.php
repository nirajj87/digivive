<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Broadcaster extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'slug',
        'status'
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
