<?php

namespace CleverReachIntegration\BusinessLogic\Validators;

/**
 * Class CustomerValidator
 * @package CleverReachIntegration\BusinessLogic\Validators
 */
class CustomerValidator
{
    /**
     * @param $updatedCustomer
     * @param $customerBeforeUpdate
     * @return bool
     */
    public static function isSameData($updatedCustomer, $customerBeforeUpdate)
    {
        return ($updatedCustomer->firstname === $customerBeforeUpdate['firstname'])
            && ($updatedCustomer->lastname === $customerBeforeUpdate['lastname'])
            && ($updatedCustomer->birthday === $customerBeforeUpdate['birthday'])
            && ($updatedCustomer->newsletter === $customerBeforeUpdate['newsletter']);
    }
}