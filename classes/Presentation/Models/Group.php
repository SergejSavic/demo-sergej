<?php

namespace CleverReachIntegration\Presentation\Models;

/**
 * Class Group
 * @package CleverReachIntegration\Presentation\Models
 */
class Group
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $receiverInfo;
    /**
     * @var bool
     */
    private $locked;
    /**
     * @var bool
     */
    private $backup;

    /**
     * @param string $name
     * @param string $receiverInfo
     * @param bool $locked
     * @param bool $backup
     */
    public function __construct($name, $receiverInfo, $locked = false, $backup = true)
    {
        $this->name = $name;
        $this->receiverInfo = $receiverInfo;
        $this->locked = $locked;
        $this->backup = $backup;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getReceiverInfo()
    {
        return $this->receiverInfo;
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        return $this->locked;
    }

    /**
     * @return bool
     */
    public function isBackup()
    {
        return $this->backup;
    }

    /**
     * @return array
     */
    public function getArray()
    {
        return array("name" => $this->getName(), "receiver_info" => $this->getReceiverInfo(), "locked" => $this->isLocked(), "backup" => $this->isBackup());
    }

}