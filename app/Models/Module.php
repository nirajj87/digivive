<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;

class Module extends BaseModel
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'module';
    public $incrementing = true;

    protected $fillable = [
        'title',
        'slug',
        'parent_id',
        'icon',
        'order',
        'role',
        'status',
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

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
