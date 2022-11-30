<?php

namespace MamaOmida\Dns\Handlers;

interface DnsHandlerInterface
{
    public function getDnsData(string $hostName, int $type): array;

    public function getRetries(): int;

    public function setRetries(int $retries): self;

    public function getTimeout(): int;

    public function setTimeout(int $timeout): self;

    public function lineToArray(string $line, ?int $limit = 0): array;

    /**
     * @throws DnsHandlerException
     */
    public function setNameserver(?string $nameserver): self;

}