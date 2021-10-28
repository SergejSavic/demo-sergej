<?php

namespace CleverReachIntegration\BusinessLogic\Validators;

/**
 * Class AuthenticationValidator
 * @package CleverReachIntegration\BusinessLogic\Validators
 */
class AuthenticationValidator
{
    /**
     * @param array $accessParameters
     * @return bool
     */
    public static function validate(array $accessParameters)
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