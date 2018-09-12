<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    public function __construct() {
        $this->middleware('guest', [
            'only' => 'create',
        ]);
    }

    #登录页面
    public function create() {
        if (Auth::user() && Auth::viaRemember()) {
            redirect()->route('user.show', [Auth::user()]);
        }
        return view('login.create');
    }

    #登录
    public function store(Request $request) {
        $credentials = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        
        if (Auth::attempt($credentials, $request->has('remember'))) {
            if ($request->activated) {
                session()->flash('success', '欢迎回来！');
                return redirect()->intended(route('users.show', [Auth::user()]));
            }else {
                session()->flash('info', '请前往邮箱查看邮件激活账号');
                return redirect()->route('home');
            }
        }else {
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }

    #退出登录
    public function destroy() {
        Auth::logout();
        session()->flash('success', '退出登录成功！');
        return redirect('login');
    }
}
