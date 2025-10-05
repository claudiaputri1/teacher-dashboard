<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * DEPRECATED: This model is deprecated and should not be used.
 * In Supabase schema, teachers are users in the 'profiles' table with role='teacher'.
 * Use the User model with role filtering instead.
 * 
 * This file is kept for backward compatibility but will be removed in future versions.
 */
class Teacher extends Model
{
    // This model is deprecated - teachers are now User models with role='teacher'
    
    public function __construct(array $attributes = [])
    {
        // Log deprecation warning
        \Log::warning('Teacher model is deprecated. Use User model with role="teacher" instead.');
        parent::__construct($attributes);
    }
}
