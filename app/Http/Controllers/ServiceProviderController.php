<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceProvider;

class ServiceProviderController extends Controller {

    public function getServiceProviderByServiceType(Request $request, $id) {
        $serviceProviders = ServiceProvider::where('service_type_id', $id)->get();
        $data = array(
            'status' => true,
            'message' => 'Providers get successfully.',
            'serviceProviders' => $serviceProviders
        );
        return response()->json($data);
    }

}
