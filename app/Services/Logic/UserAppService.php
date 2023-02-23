<?php

namespace App\Services\Logic;

use Illuminate\Support\Facades\DB;

use App\Core\BaseAppService;
use App\Contracts\Services\Logic\IUserAppService;
use App\Contracts\Repositories\IUserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserAppService extends BaseAppService implements IUserAppService
{
    /**
     * Hold the entities
     * @var \App\Entities\Entity
     */
    protected $entityRepository;

    /**
     * Default constructor to load and view data
     * @param IUserRepository $userRepository
     */
    public function __construct(IUserRepository $userRepository)
    {
        $this->entityRepository = $userRepository;
    }

    /**
     * Save a single resource
     * @param array $data
     * @return mixed
     */
    public function save(array $data)
    {
        if (isset($data['id']) && $data['id'] != 0) {
            return $this->update($data);
        } else {
            return $this->create($data);
        }
    }

    /**
     * Create a single resource
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);

        $transactionResult = DB::transaction(function () use ($user) {
            $user = $this->entityRepository->create($user->getAttributes());
            return $user;
        });

        return $transactionResult;
    }

    /**
     * Update a single resource
     * @param array $data
     * @return mixed
     */
    public function update(array $data)
    {

        $user = new User();
        $user->name = $data['name'];
        $user->password = bcrypt($data['password']);

        $transactionResult = DB::transaction(function () use ($user, $data) {
            $user = $this->entityRepository->update($data['id'], $user->getAttributes());
            return $user;
        });

        return $transactionResult;
    }

    /**
     * Delete a single resource
     * @param int $userId
     * @return mixed
     */
    public function delete(int $userId)
    {
        $user = $this->entityRepository->find($userId);

        if (!$user) {
            $this->setErrors(['error' => 'user_not_found']);
            return;
        }

        if ($this->entityRepository->delete($userId)) {
            return true;
        } else {
            $this->setErrors(['error' => 'can_delete_user']);
        }
    }

    /**
     * Login Request Service
     * @param array $data
     * @return mixed
     */
    public function login($data)
    {
        $user = User::where('email', $data['email'])->first();

        if (isset($user) && Hash::check($data['password'], $user->password)) {
            // Indicating user has logged In from temp pass after login
            $token = $user->createToken(env('APP_NAME'))->accessToken;

            $userData = $user;
            $userData['token'] = $token;
            return $userData;
        }

        return false;
    }

    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to re-login to get a new token
     *
     * @return mixed
     */
    public function logout()
    {
        $user = request()->user();

        $user->token()->revoke();

        return true;
    }

     /**
     * API Change password to user
     *
     * @param array $data
     * @return mixed
     */
    public function changePassword($data)
    {
        if (Hash::check($data['current_password'], request()->user()->password)) {

            $user_updated = request()->user()->update(['password' => Hash::make($data['password'])]);

            if ($user_updated) {
               return true;
            }
            return false;
        }

        return false;
    }
}
