<?php

namespace CleverReachIntegration\BusinessLogic\Validators;

/**
 * Class RecipientValidator
 * @package CleverReachIntegration\BusinessLogic\Validators
 */
class RecipientValidator
{
    /**
     * @param $customer
     * @return array
     */
    public static function validateRecipientGlobalAttributes($customer)
    {
        $globalAttributes = array('firstname' => $customer['firstname'], 'lastname' => $customer['lastname'], 'shop' => $customer['shop']);

        if ($customer['company'] !== null && $customer['company'] !== '') {
            $globalAttributes['company'] = $customer['company'];
        }
        if ($customer['birthday'] !== null && $customer['birthday'] !== '' && $customer['birthday'] !== '0000-00-00') {
            $globalAttributes['birthday'] = $customer['birthday'];
        }
        if ($customer['newsletter'] === '1') {
            $globalAttributes['newsletter'] = 'true';
        } else {
            $globalAttributes['newsletter'] = 'false';
        }
        if ($customer['address1'] !== null && $customer['address1'] !== '') {
            $globalAttributes['street'] = $customer['address1'];
        }
        if ($customer['country'] !== null && $customer['country'] !== '') {
            $globalAttributes['country'] = $customer['country'];
        }
        if ($customer['city'] !== null && $customer['city'] !== '') {
            $globalAttributes['city'] = $customer['city'];
        }
        if ($customer['phone'] !== null && $customer['phone'] !== '') {
            $globalAttributes['phone'] = $customer['phone'];
        }
        if ($customer['postcode'] !== null && $customer['postcode'] !== '') {
            $globalAttributes['zip'] = $customer['postcode'];
        }

        return $globalAttributes;
    }
}