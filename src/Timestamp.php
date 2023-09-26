<?php

class Timestamp
{
    protected int $timestamp;

    public function __construct(int $timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function getDateTime(): string
    {
        return (new \DateTime())->setTimestamp($this->timestamp)->format('Y-m-d H:i:s');
    }
}