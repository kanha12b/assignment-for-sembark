<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ShortUrl;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminController extends Controller
{

    public function index()
    {
        $admin = auth()->user();

        $companyUrls = ShortUrl::where('company_id', $admin->company_id)
            ->latest()
            ->paginate(5, ['*'], 'urls_page'); // note the custom page name

        // 2️⃣ Company Users (Paginate 10 per page)
        $companyUsers = User::where('company_id', $admin->company_id)
            ->whereIn('role', ['ADMIN', 'MEMBER'])
            ->withCount('shortUrls')
            ->withSum('shortUrls as total_hits', 'hits')
            ->paginate(5, ['*'], 'users_page'); // custom page name

        return view('admin.dashboard', compact('companyUrls', 'companyUsers'));
    }

    public function inviteUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:ADMIN,MEMBER',
        ]);

        $admin = auth()->user();

        // 🔐 Safety check (Admins only inside their company)
        if ($admin->role !== 'ADMIN') {
            abort(403);
        }

        $password = Str::random(8);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => $request->role,
            'company_id' => $admin->company_id,
            'created_by' => $admin->id,
            'status' => 'active',
        ]);

        // 📧 Send invite email (optional but recommended)

        try {
            //    Mail::to($user->email)->send(new \App\Mail\UserInviteMail($user, $password));
            Mail::to($user->email)->send(new \App\Mail\AdminInviteMail($user, $password));
            // maybe flash success message
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Mail sending failed: ' . $e->getMessage());
        }

        // return redirect()->back()->with('success', 'User invited successfully.');
        return redirect()->back()->with([
            'success' => 'Admin or User created! Invitation email sent.',
            'password' => $password,
            'email' => $user->email,
        ]);
    }

}
