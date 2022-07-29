<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::all();
        $accountExist = Account::where('user_id', Auth::id())->get()->count();

        if ($accountExist == 0)
            return view('post', ['postCount' => $posts->count(), 'status' => "disconnected"]);

        $accountStatus = Account::where('user_id', Auth::id())->first();
        return view('post', ['postCount' => $posts->count(), 'status' => $accountStatus->status]);
    }
}
