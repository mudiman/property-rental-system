<?php

use Faker\Factory as Faker;
use App\Models\User;
use App\Repositories\UserRepository;

trait MakeUserTrait
{
    /**
     * Create fake instance of User and save it in database
     *
     * @param array $userFields
     * @return User
     */
    public function makeUser($userFields = [])
    {
        /** @var UserRepository $userRepo */
        $userRepo = App::make(UserRepository::class);
        $theme = $this->fakeUserData($userFields);
        return $userRepo->create($theme);
    }

    /**
     * Get fake instance of User
     *
     * @param array $userFields
     * @return User
     */
    public function fakeUser($userFields = [])
    {
        return new User($this->fakeUserData($userFields));
    }

    /**
     * Get fake data of User
     *
     * @param array $postFields
     * @return array
     */
    public function fakeUserData($userFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'first_name' => $fake->word,
            'last_name' => $fake->word,
            'username' => $fake->word,
            'role' => $fake->word,
            'about' => $fake->word,
            'about_smoor_score_service' => $fake->word,
            'gender' => $fake->word,
            'email' => $fake->word,
            'cordinate' => $fake->word,
            'postcode' => $fake->word,
            'address' => $fake->word,
            'location' => $fake->word,
            'profession' => $fake->word,
            'mobile' => $fake->word,
            'status' => $fake->word,
            'qualification' => $fake->word,
            'arla_qualified' => $fake->word,
            'profile_picture' => $fake->word,
            'email_verification_code' => $fake->word,
            'email_verification_code_expiry' => $fake->date('Y-m-d H:i:s'),
            'forgot_password_verification_code' => $fake->word,
            'forgot_password_verification_code_expiry' => $fake->date('Y-m-d H:i:s'),
            'sms_verification_code' => $fake->word,
            'sms_verification_code_expiry' => $fake->date('Y-m-d H:i:s'),
            'password' => $fake->word,
            'verified' => $fake->word,
            'admin_verified' => $fake->word,
            'token' => $fake->word,
            'remember_token' => $fake->word,
            'secret' => $fake->word,
            'transaction_reputation' => $fake->randomDigitNotNull,
            'transaction_reputation_max' => $fake->randomDigitNotNull,
            'transaction_reputation_min' => $fake->randomDigitNotNull,
            'conduct_reputation' => $fake->randomDigitNotNull,
            'conduct_reputation_max' => $fake->randomDigitNotNull,
            'conduct_reputation_min' => $fake->randomDigitNotNull,
            'service_reputation' => $fake->randomDigitNotNull,
            'service_reputation_max' => $fake->randomDigitNotNull,
            'service_reputation_min' => $fake->randomDigitNotNull,
            'date_of_birth' => $fake->word,
            'phone' => $fake->word,
            'school' => $fake->word,
            'available_to_move_on' => $fake->word,
            'commission_charge' => $fake->randomDigitNotNull,
            'area_covered' => $fake->word,
            'place_of_work' => $fake->word,
            'languages' => $fake->word,
            'current_residence_postcode' => $fake->word,
            'current_residence_property_type' => $fake->word,
            'current_residence_bedrooms' => $fake->randomDigitNotNull,
            'current_residence_bathrooms' => $fake->randomDigitNotNull,
            'current_contract_type' => $fake->word,
            'current_contract_start_date' => $fake->word,
            'current_contract_end_date' => $fake->word,
            'current_rent_per_month' => $fake->randomDigitNotNull,
            'employment_status' => $fake->word,
            'previous_employer' => $fake->word,
            'experience_years' => $fake->randomDigitNotNull,
            'previous_job_function' => $fake->word,
            'recent_search_query' => $fake->word,
            'extra_info' => $fake->text,
            'currency' => $fake->word,
            'push_notification_messages' => $fake->word,
            'push_notification_viewings' => $fake->word,
            'push_notification_offers' => $fake->word,
            'push_notification_other' => $fake->word,
            'email_notification_messages' => $fake->word,
            'email_notification_viewings' => $fake->word,
            'email_notification_offers' => $fake->word,
            'email_notification_other' => $fake->word,
            'text_notification_messages' => $fake->word,
            'text_notification_viewings' => $fake->word,
            'text_notification_offers' => $fake->word,
            'text_notification_other' => $fake->word,
            'account_reference' => $fake->word,
            'customer_reference' => $fake->word,
            'timezone' => $fake->word,
            'configuration' => $fake->text,
            'data' => $fake->text,
            'deleted_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_by' => $fake->word,
            'created_by' => $fake->word,
            'deleted_by' => $fake->word
        ], $userFields);
    }
}
