<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The table associated with the model.
     * Using 'profiles' table from Supabase
     *
     * @var string
     */
    protected $table = 'profiles';

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
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'email',
        'full_name',
        'avatar_url',
        'role',
        'school_name',
        'grade_level',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the password for authentication.
     * Note: Supabase handles authentication, this is for compatibility
     */
    public function getAuthPassword()
    {
        return null; // Supabase handles auth
    }

    /**
     * Get classrooms where this user is the teacher
     */
    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    /**
     * Get classrooms where this user is a student (through classroom_members)
     */
    public function enrolledClassrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_members', 'student_id', 'classroom_id')
            ->withTimestamps()
            ->withPivot('joined_at');
    }

    /**
     * Get student progress records for this user
     */
    public function progress()
    {
        return $this->hasMany(StudentProgress::class, 'user_id');
    }

    /**
     * Get assignments created by this user (if teacher)
     */
    public function createdAssignments()
    {
        return $this->hasMany(Assignment::class, 'teacher_id');
    }

    /**
     * Get assignment submissions by this user (if student)
     */
    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'student_id');
    }

    /**
     * Check if user is a teacher
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /**
     * Check if user is a student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Scope to get only teachers
     */
    public function scopeTeachers($query)
    {
        return $query->where('role', 'teacher');
    }

    /**
     * Scope to get only students
     */
    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }
}
