<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends BaseModel
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'role';
    public $incrementing = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    
    protected $fillable = [
        'title',
        'slug',
        'is_parent',
        'created_by'
    ];

    public function users() {
        return $this->hasMany(Users::class)->orderBy('id');
    }

    public static function getUniqueSlug($slug) {
        $original = $slug;
        $count = 1;
        while (static::whereSlug($slug)->exists()) {
            $slug = "{$original}-" . $count++;
        }
        return $slug;
    }
    
    public function modules()
    {
        return $this->belongsToMany(Module::class);
    }
}
