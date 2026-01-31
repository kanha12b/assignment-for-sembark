<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use App\Models\ShortUrl;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SuperAdminController extends Controller
{
    public function index()
    {
    $companies = Company::withCount(['users','shortUrls']) // total URLs
        ->withSum('shortUrls as total_hits', 'hits') // sum of hits
        ->latest()
        ->paginate(5, ['*'], 'companies_page');

    $allUrls = ShortUrl::with('company') // eager load company name
        ->latest()
        ->paginate(5, ['*'], 'urls_page');

    return view('superadmin.dashboard', compact('companies', 'allUrls'));
    }

    public function invite(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
        ]);

        $company = Company::create([
            'name' => $request->company_name,
            'created_by' => auth()->id(),
        ]);

        $password = Str::random(8); 
        $admin = User::create([
            'name' => $request->company_name,
            'email' => $request->admin_email,
            'password' => Hash::make($password),
            'role' => 'ADMIN',
            'company_id' => $company->id,
            'created_by' => auth()->id(),
            'status' => 'active',
        ]);

        // Send invitation email to admin
        // Mail::to($admin->email)->send(new \App\Mail\AdminInviteMail($admin, $password));

        try {
            Mail::to($admin->email)->send(new \App\Mail\AdminInviteMail($admin, $password));
        } catch (\Exception $e) {
            \Log::error('Mail sending failed: ' . $e->getMessage());
        }

        // return redirect()->back()->with('success', 'Company and Admin created! Invitation email sent.');
        return redirect()->back()->with([
            'success' => 'Company and Admin created! Invitation email sent.',
            'admin_password' => $password,
            'admin_email' => $admin->email,
        ]);
    }
}
