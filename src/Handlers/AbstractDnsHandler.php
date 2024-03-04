<?php

namespace BlueLibraries\Dns\Handlers;

use BlueLibraries\Dns\Records\RecordTypes;
use BlueLibraries\Dns\Regex;

abstract class AbstractDnsHandler implements DnsHandlerInterface
{

    protected int $retries = 2;

    /**
     * maximum number of seconds DNS interrogation retries are allowed
     * seconds timeout per retry
     * 2 retries = 3 tries (1 initial try + 2 more tries)
     * if timeout is 10 that means that script duration may go up to 10 x number of tries
     * eg: timeout = 5,  retries = 1 (+ 1 initial try) => 10 seconds
     *     timeout = 5,  retries = 2 (+ 1 initial try) => 15 seconds
     *     timeout = 10, retries = 3 (+ 1 initial try) => 40 seconds (this is huge)
     */
    protected int $timeout = 5;

    protected ?string $nameserver = '8.8.8.8';

    public abstract function getDnsData(string $host, int $typeId): array;

    /**
     * @return int
     */
    public function getRetries(): int
    {
        return $this->retries;
    }

    /**
     * @param int $retries
     * @return self
     */
    public function setRetries(int $retries): self
    {
        $this->retries = $retries;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     * @return self
     */
    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }

    public function setNameserver(?string $nameserver): self
    {
        if (filter_var($nameserver, FILTER_VALIDATE_IP) === false) {
            throw new DnsHandlerException(
                'Unable to set nameserver, as ' . json_encode($nameserver) . ' is an invalid IPV4 format!',
                DnsHandlerException::INVALID_NAMESERVER
            );
        }
        $this->nameserver = $nameserver;
        return $this;
    }

    /**
     * @param string $hostName
     * @throws DnsHandlerException
     */
    protected function validateHostName(string $hostName): void
    {
        if (empty($hostName)) {
            throw new DnsHandlerException(
                'Invalid hostname, it must not be empty!',
                DnsHandlerException::HOSTNAME_EMPTY
            );
        }

        $hostnameErrorInfo = 'Invalid hostname ' . json_encode($hostName);

        if (!filter_var($hostName, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
            throw new DnsHandlerException(
                $hostnameErrorInfo . ' format!',
                DnsHandlerException::HOSTNAME_FORMAT_INVALID
            );
        }
    }

    /**
     * @throws DnsHandlerException
     */
    protected function validateTypeIdValue(int $type, ?string $hostName = null)
    {
        if (!RecordTypes::isValidTypeId($type)) {
            throw new DnsHandlerException(
                'Invalid records typeId: ' . json_encode($type) .
                ' host ' . json_encode($hostName) . ' !',
                DnsHandlerException::TYPE_ID_INVALID
            );
        }
    }

    /**
     * @throws DnsHandlerException
     */
    protected function validateParams(string $hostName, int $typeId)
    {
        $this->validateHostName($hostName);
        $this->validateTypeIdValue($typeId, $hostName);
    }

}
