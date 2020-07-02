<?php

declare(strict_types=1);

namespace Stryber\Logger\Wrappers;

use Gelf\Encoder\JsonEncoder;
use Gelf\Message;

final class GelfMessageWrapper extends Message
{
    private Message $originalMessage;

    public function __construct(Message $message)
    {
        $this->originalMessage = $message;
    }

    public function __toString(): string
    {
        return (new JsonEncoder())->encode($this);
    }

    public function getVersion()
    {
        return $this->originalMessage->getVersion();
    }

    public function setVersion($version)
    {
        return $this->originalMessage->setVersion($version);
    }

    public function getHost()
    {
        return $this->originalMessage->getHost();
    }

    public function setHost($host)
    {
        return $this->originalMessage->setHost($host);
    }

    public function getShortMessage()
    {
        return $this->originalMessage->getShortMessage();
    }

    public function setShortMessage($shortMessage)
    {
        return $this->originalMessage->setShortMessage($shortMessage);
    }

    public function getFullMessage()
    {
        return $this->originalMessage->getFullMessage();
    }

    public function setFullMessage($fullMessage)
    {
        return $this->originalMessage->setFullMessage($fullMessage);
    }

    public function getTimestamp()
    {
        return $this->originalMessage->getTimestamp();
    }

    public function setTimestamp($timestamp)
    {
        return $this->originalMessage->setTimestamp($timestamp);
    }

    public function getLevel()
    {
        return $this->originalMessage->getLevel();
    }

    public function getSyslogLevel()
    {
        return $this->originalMessage->getSyslogLevel();
    }

    public function setLevel($level)
    {
        return $this->originalMessage->setLevel($level);
    }

    public function getFacility()
    {
        return $this->originalMessage->getFacility();
    }

    public function setFacility($facility)
    {
        return $this->originalMessage->setFacility($facility);
    }

    public function getFile()
    {
        return $this->originalMessage->getFile();
    }

    public function setFile($file)
    {
        return $this->originalMessage->setFile($file);
    }

    public function getLine()
    {
        return $this->originalMessage->getLine();
    }

    public function setLine($line)
    {
        return $this->originalMessage->setLine($line);
    }

    public function getAdditional($key)
    {
        return $this->originalMessage->getAdditional($key);
    }

    public function hasAdditional($key)
    {
        return $this->originalMessage->hasAdditional($key);
    }

    public function setAdditional($key, $value)
    {
        return $this->originalMessage->setAdditional($key, $value);
    }

    public function getAllAdditionals()
    {
        return $this->originalMessage->getAllAdditionals();
    }

    public function toArray()
    {
        return $this->originalMessage->toArray();
    }
}
