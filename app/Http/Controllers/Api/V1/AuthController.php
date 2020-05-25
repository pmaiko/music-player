<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use App\UsersTable;

class AuthController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            "mail" => "required|string|email|max:255",
            "password" => "required|string|min:6"
        ]);
        //dump($request->input('mail'));
        if (Auth::once([
            'mail' => $request->input('mail'),
            'password' => $request->input('password'),
        ])) {
            return response()->json([
                "userInfo" => [
                    'name' => Auth::user()->name,
                    'surname' => Auth::user()->surname,
                    'mail' => Auth::user()->mail,
                    'image' => Auth::user()->image],
                "token" => Auth::user()->api_token,
            ]);
        } else {
            throw new AuthenticationException('Invalid credentials');
        }

    }

    public function register(Request $request) {
        $folder = 'ava';
        $request->validate([
            'name' => 'max:255',
            'surname' => 'max:255',
            'mail' => 'required|email|unique:users,mail',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ]);
        if($request->hasFile('image')) {
            $file = $request->file('image');
            $file->move(public_path() . '/'.$folder,$file->getClientOriginalName());
        }

        UsersTable::create([
            'name' => $request->all('name')['name'] ? $request->all('name')['name']: '',
            'surname' => $request->all('surname')['surname'] ? $request->all('surname')['surname']: '',
            'mail' => $request->all('mail')['mail'],
            'password' => bcrypt($request->all('password')['password']),
            'image' => $request->all('image')['image'] ? '/'.$folder.'/'.$file->getClientOriginalName() : '',
            'api_token' => Str::random(60),
        ]);
//        return response()->json();
    }
}
