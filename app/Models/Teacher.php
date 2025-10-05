<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Teacher extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'teacher';

    /**
     * The primary key type.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'employee_id',
        'full_name',
        'email',
        'password',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the name attribute (alias for full_name)
     */
    public function getNameAttribute()
    {
        return $this->full_name;
    }

    /**
     * Get the profile relationship
     */
    public function profile()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the classrooms owned by this teacher.
     */
    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'teacher_id', 'user_id');
    }

    /**
     * Get the assignments created by this teacher.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'teacher_id', 'user_id');
    }

    /**
     * Check if teacher is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Scope to get only active teachers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
