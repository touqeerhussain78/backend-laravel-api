<?php

namespace App\Http\Controllers\Api;

use App\Core\BaseController;
use App\Http\Controllers\Controller;
use App\Services\Logic\GuardianService;
use App\Services\Logic\NewYorkTimesService;
use Illuminate\Http\Request;

class ExternalNewsController extends BaseController
{
    /**
     * hold the service of new york
     * @var NewYorkTimesService $newYorkTimesService
     */
    protected $newYorkTimesService;

    /**
     * hold the service of guardian
     * @var GuardianService $guardianService
     */
    protected $guardianService;

    /**
     * Default Constructor to load and view data
     * @param NewYorkTimesService $newYorkTimesService
     * @param GuardianService $guardianService
    */
    public function __construct(NewYorkTimesService $newYorkTimesService, GuardianService $guardianService)
    {
        $this->newYorkTimesService = $newYorkTimesService;
        $this->guardianService = $guardianService;
    }

    /**
     * Get articles of news sources
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function getArticles(Request $request)
    {
        $data = null;
        if ($request->type == 'newYork') {
            $data = $this->newYorkTimesService->getAllArticles();
        } else {
            $data =  $this->guardianService->getAllArticles();
        }
        return $this->successResponse(__('responseMessages.fetchArticlesSuccessfully'), json_decode($data)->response);
    }
}
