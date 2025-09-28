// resources/js/realtime.js

import { createClient } from '@supabase/supabase-js'

const supabaseUrl = import.meta.env.VITE_SUPABASE_URL
const supabaseAnonKey = import.meta.env.VITE_SUPABASE_ANON_KEY

const supabase = createClient(supabaseUrl, supabaseAnonKey)

// Subscribe to student activities
export function subscribeToActivities(teacherId, callback) {
    return supabase
        .channel('student_activities')
        .on('postgres_changes', {
            event: 'INSERT',
            schema: 'public',
            table: 'student_activities',
            filter: `teacher_id=eq.${teacherId}`
        }, callback)
        .subscribe()
}

// Subscribe to assignment submissions
export function subscribeToSubmissions(teacherId, callback) {
    return supabase
        .channel('submissions')
        .on('postgres_changes', {
            event: 'INSERT',
            schema: 'public',
            table: 'student_submissions',
            filter: `teacher_id=eq.${teacherId}`
        }, callback)
        .subscribe()
}

// Subscribe to progress updates
export function subscribeToProgress(teacherId, callback) {
    return supabase
        .channel('progress')
        .on('postgres_changes', {
            event: 'UPDATE',
            schema: 'public',
            table: 'student_progress',
            filter: `teacher_id=eq.${teacherId}`
        }, callback)
        .subscribe()
}

// Subscribe to AI assessments
export function subscribeToAssessments(teacherId, callback) {
    return supabase
        .channel('ai_assessments')
        .on('postgres_changes', {
            event: '*',
            schema: 'public',
            table: 'ai_assessments',
            filter: `teacher_id=eq.${teacherId}`
        }, callback)
        .subscribe()
}

// Subscribe to new students
export function subscribeToStudents(teacherId, callback) {
    return supabase
        .channel('students')
        .on('postgres_changes', {
            event: 'INSERT',
            schema: 'public',
            table: 'students',
            filter: `teacher_id=eq.${teacherId}`
        }, callback)
        .subscribe()
}

// Unsubscribe from all channels
export function unsubscribeAll() {
    supabase.removeAllChannels()
}

// Get Supabase client for direct use
export function getSupabaseClient() {
    return supabase
}
