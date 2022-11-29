<?php

namespace MamaOmida\Dns\Handlers\Types;

use MamaOmida\Dns\Handlers\AbstractDnsHandler;
use MamaOmida\Dns\Handlers\DnsHandlerException;

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
     * @return array
     * @throws DnsHandlerException
     */
    protected function getDnsRecord(string $hostName, int $type): array
    {
        try {
            $result = dns_get_record($hostName, $type);
        } catch (\Throwable $exception) {
            throw new DnsHandlerException(
                'Unable to get dns record, for hostname: ' . json_encode($hostName) .
                ' and type: ' . json_encode($type) . ' error message: ' . json_encode($exception->getMessage()),
                DnsHandlerException::UNABLE_TO_GET_RECORD
            );
        }
        if ($result === false) {
            throw new DnsHandlerException(
                'Unable to get dns record, for hostname: ' . json_encode($hostName) .
                ' and type: ' . json_encode($type) . ' invalid result',
                DnsHandlerException::UNABLE_TO_GET_RECORD
            );
        }
        return $result;
    }

}