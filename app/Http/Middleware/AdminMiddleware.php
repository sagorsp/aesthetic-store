<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request; use Symfony\Component\HttpFoundation\Response;
class AdminMiddleware { public function handle(Request $request, Closure $next, string ...$roles):Response { $u=auth()->user(); if(!$u || !$u->is_admin) return redirect()->route('admin.login')->with('error','Please sign in as admin.'); if($roles && !$u->hasAdminRole($roles)) abort(403,'You do not have permission for this section.'); return $next($request); } }
