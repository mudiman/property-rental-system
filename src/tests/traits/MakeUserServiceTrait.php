<?php

use Faker\Factory as Faker;
use App\Models\UserService;
use App\Repositories\UserServiceRepository;

trait MakeUserServiceTrait
{
    /**
     * Create fake instance of UserService and save it in database
     *
     * @param array $userServiceFields
     * @return UserService
     */
    public function makeUserService($userServiceFields = [])
    {
        /** @var UserServiceRepository $userServiceRepo */
        $userServiceRepo = App::make(UserServiceRepository::class);
        $theme = $this->fakeUserServiceData($userServiceFields);
        return $userServiceRepo->create($theme);
    }

    /**
     * Get fake instance of UserService
     *
     * @param array $userServiceFields
     * @return UserService
     */
    public function fakeUserService($userServiceFields = [])
    {
        return new UserService($this->fakeUserServiceData($userServiceFields));
    }

    /**
     * Get fake data of UserService
     *
     * @param array $postFields
     * @return array
     */
    public function fakeUserServiceData($userServiceFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'user_id' => $fake->randomDigitNotNull,
            'service_name' => $fake->word,
            'pricing' => $fake->word,
            'price' => $fake->randomDigitNotNull,
            'extra_charges' => $fake->randomDigitNotNull,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $userServiceFields);
    }
}
