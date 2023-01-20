<?php

namespace MamaOmida\Dns\Facade;

use MamaOmida\Dns\DnsRecords;
use MamaOmida\Dns\Handlers\DnsHandlerException;
use MamaOmida\Dns\Handlers\DnsHandlerFactory;
use MamaOmida\Dns\Handlers\DnsHandlerFactoryException;
use MamaOmida\Dns\Handlers\DnsHandlerTypes;
use MamaOmida\Dns\Records\RecordException;

class DNS
{
    private static ?DnsHandlerFactory $dnsHandlerFactory = null;

    private static function getHandlerFactory(): DnsHandlerFactory
    {
        if (is_null(self::$dnsHandlerFactory)) {
            self::$dnsHandlerFactory = new DnsHandlerFactory();
        }
        return self::$dnsHandlerFactory;
    }

    /**
     * @param string $host
     * @param int|int[] $type
     * @param string|null $handlerType
     * @param bool|null $useExtendedRecords
     * @return array
     * @throws DnsHandlerException
     * @throws DnsHandlerFactoryException
     * @throws RecordException
     */
    public static function getRecords(
        string  $host,
                $type,
        ?string $handlerType = DnsHandlerTypes::TCP,
        ?bool   $useExtendedRecords = true,
        ?string $nameserver = null): array
    {
        $dnsHandler = self::getHandlerFactory()
            ->create($handlerType);

        if (!is_null($nameserver)) {
            $dnsHandler->setNameserver($nameserver);
        }

        return (new DnsRecords())
            ->setHandler(
                $dnsHandler
            )
            ->get($host, $type, $useExtendedRecords);
    }

}
