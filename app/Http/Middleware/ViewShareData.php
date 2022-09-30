<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use App\Models\ServiceType;

class ViewShareData {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $serviceTypes = ServiceType::with(['serviceProvidersData'])->orderBy('service_name')->get();
        $viewShareData = [
            'serviceTypes' => $serviceTypes
        ];
        View::share("viewShareData", $viewShareData);
        return $next($request);
    }

}
