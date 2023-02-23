<?php

namespace App\Services\Logic;

use App\Traits\ConsumesExternalServices;

class NewYorkTimesService
{
    use ConsumesExternalServices;

    /**
     * Hold the base Url
     * @var string $baseUri
     */
    protected $baseUri = '';

    public function __construct()
    {
        $this->baseUri = config('app.new_york_base_url');
    }

    /**
     * Get all articles
     * @return mixed
    */
    public function getAllArticles()
    {
        return $this->makeRequest('GET' ,$this->baseUri ,['api-key' => config('app.new_york_api_key')]);
    }
}
