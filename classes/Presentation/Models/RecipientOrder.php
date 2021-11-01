<?php

namespace CleverReachIntegration\Presentation\Models;

/**
 * Class RecipientOrder
 * @package CleverReachIntegration\Presentation\Models
 */
class RecipientOrder
{
    /**
     * @var string
     */
    private $orderId;
    /**
     * @var string
     */
    private $productId;
    /**
     * @var string
     */
    private $productName;
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
    private $mailingId;

    /**
     * @param string $orderId
     * @param string $productId
     * @param string $productName
     * @param float $price
     * @param string $currency
     * @param int $amount
     * @param string $mailingId
     */
    public function __construct($orderId, $productId, $productName, $price, $currency, $amount, $mailingId)
    {
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->productName = $productName;
        $this->price = $price;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->mailingId = $mailingId;
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
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
        return $this->mailingId;
    }

    /**
     * @return array
     */
    public function getArray()
    {
        return array("order_id" => $this->getOrderId(), "product_id" => $this->getProductId(), "product" => $this->getProductName(),
            "price" => $this->getPrice(), "currency" => $this->getCurrency(), "amount" => $this->getAmount(), "mailing_id" => $this->getMailingId());
    }

}