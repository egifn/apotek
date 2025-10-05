<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    public function handle(Request $request, Closure $next, ...$types)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        if (in_array($user->type, $types)) {
            return $next($request);
        }

        // Jika back() tidak bekerja, fallback ke halaman akses ditolak
        try {
            return back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        } catch (\Exception $e) {
            return redirect()->route('akses_ditolak')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
}
}