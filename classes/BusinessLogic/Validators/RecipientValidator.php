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
     * @param array $orders
     * @return array
     */
    public static function validateRecipientGlobalAttributes($customer, $orders)
    {
        $globalAttributes = array('firstname' => $customer['firstname'], 'lastname' => $customer['lastname'], 'shop' => $customer['name']);

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

        if (count($orders) > 0) {

            if ($orders[0]['address1'] !== null && $orders[0]['address1'] !== '') {
                $globalAttributes['street'] = $orders[0]['address1'];
            }

            if ($orders[0]['city'] !== null && $orders[0]['city'] !== '') {
                $globalAttributes['city'] = $orders[0]['city'];
            }

            if ($orders[0]['phone'] !== null && $orders[0]['phone'] !== '') {
                $globalAttributes['phone'] = $orders[0]['phone'];
            }

            if ($orders[0]['postcode'] !== null && $orders[0]['postcode'] !== '') {
                $globalAttributes['zip'] = $orders[0]['postcode'];
            }
        }

        return $globalAttributes;
    }
}