<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Mail;

class UsersController extends Controller
{
    public function __construct() {
        #登录以外用户能访问的操作
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
        ]);
        
        #未登录才能访问的操作
        $this->middleware('guest', [
            'only' => ['create'],
        ]);
    }

    #用户列表
    public function index() {
        $users = User::paginate(5);
        return view('users.index', compact('users'));
    }

    #用户信息
    public function show(User $user) {
        return view('users.show', compact('user'));
    }

    #用户注册
    public function create() {
        return view('users.create');
    }

    #注册
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

        // Auth::login($user);
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '邮件以发送至您的邮箱，请查收~~');
        return redirect('/');
    }

    #编辑资料
    public function edit(User $user) {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    #修改资料
    public function update(User $user, Request $request) {
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|min:6|confirmed'
        ]);

        $this->authorize('update', $user);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = $request->password;
        }
        $user->update($data);
        session()->flash('success', '个人资料更新成功！');
        return redirect()->route('users.show', $user->id);
    }

    #删除用户
    public function destroy(User $user) {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '用户删除成功！');
        return redirect()->route('users.index');
    }

    #确认邮箱跳转用户信息页面
    public function confirmEmail($token) {
        $user = User::where('activation_token', $token)->firstOrfail();
        $user->actvated = true;
        $user->activation_token = null;
        Auth::login($user);
        session()->flash('success', '激活成功！欢迎加入Sample App~~');
        return redirect()->route('users.show', compact('user'));
    }

    #发送邮件
    private function sendEmailConfirmationTo($user) {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = '1208491124@qq.com';
        $name = 'Mr.li';
        $to = $user->email;
        $subject = '感谢您在 Sample 网站进行注册！';
        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

}
