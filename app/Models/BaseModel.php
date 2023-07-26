<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    public function getCreatedAtAttribute($value)
    {
        return $this->convertToUserTimezone($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return $this->convertToUserTimezone($value);
    }

    private function convertToUserTimezone($value)
    {
        // Retrieve the authenticated user's timezone or use a default timezone if not available or invalid
        $timezone = optional(auth()->user())->timezone ?: 'UTC';

        try {
            return Carbon::parse($value)->setTimezone($timezone);
        } catch (\Exception $e) {
            // Handle the exception or log the error
            return $value; // Return the original value if an error occurs
        }
    }
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


