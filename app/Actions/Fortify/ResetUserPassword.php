<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use Laravel\Fortify\Rules\Password;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function reset($user, array $input)
    {
        Validator::make($input, [
            'password' => ['required',function ($attribute, $value, $fail) use($user) {
            if (Hash::check($value, $user->password)) {
                $fail('New Password cannot be the same with the old password');
            }
        }, 'string', (new Password)->requireUppercase()
        ->length(8)
        ->requireNumeric()
        ->requireSpecialCharacter(), 'confirmed',
    ]
        ])->validate();

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
