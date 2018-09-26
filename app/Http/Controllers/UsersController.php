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
        $statuses = $user->statuses()->orderBy('created_at', 'desc')->paginate(30);
        return view('users.show', compact('user', 'statuses'));
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
        $to = $user->email;
        $subject = '感谢您在 Sample 网站进行注册！';
        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    #用户关注人列表
    public function getFollowings(User $user) {
        $users = $user->followings()->paginate(30);
        $title = '关注的人';
        return view('users.show_follower', compact('users', 'title'));
    }

    #用户粉丝列表
    public function getFollowers(User $user) {
        $users = $user->followers()->paginate(30);
        $title = '粉丝';
        return view('users.show_follower', compact('users', 'title'));
    }
}
