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
    public static function validate($accessParameters)
    {
        return $accessParameters['access_token'] && !empty($accessParameters['access_token'])
            && $accessParameters['refresh_token'] && !empty($accessParameters['refresh_token'])
            && $accessParameters['token_type'] && !empty($accessParameters['token_type'])
            && $accessParameters['scope'] && !empty($accessParameters['scope']);
    }
}