<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(){
        return view('register', [
            "isReverse" => true,
            "title" => 'Bergabunglah dengan Kami',
            "subtitle" => 'Daftar sekarang untuk mulai dengan mudah',
            "sideTitle" => 'Mulai Perjalanan Investasi Anda Hari Ini',
            "sideSubTitle" => 'Bergabunglah bersama ribuan pengguna yang telah menemukan tanah strategis melalui platform kami.'
        ]);
    }

    public function registerPost(Request $request){
        $request->validate([
        'fullname' => 'required',
        'email' => 'required|email|unique:users,email',
        'username' => 'required|alpha_num|unique:users,username',
        'telp_number' => 'required',
        'password' => 'required|min:6',
        'profile_image' => 'nullable|image|mimes:jpg,png,jpeg'
        ], [
        'email.unique' => 'Email sudah digunakan!',
        'username.unique' => 'Username sudah digunakan!',
        'username.alpha_num' => 'Username hanya boleh huruf dan angka tanpa spasi!',
        'password.required' => 'Password wajib diisi!',
        'password.min' => 'Password minimal 6 karakter!',
        ]);

        $fullname = $request->input("fullname");
        $email = $request->input("email");
        $username = $request->input("username");
        $password = $request->input("password");
        $call = $request->input("telp_number");

        if($request->hasFile("profile_image")){
            $file = $request->file("profile_image");
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profile'), $filename);
        } else{
            $filename = 'default.png';     
        }

        User::create([
            "fullname" => $fullname,
            "email" => $email,
            "username" => $username,
            "telp_number" => $call,
            "password" => bcrypt($password),
            "profile" => $filename,
            "role" => "users"
        ]);

        return redirect('/login');
    }

    public function login(){
        return view('login', [
            "isReverse" => false,
            "title" => 'Welcome Back',
            "subtitle" => 'Silakan masuk untuk mulai mencari atau menjual tanah dengan mudah dan aman.',
            "sideTitle" => 'Akses Dashboard Properti Anda',
            "sideSubTitle" => 'Kelola iklan, pantau calon pembeli, dan temukan peluang investasi terbaik.'
        ]);
    }

    public function loginPost(Request $request){
        $username = $request->input("username");
        $password = $request->input("password");

        $isSuccessLogin = Auth::attempt([
            'username' => $username, 
            'password' => $password
        ]);

        if($isSuccessLogin){
            //  dd("LOGIN BERHASIL", Auth::user());
            $user = Auth::user();
            switch($user->role){
                case "notary":
                    return redirect("/notary/home");
                case "agent":
                    return redirect("/agent/appointment");
                case "users":
                    return redirect("/users/property");
            }
        } else {
            // dd("LOGIN GAGAL");
            return redirect("/login")
                ->with("loginStatus", "Username atau password salah");
        }
    }

    
    public function logout()
    {
        Auth::logout();

        return redirect("/login")
            ->with("loginStatus", "Logout berhasil");
    }

}
