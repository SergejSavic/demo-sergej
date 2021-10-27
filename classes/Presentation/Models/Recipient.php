<?php

namespace CleverReachIntegration\Presentation\Models;

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
    private $deactivated;
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
    private $global_attributes;
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
     * @param string $deactivated
     * @param string $source
     * @param array $attributes
     * @param array $global_attributes
     * @param array $tags
     * @param array $orders
     */
    public function __construct($email, $registered, $activated, $deactivated, $source, $attributes, $global_attributes, $tags, $orders)
    {
        $this->email = $email;
        $this->registered = $registered;
        $this->activated = $activated;
        $this->deactivated = $deactivated;
        $this->source = $source;
        $this->attributes = $attributes;
        $this->global_attributes = $global_attributes;
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
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * @return string
     */
    public function getDeactivated()
    {
        return $this->deactivated;
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
        return $this->global_attributes;
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

}

