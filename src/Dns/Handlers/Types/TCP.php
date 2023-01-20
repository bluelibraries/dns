<?php

namespace MamaOmida\Dns\Handlers\Types;

use MamaOmida\Dns\Handlers\AbstractDnsHandler;
use MamaOmida\Dns\Handlers\DnsHandlerException;
use MamaOmida\Dns\Handlers\DnsHandlerTypes;
use MamaOmida\Dns\Handlers\Raw\RawDataException;
use MamaOmida\Dns\Handlers\Raw\RawDataRequest;
use MamaOmida\Dns\Handlers\Raw\RawDataResponse;

class TCP extends AbstractDnsHandler
{

    private int $port = 53;

    /**
     * @var mixed
     */
    private $socket = null;

    public function getType(): string
    {
        return DnsHandlerTypes::TCP;
    }

    function canUseIt(): bool
    {
        return function_exists('fsockopen');
    }

    private function getSocket()
    {
        $result = $this->socket
            ?? fsockopen(
                $this->nameserver,
                $this->port,
                $errorCode,
                $errorMessage,
                $this->timeout
            );

        if ($result === false) {
            return null;
        }

        return $this->socket = $result;
    }

    /**
     * @param int $port
     * @return self
     */
    public function setPort(int $port): self
    {
        $this->port = $port;
        return $this;
    }

    private function closeSocket()
    {
        if (is_null($this->socket)) {
            return;
        }
        fclose($this->socket);
        $this->socket = null;
    }

    /**
     * @throws DnsHandlerException
     * @throws RawDataException
     */
    private function query(string $hostName, int $typeId, int $retry = 0): ?RawDataResponse
    {
        $socket = $this->getSocket();

        if (is_null($socket)) {
            return null;
        }

        $request = new RawDataRequest($hostName, $typeId, $this->timeout);

        $header = $request->generateHeader();
        $headerLen = strlen($header);
        $headerBinLen = $request->getBinaryHeaderLength($headerLen);

        if (!fwrite($socket, $headerBinLen)) // write the socket
        {
            if ($retry < $this->retries) {
                return $this->query($hostName, $typeId, $retry + 1);
            }
            $this->closeSocket();
            throw new DnsHandlerException(
                "Failed to write question length to TCP socket",
                DnsHandlerException::ERR_UNABLE_TO_WRITE_QUESTION_LENGTH_TO_TCP_SOCKET
            );
        }

        if (!fwrite($socket, $header, $headerLen)) {
            if ($retry < $this->retries) {
                return $this->query($hostName, $typeId, $retry + 1);
            }
            $this->closeSocket();
            throw new DnsHandlerException(
                "Failed to write question to TCP socket",
                DnsHandlerException::ERR_UNABLE_TO_WRITE_QUESTION_TO_TCP_SOCKET
            );
        }

        if (!$returnLen = fread($socket, 2)) {
            if ($retry < $this->retries) {
                return $this->query($hostName, $typeId, $retry + 1);
            }
            $this->closeSocket();
            throw new DnsHandlerException(
                "Failed to read size from TCP socket",
                DnsHandlerException::ERR_UNABLE_TO_READ_SIZE_FROM_TCP_SOCKET
            );
        }

        $returnLenData = unpack("nlength", $returnLen);
        $dataLen = $returnLenData['length'];
        $rawDataResponse = fread($socket, $dataLen);
        $this->closeSocket();

        if ($rawDataResponse === false) {
            if ($retry < $this->retries) {
                return $this->query($hostName, $typeId, $retry + 1);
            }
            return null;
        }

        return new RawDataResponse($request, $rawDataResponse, $this->getType());
    }

    /**
     * @throws DnsHandlerException
     * @throws RawDataException
     */
    public function getDnsData(string $host, int $typeId): array
    {

        $this->validateParams($host, $typeId);
        $result = $this->query($host, $typeId);

        if (is_null($result)) {
            return [];
        }

        return $result->getData();
    }

}
