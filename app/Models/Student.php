<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function progress()
    {
        return $this->hasMany(StudentProgress::class);
    }
}
