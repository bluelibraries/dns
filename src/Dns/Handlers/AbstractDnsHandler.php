<?php

namespace MamaOmida\Dns\Handlers;

abstract class AbstractDnsHandler implements DnsHandlerInterface
{

    protected int $retries = 10;

    /**
     * maximum number of seconds DNS interrogation retries are allowed
     */
    protected int $timeout = 5; //seconds

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

    /**
     * @param string $hostName
     * @throws DnsHandlerException
     */
    private function validateHostName(string $hostName): void
    {
        if (empty($hostName)) {
            throw new DnsHandlerException(
                'Invalid hostname, it must not be empty!',
                DnsHandlerException::HOSTNAME_EMPTY
            );
        }

        if (strlen($hostName) < 3) {
            throw new DnsHandlerException(
                'Invalid hostname ' . json_encode($hostName) . ' length. It must be 3 or more!',
                DnsHandlerException::HOSTNAME_LENGTH_TOO_SMALL
            );
        }

        if (!preg_match("/^(([a-z\d_\-]+\.)*)?([a-z\d\-]+)\.([a-z\d]+)$/i", $hostName)) {
            throw new DnsHandlerException(
                'Invalid hostname ' . json_encode($hostName) . ' format! (characters "A-Za-z0-9.-" allowed)',
                DnsHandlerException::HOSTNAME_FORMAT_INVALID
            );
        }

        if (!preg_match("/^.{3,253}$/", $hostName)) {
            throw new DnsHandlerException(
                'Invalid hostname ' . json_encode($hostName) . ' length! (min 3, max 253 characters allowed)',
                DnsHandlerException::HOSTNAME_LENGTH_INVALID
            );
        }

        if (!preg_match("/^[^.]{1,63}(\.[^.]{1,63})*$/", $hostName)) {
            throw new DnsHandlerException(
                'Invalid hostname ' . json_encode($hostName) . ' TLD (extension) length! (min 1, max 63 characters allowed)',
                DnsHandlerException::HOSTNAME_TLD_LENGTH_INVALID
            );
        }
    }

    /**
     * @throws DnsHandlerException
     */
    protected function validateParams(string $hostName, int $type)
    {
        $this->validateHostName($hostName);
    }

    /**
     * @throws DnsHandlerException
     */
    public function getValidatedDnsData(string $hostName, int $type): array
    {
        $this->validateParams($hostName, $type);
        return $this->getDnsData($hostName, $type);
    }

    protected function lineToArray(string $line, ?int $limit = null): array
    {
        return explode(
            ' ',
            preg_replace('/\s+/', ' ', $line),
            $limit
        );
    }

    public abstract function getDnsData(string $hostName, int $type): array;

}