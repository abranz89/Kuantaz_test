<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class KuantazService
{
    protected $baseUrl;

    public function __construct(){

        $this->baseUrl =config('services.kuantaz.api_url');
    }

    public function getBeneficios(): Collection
    {
        $response = Http::get($this->baseUrl.'399b4ce1-5f6e-4983-a9e8-e3fa39e1ea71');
        return collect($response['data']);
    }

    public function getFiltros(): Collection
    {
        $response = Http::get($this->baseUrl.'06b8dd68-7d6d-4857-85ff-b58e204acbf4');
        return collect($response['data']);
    }

    public function getFichas(): Collection
    {
        $response = Http::get($this->baseUrl.'c7a4777f-e383-4122-8a89-70f29a6830c0');
        return collect($response['data']);
    }

}
