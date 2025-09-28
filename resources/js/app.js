import './bootstrap';
import './realtime';

import Alpine from 'alpinejs';

// Import realtime functions and make them globally available
import { 
    subscribeToActivities, 
    subscribeToSubmissions, 
    subscribeToProgress,
    subscribeToAssessments,
    subscribeToStudents,
    unsubscribeAll,
    getSupabaseClient 
} from './realtime';

// Make realtime functions available globally
window.subscribeToActivities = subscribeToActivities;
window.subscribeToSubmissions = subscribeToSubmissions;
window.subscribeToProgress = subscribeToProgress;
window.subscribeToAssessments = subscribeToAssessments;
window.subscribeToStudents = subscribeToStudents;
window.unsubscribeAll = unsubscribeAll;
window.getSupabaseClient = getSupabaseClient;

window.Alpine = Alpine;

Alpine.start();
