<?php

namespace App\Http\Controllers;

use App\Services\ApiService;

use Illuminate\Support\Facades\Config;

class HomeController extends Controller
{
    public function index(ApiService $apiService, $dateFrom = '2023-09-23', $dateTo = '2023-09-26')
    {
        $apiService->setParams([
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);

        $apiService->getData();
    }
}
