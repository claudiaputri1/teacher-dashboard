<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $fillable = [
        'title',
        'description',
    ];

    public function progress(): HasMany
    {
        return $this->hasMany(StudentProgress::class, 'module_id');
    }
}
