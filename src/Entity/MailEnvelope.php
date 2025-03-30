<?php

namespace WPToolkit\Entity;

class MailEnvelope
{
    private array $fromList = [];
    private array $replyList = [];
    private array $carbonCopyList = [];
    private array $blindCarbonCopyList = [];

    public function setFrom(string $email, ?string $name = null): void
    {
        $this->fromList = [$email, $name];
    }

    public function setReply(string $email, ?string $name = null): void
    {
        $this->replyList = [$email, $name];
    }

    public function setCarbonCopy(string $email, ?string $name = null): void
    {
        $this->carbonCopyList[$email] = $name;
    }

    public function setBlindCarbonCopy(string $email, ?string $name = null): void
    {
        $this->blindCarbonCopyList[$email] = $name;
    }

    public function getFrom(): array
    {
        return $this->fromList;
    }

    public function getReply(): array
    {
        return $this->replyList;
    }

    public function getCarbonCopies(): array
    {
        return $this->carbonCopyList;
    }

    public function getBlindCarbonCopies(): array
    {
        return $this->blindCarbonCopyList;
    }
}
