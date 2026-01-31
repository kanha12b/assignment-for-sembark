<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\Str;



class ShortUrlController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'original_url' => 'required|url|max:2048',
    ]);

    $user = auth()->user();

    // Generate unique short code
    do {
        $shortCode = Str::random(6);
    } while (ShortUrl::where('short_code', $shortCode)->exists());

    ShortUrl::create([
        'original_url' => $request->original_url,
        'short_code' => $shortCode,
        'company_id' => $user->company_id,
        'created_by' => $user->id,
    ]);

    return redirect()->back()->with('success', 'Short URL generated successfully.');
}

}
