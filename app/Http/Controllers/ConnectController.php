<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use App\Models\Page;
use Laravel\Socialite\Facades\Socialite;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ConnectController extends Controller
{
    public function index()
    {
        $account = User::find(Auth::id())->account;
        $pages = User::find(Auth::id())->pages;

        return view('connect', ['account' => $account, 'pages' => $pages]);
    }
    
    public function handleDeconnect(){
        Account::where('user_id', Auth::id())->update([
            'status' => 'disconnected',
        ]);

        return redirect('connect');
    }

    public function handleFacebookRedirect()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->stateless()->user();
        // dd($user);

        Account::updateOrCreate([
            'fb_id' => $user->id,
        ], [
            'user_id' => Auth::id(),
            'name' => $user->name,
            'email' => $user->email,
            'status' => 'connected',
            'access_token' => $user->token,
        ]);

        $response = Http::get("https://graph.facebook.com/v14.0/me/accounts?access_token=$user->token");
        $pages = $response->json("data");

        foreach ($pages as $page) {
            Page::updateOrCreate([
                'user_id' => Auth::id(),
                'page_id' => $page["id"],
            ], [
                'access_token' => $page["access_token"],
                'name' => $page["name"],
                'category' => $page["category"],
                'tasks' => implode(",", $page["tasks"]),
            ]);
        }

        return redirect('connect');
    }
}
