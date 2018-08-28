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

    public function link_edit($id)
    {
        $user = Auth::user();
        $link = Link::where('user_id', $user['id'])->find($id);
        if (!$link)
            return redirect()->route('profile.home');
        return view('profile.link_edit', compact('link'));
    }

    public function link_update(Request $request)
    {

        $validatedData = $request->validate([
            'url_short' => 'alpha|unique:links,url_short,except,id',
        ]);

        $user = Auth::user();
        $link = Link::where('user_id', $user['id'])->find($request['id']);
        if (!$link)
            return redirect()->route('profile.home');
        if ($request['url_short']) {
            $link['url_short'] = $request['url_short'];
        }
        if ($request['link_time']) {
            $link['date_end'] = $link['date_start'] + $request['link_time'];
        } else $link['date_end'] = Null;
        $link->update();

        return redirect()->route('profile.home');
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
