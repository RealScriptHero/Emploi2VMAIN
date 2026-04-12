<?php

return [
    'title' => 'Paramètres',
    'breadcrumb' => 'Système → Paramètres',

    'general' => [
        'title' => 'Paramètres généraux',
        'institution_name' => 'Nom de l\'établissement',
        'academic_year' => 'Année académique',
        'academic_year_warning' => '⚠️ Changer l\'année réinitialise : avancement, absences, stages et rapports.',
        'director' => 'Directeur',
        'language' => 'Langue',
        'languages' => [
            'french' => '🇫🇷 Français',
            'english' => '🇬🇧 Anglais',
        ],
        'save_changes' => '💾 Enregistrer les modifications',
    ],

    'appearance' => [
        'title' => 'Apparence',
        'dark_mode' => 'Mode sombre',
        'dark_mode_sub' => 'Basculer entre thème sombre et clair',
        'on' => 'Activé',
        'off' => 'Désactivé',
        'compact_sidebar' => 'Barre latérale compacte',
        'compact_sidebar_sub' => 'Réduire la barre latérale par défaut',
        'toggle' => 'Basculer',
        'accent_color' => 'Couleur d\'accent',
        'accent_color_hint' => 'Choisissez une couleur pour mettre à jour les accents des boutons.',
    ],

    'security' => [
        'title' => 'Sécurité',
        'current_password' => 'Mot de passe actuel',
        'new_password' => 'Nouveau mot de passe',
        'confirm_password' => 'Confirmer le mot de passe',
        'update_password' => '🔑 Mettre à jour le mot de passe',
    ],

    'messages' => [
        'general_updated' => 'Paramètres généraux mis à jour avec succès.',
        'appearance_updated' => 'Apparence mise à jour avec succès.',
        'account_updated' => 'Compte mis à jour avec succès.',
        'academic_year_reset' => 'Les emplois de l\'année académique :year ont été réinitialisés.',
        'timetable_updated' => 'Paramètres d\'emploi du temps mis à jour avec succès.',
        'reports_updated' => 'Paramètres de rapports mis à jour avec succès.',
        'password_updated' => 'Mot de passe mis à jour avec succès.',
    ],

    'errors' => [
        'current_password_incorrect' => 'Le mot de passe actuel est incorrect.',
        'academic_year_missing' => 'Aucune année académique définie.',
        'academic_year_invalid' => 'Format d\'année académique invalide. Utilisez AAAA-AAAA.',
    ],
];
