<?php

namespace App\Http\Controllers;

use App\Models\EmploiDuTemps;
use App\Models\AbsenceFormateur;
use App\Models\AbsenceGroupe;
use App\Models\Avancement;
use App\Models\Groupe;
use App\Models\Module;
use App\Models\Formateur;
use App\Models\Stage;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::getAll();
        $user = Auth::user();

        if (empty($settings['institution_name'])) {
            $defaultInstitution = 'Office de la Formation Professionnelle et de la Promotion du Travail';
            Setting::set('institution_name', $defaultInstitution);
            $settings['institution_name'] = $defaultInstitution;
        }

        if (empty($settings['academic_year'])) {
            $settings['academic_year'] = $this->resolveAcademicYearFromTimetable();
            if ($settings['academic_year']) {
                Setting::set('academic_year', $settings['academic_year']);
            }
        }

        if ($user) {
            $settings['director_name'] = trim(($user->nom ?? '') . ' ' . ($user->prenom ?? ''));
        }

        return view('parametres.index', compact('settings', 'user'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'institution_name' => 'required|string|max:255',
            'academic_year' => 'required|string',
            'director_name' => 'required|string|max:255',
            'language' => 'required|in:en,fr',
        ]);

        $currentAcademicYear = Setting::get('academic_year');

        Setting::set('institution_name', $request->institution_name);
        Setting::set('academic_year', $request->academic_year);
        Setting::set('director_name', $request->director_name);
        Setting::set('language', $request->language);

        if ($currentAcademicYear !== $request->academic_year) {
            $this->resetAcademicYearEntries($request->academic_year);
        }

        return back()->with('success', __('settings.messages.general_updated'));
    }

    protected function resetAcademicYearEntries(string $newAcademicYear): void
    {
        // Archive dynamic data by setting academic_year
        // This allows switching back to previous years and restoring old data
        // NOTE: Static data (groups, trainers, modules) are not affected

        $currentYear = Setting::get('academic_year');

        if (!$currentYear) {
            return; // No current year set, nothing to archive
        }

        // Archive only DYNAMIC data with the current academic year

        // Archive EmploiDuTemps entries
        EmploiDuTemps::withoutGlobalScope('academic_year')
            ->whereNull('academic_year')
            ->update(['academic_year' => $currentYear]);

        // Archive AbsenceFormateur entries
        AbsenceFormateur::withoutGlobalScope('academic_year')
            ->whereNull('academic_year')
            ->update(['academic_year' => $currentYear]);

        // Archive AbsenceGroupe entries
        AbsenceGroupe::withoutGlobalScope('academic_year')
            ->whereNull('academic_year')
            ->update(['academic_year' => $currentYear]);

        // Archive Avancement entries
        Avancement::withoutGlobalScope('academic_year')
            ->whereNull('academic_year')
            ->update(['academic_year' => $currentYear]);

        // Archive Stage entries
        Stage::withoutGlobalScope('academic_year')
            ->whereNull('academic_year')
            ->update(['academic_year' => $currentYear]);

        // NOTE: Groupe, Module, Formateur, Salle, Centre are static base data
        // They do NOT have academic_year and should NOT be archived
        // They remain the same across all years
    }

    protected function resolveAcademicYearFromTimetable(): string
    {
        $latestDate = EmploiDuTemps::query()->latest('date')->value('date');
        if (!$latestDate) {
            $now = Carbon::now();
            $start = $now->month >= 9 ? $now->year : $now->year - 1;
            $end = $start + 1;
            return "{$start}/{$end}";
        }

        $date = Carbon::parse($latestDate);
        $start = $date->month >= 9 ? $date->year : $date->year - 1;
        $end = $start + 1;

        return "{$start}/{$end}";
    }

    public function updateAppearance(Request $request)
    {
        $request->validate([
            'theme_mode' => 'nullable|in:system,light,dark',
            'accent_color' => 'nullable|string',
        ]);

        if ($request->filled('theme_mode')) {
            Setting::set('theme_mode', $request->theme_mode);
        }

        if ($request->filled('accent_color')) {
            Setting::set('accent_color', $request->accent_color);
        }

        return back()->with('success', __('settings.messages.appearance_updated'));
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme_mode' => 'required|in:light,dark',
        ]);

        Setting::set('theme_mode', $request->theme_mode);

        return response()->json(['success' => true]);
    }

    public function updateLanguage(Request $request)
    {
        $request->validate([
            'language' => 'required|in:en,fr',
        ]);

        Setting::set('language', $request->language);

        return response()->json(['success' => true]);
    }

    public function resetAcademicYear(Request $request)
    {
        $academicYear = Setting::get('academic_year', $request->academic_year ?? '');
        if (!$academicYear) {
            return back()->with('error', __('settings.errors.academic_year_missing'));
        }

        if (!preg_match('/^(\d{4})(?:\D+(\d{4}))?/', $academicYear, $matches)) {
            return back()->with('error', __('settings.errors.academic_year_invalid'));
        }

        $startYear = $matches[1];
        $endYear = $matches[2] ?? ($startYear + 1);
        $startDate = "{$startYear}-09-01";
        $endDate = "{$endYear}-08-31";

        EmploiDuTemps::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->delete();

        return back()->with('success', __('settings.messages.academic_year_reset', ['year' => $academicYear]));
    }

    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $rules = [];
        if ($request->filled('name')) {
            $rules['name'] = 'required|string|max:255';
        }
        if ($request->filled('email')) {
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
        }
        if ($request->filled('current_password') || $request->filled('new_password')) {
            $rules['current_password'] = 'required';
            $rules['new_password'] = 'required|min:8|confirmed';
        }
        if ($request->hasFile('profile_photo')) {
            $rules['profile_photo'] = 'nullable|image|max:2048';
        }

        $request->validate($rules);

        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => __('settings.errors.current_password_incorrect')]);
            }

            $user->password = $request->new_password;
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $user->profile_photo = $request->file('profile_photo')->store('profiles', 'public');
        }

        $user->save();

        $message = $request->filled('current_password')
            ? __('settings.messages.password_updated')
            : __('settings.messages.account_updated');

        return back()->with('success', $message);
    }

    public function updateTimetable(Request $request)
    {
        $request->validate([
            'sessions_per_day' => 'required|integer|min:2|max:8',
            'working_days' => 'required|array',
            'enable_saturday' => 'boolean',
            'enable_conflict_detection' => 'boolean',
        ]);

        Setting::set('sessions_per_day', $request->sessions_per_day);
        Setting::set('working_days', json_encode($request->working_days));
        Setting::set('enable_saturday', $request->has('enable_saturday') ? '1' : '0');
        Setting::set('enable_conflict_detection', $request->has('enable_conflict_detection') ? '1' : '0');

        return back()->with('success', __('settings.messages.timetable_updated'));
    }

    public function updateReports(Request $request)
    {
        $request->validate([
            'director_name' => 'required|string|max:255',
            'default_pdf_format' => 'required|in:A4,Letter',
            'show_logo_in_pdf' => 'boolean',
            'automatic_signature' => 'nullable|image|max:1024',
        ]);

        Setting::set('director_name', $request->director_name);
        Setting::set('default_pdf_format', $request->default_pdf_format);
        Setting::set('show_logo_in_pdf', $request->has('show_logo_in_pdf') ? '1' : '0');

        if ($request->hasFile('automatic_signature')) {
            $path = $request->file('automatic_signature')->store('signatures', 'public');
            Setting::set('automatic_signature', $path);
        }

        return back()->with('success', __('settings.messages.reports_updated'));
    }
}