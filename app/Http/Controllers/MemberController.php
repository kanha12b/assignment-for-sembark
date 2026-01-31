<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;

class MemberController extends Controller
{
       public function index()
    {
        $user = auth()->user();

        // Only URLs created by this member
        // $userUrls = ShortUrl::where('created_by', $user->id)
        //                     ->latest()
        //                     ->get();

        $userUrls = ShortUrl::where('created_by', $user->id)
                    ->latest()
                    ->paginate(10); // 10 per page

        return view('member.dashboard', compact('userUrls'));
    }
}
