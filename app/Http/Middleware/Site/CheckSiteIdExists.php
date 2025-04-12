<?php

namespace App\Http\Middleware\Site;

use App\Models\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSiteIdExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $siteId = $request->route('site_id') ?? $request->input('site_id');
        if (!$siteId) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID for site is required.',
            ], 400);
        }

        if (!Site::where('id', $siteId)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Site with this ID does not exist.',
            ], 404);
        }

        return $next($request);
    }
}
