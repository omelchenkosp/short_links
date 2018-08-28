<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Link;
use App\Visit;
use Carbon\Carbon;
use Auth;


class HomeController extends Controller
{
    public function index()
    {
        $links = Link::with('visits')
            ->where('user_id', NULL)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('site.home', compact('links'));
    }

    public function show($link_id)
    {
        $link = Link::with('visits')->find($link_id);
        return view('site.link', compact('link'));
    }

    public function add()
    {
        return view('site.add');
    }

    private function generateRandomString($length = 4) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'url_origin' => 'required|active_url',
        ]);
        $input = $request->except('_token');
        $link = new Link($input);

        $loop = 0;
        do {
            $url_short = $this->generateRandomString();
            $loop++;
            if ($loop > 50) {
                return redirect()->route('site.home');
            }
        } while (Link::where('url_short', $url_short)->get()->isNotEmpty());
        $link->url_short = $url_short;
        $link->date_start = Carbon::now()->timestamp;
        if($user = Auth::user()) {
            $link->user_id = $user['id'];
        }
        $link->save();

        session(['link'=>$url_short]);

        return redirect()->route('site.edit');
    }

    public function edit()
    {
        if (!session('link'))
            return redirect()->route('site.home');

        $link = Link::where('url_short', session('link'))->first();
        return view('site.edit', compact('link'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
//            'url_short' => 'alpha|unique:links,url_short,except,id',
            'url_short' => 'nullable|alpha|unique:links,url_short',
        ]);

        if (session('link')) {
            $link = Link::where('url_short', session('link'))->first();
            if ($request['url_short']) {
                $link['url_short'] = $request['url_short'];
                session(['link'=>$link['url_short']]);
            }
            if ($request['link_time']) {
                $link['date_end'] = $link['date_start'] + $request['link_time'];
            } else $link['date_end'] = Null;
                $link->update();
        }
        return redirect()->route('site.link', [$link['id']]);
    }

    public function visit($short_link)
    {
        $origin_link = Link::where('url_short', $short_link)->first();
        $current_timestamp = Carbon::now()->timestamp;

        if (!$origin_link || ($origin_link->date_end != NULL && $origin_link->date_end < $current_timestamp))
            return redirect()->route('site.home');

        $user_os = $this->getOS($_SERVER['HTTP_USER_AGENT']);
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $user_ip = $_SERVER['REMOTE_ADDR'];

        $visit = new Visit();
        $visit->os = $user_os;
        $visit->browser = $user_agent;
        $visit->link_id = $origin_link['id'];


        $access_key = env('LOCATION_API');

        // Initialize CURL:
        $ch = curl_init('http://api.ipstack.com/'.$user_ip.'?access_key='.$access_key.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Store the data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        $api_result = json_decode($json, true);

        // Output the "capital" object inside "location"
//        echo $api_result['location']['capital'];


        $visit->country = $api_result['country_name'] ? $api_result['country_name'] : "no data";
        $visit->lat = $api_result['latitude'] ? $api_result['latitude'] : 1;
        $visit->long = $api_result['longitude'] ? $api_result['longitude'] : 1;
        $visit->save();

        header( "Location: $origin_link->url_origin");
        die();
    }

    private function getOS($userAgent) {
        // Create list of operating systems with operating system name as array key
        $oses = array (
            'iPhone'            => '(iPhone)',
            'Windows 3.11'      => 'Win16',
            'Windows 95'        => '(Windows 95)|(Win95)|(Windows_95)',
            'Windows 98'        => '(Windows 98)|(Win98)',
            'Windows 2000'      => '(Windows NT 5.0)|(Windows 2000)',
            'Windows XP'        => '(Windows NT 5.1)|(Windows XP)',
            'Windows 2003'      => '(Windows NT 5.2)',
            'Windows Vista'     => '(Windows NT 6.0)|(Windows Vista)',
            'Windows 7'         => '(Windows NT 6.1)|(Windows 7)',
            'Windows NT 4.0'    => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
            'Windows ME'        => 'Windows ME',
            'Open BSD'          => 'OpenBSD',
            'Sun OS'            => 'SunOS',
            'Linux'             => '(Linux)|(X11)',
            'Safari'            => '(Safari)',
            'Mac OS'            => '(Mac_PowerPC)|(Macintosh)',
            'QNX'               => 'QNX',
            'BeOS'              => 'BeOS',
            'OS/2'              => 'OS/2',
            'Search Bot'        => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)'
        );

        // Loop through $oses array
        foreach($oses as $os => $preg_pattern) {
            // Use regular expressions to check operating system type
            if ( preg_match('@' . $preg_pattern . '@', $userAgent) ) {
                // Operating system was matched so return $oses key
                return $os;
            }
        }

        // Cannot find operating system so return Unknown

        return 'n/a';
    }
}
