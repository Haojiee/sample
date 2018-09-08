<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    #用户信息
    public function show(User $user) {
        return view('users.show', compact('user'));
    }

    #用户注册
    public function create() {
        return view('users.create');
    }

    #用户登录
    public function login() {
        return view('users.login');
    }

    #用户注册
    public function store(Request $request) {
        //字段验证
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'required|min:6|confirmed',
            'email' => 'required|email|unique:users|max:255',
        ]);

        //注册
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        //注册成功，跳转后提示信息
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }

}
