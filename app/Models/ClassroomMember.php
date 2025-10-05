<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassroomMember extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'classroom_members';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = true;

    /**
     * The primary key for the model.
     */
    protected $primaryKey = ['classroom_id', 'student_id'];

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The data type of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'classroom_id',
        'student_id',
        'joined_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
        ];
    }

    /**
     * Get the classroom that owns the member.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    /**
     * Get the student (profile) that owns the member.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
