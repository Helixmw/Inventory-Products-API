<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use app\Models\User;

class CheckUserRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
        $user = Auth::user();
        $check = User::where('email', $user->email)->first();
        if($check->role == 0 || $check->role == null){
            return response()->json(["Unauthorized" => "User is unauthorized."], 403);
        }else{
            return $next($request);
        }
        }catch(\Exception $e){
            return response()->json(["Server" => "Error " . $e->getMessage()], 403);
        }
    }
}
