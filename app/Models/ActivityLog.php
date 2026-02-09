<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'action', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor untuk menampilkan created_at dengan timezone Indonesia
     */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->asDateTime($value)->setTimezone('Asia/Jakarta')
        );
    }

    /**
     * Accessor untuk menampilkan updated_at dengan timezone Indonesia
     */
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->asDateTime($value)->setTimezone('Asia/Jakarta')
        );
    }
}
