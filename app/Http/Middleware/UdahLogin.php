<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UdahLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $username = $request->session()->get('username');
        $password = $request->session()->get('password');

        $user = DB::table('user')
            ->where('username', $username)
            ->where('password', $password)
            ->get()->toArray();

        if (count($user) > 0) {
            //pass
        }else{
            return redirect('logout');
        }

        return $next($request);
    }
}
