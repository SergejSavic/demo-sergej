<?php

namespace CleverReachIntegration\Presentation\Models;

class RecipientOrder
{
    /**
     * @var string
     */
    private $order_id;
    /**
     * @var string
     */
    private $product_id;
    /**
     * @var string
     */
    private $product_name;
    /**
     * @var float
     */
    private $price;
    /**
     * @var string
     */
    private $currency;
    /**
     * @var int
     */
    private $amount;
    /**
     * @var string
     */
    private $mailing_id;

    /**
     * @param string $order_id
     * @param string $product_id
     * @param string $product_name
     * @param float $price
     * @param string $currency
     * @param int $amount
     * @param string $mailing_id
     */
    public function __construct($order_id, $product_id, $product_name, $price, $currency, $amount, $mailing_id)
    {
        $this->order_id = $order_id;
        $this->product_id = $product_id;
        $this->product_name = $product_name;
        $this->price = $price;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->mailing_id = $mailing_id;
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * @return string
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * @return string
     */
    public function getProductName()
    {
        return $this->product_name;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getMailingId()
    {
        return $this->mailing_id;
    }

}