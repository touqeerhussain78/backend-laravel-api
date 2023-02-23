<?php

namespace App\Http\Controllers\Api;

// core libaray
use App\Contracts\Services\Logic\IUserAppService;
use App\Core\BaseController;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignUpRequest;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class AuthController extends BaseController
{
    /**
     * Hold the User App Service
     * @var IUserAppService $userAppService
     */
    protected $userApp;

    /**
     * Default constructor to load and view data
     * @param IUserAppService $userAppService
     */

    public function __construct(IUserAppService $userAppService)
    {
        $this->userApp = $userAppService;
        parent::__construct($userAppService);
    }

    /**
     * API Login, on success return JWT Auth token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $data = $this->userApp->login($request->all());

        if ($data) {
            return $this->successResponse(__('responseMessages.loggedIn'), $data);
        }
        return $this->errorResponse(__('responseMessages.unknowError'), 400);
    }

    /**
     * Sign Up
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(SignUpRequest $request)
    {
        $user = User::where(['email' => $request["email"]])->exists();

        if ($user) {
            return $this->authResponse(400, "Your email {$request['email']} already exist");
        }

        $this->userApp->create($request->input());

        return $this->jsonResponse(['message' => 'Register user successfully']);
    }

    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to re-login to get a new token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $data = $this->userApp->logout();
        if ($data) {
            return $this->successResponse(true, __('responseMessages.loggedOut'));
        }

        return $this->errorResponse(__('responseMessages.unknowErrorOccur'), 400);
    }

    /**
     * API Change password to user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $data = $this->userApp->changePassword($request->all());
        if ($data) {
            return $this->sendResponse($data, __('responseMessages.passwordUpdated'));
        }

        return $this->sendError(__('responseMessages.errorUpdatingPassword'), false);

    }
}
