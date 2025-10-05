<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * DEPRECATED: This model is deprecated and should not be used.
 * Use the Classroom model instead, which matches the Supabase schema.
 * 
 * This file is kept for backward compatibility but will be removed in future versions.
 */
class ClassSiswa extends Model
{
    // This model is deprecated - use Classroom model instead
    protected $table = 'classrooms'; // Updated to match Supabase
    
    // Redirect to Classroom model
    public function __construct(array $attributes = [])
    {
        // Log deprecation warning
        \Log::warning('ClassSiswa model is deprecated. Use Classroom model instead.');
        parent::__construct($attributes);
    }
}
