<?php

namespace CleverReachIntegration\Presentation\Models;

/**
 * Class Recipient
 * @package CleverReachIntegration\Presentation\Models
 */
class Recipient
{
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $registered;
    /**
     * @var string
     */
    private $activated;
    /**
     * @var string
     */
    private $source;
    /**
     * @var array
     */
    private $attributes;
    /**
     * @var array
     */
    private $globalAttributes;
    /**
     * @var array
     */
    private $tags;
    /**
     * @var array
     */
    private $orders;

    /**
     * @param string $email
     * @param string $registered
     * @param string $activated
     * @param string $source
     * @param array $attributes
     * @param array $globalAttributes
     * @param array $tags
     * @param array $orders
     */
    public function __construct($email, $registered, $activated, $source, $attributes, $globalAttributes, $tags, $orders)
    {
        $this->email = $email;
        $this->registered = $registered;
        $this->activated = $activated;
        $this->source = $source;
        $this->attributes = $attributes;
        $this->globalAttributes = $globalAttributes;
        $this->tags = $tags;
        $this->orders = $orders;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getRegistered()
    {
        return $this->registered;
    }


    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return array
     */
    public function getGlobalAttributes()
    {
        return $this->globalAttributes;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return array
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @return string
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * @return array
     */
    private function getOrdersArray()
    {
        $ordersArray = array();
        foreach ($this->getOrders() as $order) {
            $ordersArray[] = $order->getArray();
        }

        return $ordersArray;
    }

    /**
     * @return array
     */
    public function getArray()
    {
        return array("email" => $this->getEmail(), "registered" => $this->getRegistered(), "activated" => $this->getActivated(), "source" => $this->getSource(), "attributes" => (object)array(),
            "global_attributes" => $this->getGlobalAttributes(), "tags" => $this->getTags(), "orders" => $this->getOrdersArray());
    }

}

