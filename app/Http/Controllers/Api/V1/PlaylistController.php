<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Playlist;
use App\Track;


class PlaylistController extends Controller
{
    public function getPlaylist() {
        $playlist = Track::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->select('playlist')->get();

//        $strArr = null;
//        foreach ($playlist as $item) {
//            $strArr = $strArr . $item->playlist;
//        }
//
//        $arr = explode(',', $strArr, -1);
//        $arr = array_unique($arr);
//        return response()->json($arr);
        $newArray = [];
        $arr = [];
        for ($i = 0; $i < count($playlist); $i++) {
           $newArray = array_merge($newArray, $playlist[$i]->playlist);
        }

        for ($i = 0; $i < count($newArray); $i++) {
            array_push($arr, $newArray[$i]["value"]);
        }
        $arr = array_unique($arr);
        return response()->json($arr);
    }

    public function setPlaylist(Request $request) {
        $track_id = $request->id;
        $playlist_name = $request->playlist_name;
        $playlist = Track::where('user_id', Auth::user()->id)->where('id', $track_id)->select('playlist')->get()->first();
        $newArray = $playlist->playlist;
        array_push($newArray, ["value" => $playlist_name]);
        Track::where('user_id', Auth::user()->id)->where('id', $track_id)->update(array('playlist' => json_encode($newArray)));


//        Track::where('user_id', Auth::user()->id)->where('id', $track_id)->select('playlist')->update('{"value":"lox"}');
//        Track::where('user_id', Auth::user()->id)->where('id', $track_id)->update(array('playlist' => $playlist[0]->playlist . $playlist_name . ','));


//        $playlist = Track::where('user_id', Auth::user()->id)->where('id', $track_id)->select('playlist')->get();
//        if (!empty($playlist[0]->playlist)) {
//            Track::where('user_id', Auth::user()->id)->where('id', $track_id)->update(array('playlist' => $playlist[0]->playlist . $playlist_name . ','));
//        }
//        else {
//            Track::where('user_id', Auth::user()->id)->where('id', $track_id)->update(array('playlist' => $playlist_name . ','));
//        }
    }

    public function delTrackPlaylist(Request $request) {
        $track_id = $request->id;
        $playlist_name = $request->playlist_name;

//        $track_id = 1;
//        $playlist_name = 'my';
        $playlist = Track::where('user_id', Auth::user()->id)->where('id', $track_id)->select('playlist')->get()->first();

        $arr = [];
        for($i = 0; $i < count($playlist->playlist); $i++) {
            if ($playlist->playlist[$i]["value"] !== $playlist_name) {
                array_push($arr, $playlist->playlist[$i]);
            }
        }

        Track::where('user_id', Auth::user()->id)->where('id', $track_id)->update(array('playlist' => json_encode($arr)));
    }
}
