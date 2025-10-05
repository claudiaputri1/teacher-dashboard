<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * DEPRECATED: This model is deprecated and should not be used.
 * In Supabase schema, students are users in the 'profiles' table with role='student'.
 * Use the User model with role filtering instead.
 * 
 * This file is kept for backward compatibility but will be removed in future versions.
 */
class Student extends Model
{
    // This model is deprecated - students are now User models with role='student'
    
    public function __construct(array $attributes = [])
    {
        // Log deprecation warning
        \Log::warning('Student model is deprecated. Use User model with role="student" instead.');
        parent::__construct($attributes);
    }
}
