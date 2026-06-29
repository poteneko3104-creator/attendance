<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;

class MyApiService
{
    public function getData()
    {
        $response = Http::get('https://api.example.com/data');
        return $response->successful() ? $response->json() : [];
    }
}