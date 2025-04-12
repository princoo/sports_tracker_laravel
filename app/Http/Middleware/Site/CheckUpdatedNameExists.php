<?php

namespace App\Http\Middleware\Site;

use App\Models\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUpdatedNameExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // check if name already exists
        $site_name = $request->input('name');
        $site_id = $request->route('siteId'); // Assuming the site ID is passed in the route
        $site = Site::where('name', $site_name)->where('id', '!=', $site_id)->first();
        if ($site) {
            return response()->json([
                'status' => 'error',
                'message' => 'Name already exists.',
            ], 409);
        }
        return $next($request);
    }
}
