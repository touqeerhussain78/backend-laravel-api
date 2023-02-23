<?php

namespace App\Services\Logic;

use App\Traits\ConsumesExternalServices;

class GuardianService
{
    use ConsumesExternalServices;

    /**
     * Hold the base Url
     * @var string $baseUri
     */
    protected $baseUri = '';

    public function __construct()
    {
        $this->baseUri = config('app.guardian_base_url');
    }

    /**
     * Get all articles
     * @return mixed
    */
    public function getAllArticles()
    {
        return $this->makeRequest('GET' ,$this->baseUri ,['api-key' => config('app.guardian_api_key'),'show-fields' =>'starRating,headline,thumbnail,short-url']);
    }
}
