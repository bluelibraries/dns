<?php

namespace MamaOmida\Dns\Handlers;

interface DnsHandlerInterface
{
    public function getDnsData(string $hostName, int $type): array;
}