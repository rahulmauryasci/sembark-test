<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\{User, ShortUrl};

class UserController extends Controller
{
    public function index()
    {
        $per_page = 2;
        $users = User::when(!auth()->user()->isSuperUser(), function ($query) {
            $query->where('company_id', auth()->user()->company_id);
        })
        ->where('id', '!=', auth()->user()->id)
        ->paginate($per_page, ['*'], 'users_listing');

        $shortUrls = ShortUrl::when(!auth()->user()->isSuperUser(), function ($query) {
            $query->where('company_id', auth()->user()->company_id);
        })->when(auth()->user()->isMember(), function ($query) {
            $query->where('user_id', auth()->user()->id);
        })
        ->paginate($per_page, ['*'], 'url_listing');

        $data = compact('users', 'shortUrls');
        return view('dashboard', $data);
    }

    // View Invite Member Methods
    public function invite()
    {
        return view('users.add');
    }

    // Store Invited Member
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $role = auth()->user()->isSuperUser() ? 'admin' : 'member';

        if($role == 'admin') {
           $addCompany = Company::create(['name' => $request->name . "'s Company"]);
           $companyId = $addCompany->id;
        } else {
            $companyId = auth()->user()->company_id;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('user12345'),
            'company_id' => $companyId,
            'role' => $role,
        ]);

        // Here you can send an invitation email to the user with their login details

        return redirect()->route('dashboard')->with('success', 'User invited successfully.');
    }

    // View Shorten URL Form
    public function shortenUrl()
    {
        return view('users.short-urls-add');
    }

    // Store Shortened URL
    public function storeShortUrl(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url|max:2048',
        ]);

        $shortUrl = ShortUrl::create([
            'original_url' => $request->original_url,
            'short_code' => substr(md5(uniqid()), 0, 6),
            'user_id' => auth()->user()->id,
            'company_id' => auth()->user()->company_id,
        ]);

        // Here you can implement the logic to store the shortened URL
        return redirect()->route('dashboard')->with('success', 'URL shortened successfully.');
    }

    // Redirect Short URL
    public function redirectShortUrl($code)
    {
        $shortUrl = ShortUrl::where('short_code', $code)->first();

        if ($shortUrl) {
            $shortUrl->increment('clicks');
            return redirect($shortUrl->original_url);
        }

        return redirect()->route('dashboard')->with('error', 'Short URL not found.');
    }

}
