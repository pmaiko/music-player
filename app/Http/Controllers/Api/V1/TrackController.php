<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Track;
use Illuminate\Support\Facades\DB;


class TrackController extends Controller
{
    public function getTrack(Request $request) {
        $total_tracks = 0;
        if(empty($request->playlist_name))  {
            $playlist = Track::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get()->take($request->size);
            $total_tracks = Track::where('user_id', Auth::user()->id)->count();
        }
        else {
            $playlist = Track::where('user_id', Auth::user()->id)->where('playlist', 'LIKE', '%'.$request->playlist_name.'%')->orderBy('id', 'DESC')->get()->take($request->size);
            $total_tracks = Track::where('user_id', Auth::user()->id)->where('playlist', 'LIKE', '%'.$request->playlist_name.'%')->count();
        }


        $json = [
            'playlist' => $playlist,
            'total_tracks' => $total_tracks,

        ];

        return response()->json($json);
    }

    public function uploadTrack(Request $request) {
        $folder = 'music_upload';

        $request->validate([
            'file' => 'required|max:20000|mimes:mpga'
        ]);

        if($request->hasFile('file')) {
            $file = $request->file('file');
            $file->move(public_path() . '/'.$folder,$file->getClientOriginalName());
        }

//        Track::create([
//            'src' => '/'.$folder.'/'.$file->getClientOriginalName(),
//            'name_track' => $file->getClientOriginalName(),
//            'playlist' => "[]",
//            'user_id' => Auth::user()->id,
//        ]);

        DB::table('tracks')->insert([
            'src' => '/'.$folder.'/'.$file->getClientOriginalName(),
            'name_track' => $file->getClientOriginalName(),
            'playlist' => '[]',
            'user_id' => Auth::user()->id,
        ]);
    }
}
