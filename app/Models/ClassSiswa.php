<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSiswa extends Model
{
    protected $table = 'classes';
    protected $fillable = [
        'name',
        'teacher_id',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
