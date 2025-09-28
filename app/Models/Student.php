<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $fillable = [
        'name',
        'email',
        'class_id',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassSiswa::class, 'class_id');
    }

    public function progress()
    {
        return $this->hasMany(StudentProgress::class, 'student_id');
    }
}
