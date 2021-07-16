<?php

namespace App\Actions\Fortify;

use App\Models\Video;
use GuzzleHttp\Utils;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param mixed $user
     * @param array $input
     * @return void
     */
    public function update($user, array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ])->validateWithBag('updateProfileInformation');

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'description' => Arr::exists($input, 'description') ? $input['description'] : '',
                'tags' => Utils::jsonEncode($input['tags']),
            ])->save();
        }

        if (isset($input['intro_video']) && $input['intro_video']) {

            if ($user->videos->isNotEmpty()) {
                $user->videos->toQuery()->update(['is_profile' => 0]);
            }

            Video::create([
                'title' => $input['name'],
                'link' => $input['intro_video'],
                'user_id' => $user->id,
                'is_profile' => true
            ]);
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param mixed $user
     * @param array $input
     * @return void
     */
    protected function updateVerifiedUser($user, array $input)
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
