<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller {
    public function show(){ if(auth()->check()&&auth()->user()->is_admin) return redirect()->route('admin.dashboard'); return view('admin.login'); }
    public function login(Request $r){ $d=$r->validate(['email'=>'required|email','password'=>'required|string']); if(Auth::attempt($d,$r->boolean('remember')) && auth()->user()->is_admin){$r->session()->regenerate();return redirect()->intended(route('admin.dashboard'));} Auth::logout(); return back()->withInput($r->only('email'))->with('error','Invalid admin credentials.'); }
    public function logout(Request $r){ Auth::logout();$r->session()->invalidate();$r->session()->regenerateToken();return redirect()->route('admin.login'); }
}
