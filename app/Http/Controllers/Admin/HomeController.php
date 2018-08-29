<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Link;
use App\User;

class HomeController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.links');
    }

    public function links()
    {
        $links = Link::with('visits')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        if ($links->isEmpty())
            return 'No links.';
        return view('admin.links', compact('links'));
    }

    public function users()
    {
        $users = User::withTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        if ($users->isEmpty())
            return 'No links.';
        return view('admin.users', compact('users'));
    }

    public function user_disable(Request $request)
    {
        $user_id = $request['id'];
        $user = User::find($user_id);
        $user->delete();
        return redirect()->route('admin.users')->with('success','Post deleted successfully');
    }

    public function user_enable(Request $request)
    {
        $user_id = $request['id'];
        $user = User::withTrashed()->find($user_id);
        $user->restore();
        return redirect()->route('admin.users')->with('success','Post deleted successfully');
    }

    public function user_destroy(Request $request)
    {
        $user_id = $request['id'];
        $user = User::withTrashed()->find($user_id);
        $user->forcedelete();
        return redirect()->route('admin.users')->with('success','Post deleted successfully');
    }

    public function destroy(Request $request)
    {
        $link_id = $request['id'];
        $link = Link::find($link_id);
        $link->delete();
        return redirect()->route('admin.links')->with('success','Post deleted successfully');
    }

    public function link_edit($id)
    {
        $link = Link::find($id);
        return view('admin.link_edit', compact('link'));
    }

    public function link_update(Request $request)
    {
        $validatedData = $request->validate([
//            'url_short' => 'alpha|unique:links,url_short,except,id',
            'url_short' => 'nullable|alpha|unique:links,url_short',
        ]);

        $link = Link::find($request['link_id']);

        if (!$link)
            return redirect()->route('profile.home');

        if ($request['url_short']) {
            $link['url_short'] = $request['url_short'];
        }

        if ($request['link_time']) {
            $link['date_end'] = $link['date_start'] + $request['link_time'];
        } else $link['date_end'] = Null;
        $link->update();
        return redirect()->route('admin.links');

    }
}
