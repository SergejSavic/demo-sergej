<?php

namespace App\BusinessLogic\Validators;

class AuthenticationValidator
{
    public static function validate(array $accessParameters): bool
    {
        if($accessParameters['access_token']
        && $accessParameters['refresh_token']
        && $accessParameters['token_type']
        && $accessParameters['scope']) {

            return true;
        }

        return false;
    }
}