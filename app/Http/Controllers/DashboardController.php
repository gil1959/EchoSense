<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EchoSenseService;

class DashboardController extends Controller
{
    protected $echoSense;

    public function __construct(EchoSenseService $echoSense)
    {
        $this->echoSense = $echoSense;
    }

    public function getSonificationData(Request $request)
    {
        // Default to Jakarta Monas if no location provided
        $lat = $request->query('lat', '-6.1751');
        $lon = $request->query('lon', '106.8272');
        $lang = $request->query('lang', 'id-ID'); // Get language parameter, default to ID

        $data = $this->echoSense->getEnvironmentData($lat, $lon, $lang);

        return response()->json($data);
    }
}
