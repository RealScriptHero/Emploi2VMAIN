<?php

return [
    'title' => 'Settings',
    'breadcrumb' => 'System → Settings',

    'general' => [
        'title' => 'General Settings',
        'institution_name' => 'Institution name',
        'academic_year' => 'Academic Year',
        'academic_year_warning' => '⚠️ Changing the academic year resets progress, absences, internships and reports.',
        'director' => 'Director',
        'language' => 'Language',
        'languages' => [
            'french' => '🇫🇷 French',
            'english' => '🇬🇧 English',
        ],
        'save_changes' => '💾 Save Changes',
    ],

    'appearance' => [
        'title' => 'Appearance',
        'dark_mode' => 'Dark Mode',
        'dark_mode_sub' => 'Toggle dark/light theme',
        'on' => 'On',
        'off' => 'Off',
        'compact_sidebar' => 'Compact Sidebar',
        'compact_sidebar_sub' => 'Collapse sidebar by default',
        'toggle' => 'Toggle',
        'accent_color' => 'Accent Color',
        'accent_color_hint' => 'Pick a color to update button accents across the system.',
    ],

    'security' => [
        'title' => 'Security',
        'current_password' => 'Current password',
        'new_password' => 'New password',
        'confirm_password' => 'Confirm password',
        'update_password' => '🔑 Update Password',
    ],

    'messages' => [
        'general_updated' => 'General settings updated successfully.',
        'appearance_updated' => 'Appearance updated successfully.',
        'account_updated' => 'Account updated successfully.',
        'academic_year_reset' => 'Timetables for academic year :year have been reset.',
        'timetable_updated' => 'Timetable settings updated successfully.',
        'reports_updated' => 'Report settings updated successfully.',
        'password_updated' => 'Password updated successfully.',
    ],

    'errors' => [
        'current_password_incorrect' => 'The current password is incorrect.',
        'academic_year_missing' => 'No academic year is defined.',
        'academic_year_invalid' => 'Invalid academic year format. Use YYYY-YYYY.',
    ],
];
