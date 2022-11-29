<?php

namespace MamaOmida\Dns\Handlers\Types;

use MamaOmida\Dns\Handlers\AbstractDnsHandler;
use MamaOmida\Dns\Handlers\DnsHandlerException;
use Throwable;

class DnsGetRecord extends AbstractDnsHandler
{
    /**
     * @throws DnsHandlerException
     */
    public function getDnsData(string $hostName, int $type): array
    {
        $this->validateParams($hostName, $type);

        return $this->getDnsRawResult($hostName, $type);
    }

    public function getDnsRawResult(string $hostName, int $type): array
    {
        $startProcess = time();
        for ($i = 0; $i <= $this->retries; $i++) {
            if (
                ($result = $this->getDnsRecord($hostName, $type)) !== []
                || ((time() - $startProcess) >= $this->timeout)
            ) {
                return is_array($result) ? $result : [];
            }
        }
        return [];
    }

    /**
     * @param string $hostName
     * @param int $type
     * @return array|bool
     */
    protected function getDnsRecord(string $hostName, int $type)
    {
        try {
            return dns_get_record($hostName, $type);
        } catch (Throwable $exception) {
            return false;
        }
    }

}