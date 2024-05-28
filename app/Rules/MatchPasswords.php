<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MatchPasswords implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Initialization, if needed
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Assuming the password confirmation field is named 'password_confirmation'
        return $value === request('password_confirmation');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The password and password confirmation do not match.';
    }
}
