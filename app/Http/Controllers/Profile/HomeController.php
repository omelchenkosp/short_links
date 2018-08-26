<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Link;


class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $links = Link::where('user_id', $user['id'])->get();
        return view('profile.home', compact('user', 'links'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $user->name = $request['name'];
        $user->password = bcrypt($request['password']);
        $user->update();
        return redirect()->route('profile.home');
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        $link_id = $request['id'];
        $link = Link::find($link_id);
        if ($link['user_id'] == $user->id ) {
            $link->delete();
        }
        return redirect()->route('profile.home')->with('success','Post deleted successfully');
    }
}
