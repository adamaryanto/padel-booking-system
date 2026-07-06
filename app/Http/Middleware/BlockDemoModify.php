<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockDemoModify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Block data-modifying requests (POST, PUT, PATCH, DELETE) in demo mode
        if (env('APP_DEMO_MODE', false) && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aksi dinonaktifkan dalam mode Demo Portfolio.'
                ], 403);
            }

            return back()->with('error', 'Aksi dinonaktifkan dalam mode Demo Portfolio.');
        }

        return $next($request);
    }
}
