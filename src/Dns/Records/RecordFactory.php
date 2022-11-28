<?php

namespace MamaOmida\Dns\Records;

use MamaOmida\Dns\Records\Types\A;
use MamaOmida\Dns\Records\Types\AAAA;
use MamaOmida\Dns\Records\Types\CNAME;
use MamaOmida\Dns\Records\Types\MX;
use MamaOmida\Dns\Records\Types\NS;
use MamaOmida\Dns\Records\Types\SOA;
use MamaOmida\Dns\Records\Types\TXT;

class RecordFactory
{

    /**
     * @param array $recordData
     * @return RecordInterface
     * @throws RecordException
     */
    public function create(array $recordData)
    {

        if (
            !isset($recordData['type'])
            || is_null($type = DnsRecordTypes::getType($recordData['type']))
        ) {
            throw new RecordException(
                'Invalid record type for recordData: ' .
                json_encode($recordData),
                RecordException::UNABLE_TO_CREATE_RECORD
            );
        }

        switch ($type) {

            case DnsRecordTypes::A:
                return new A($recordData);

            case DnsRecordTypes::NS:
                return new NS($recordData);

            case DnsRecordTypes::CNAME:
                return new CNAME($recordData);

            case DnsRecordTypes::SOA:
                return new SOA($recordData);

            case DnsRecordTypes::MX:
                return new MX($recordData);

            case DnsRecordTypes::TXT:
                return new TXT($recordData);

            case DnsRecordTypes::AAAA:
                return new AAAA($recordData);

            default:
                throw new RecordException(
                    'Unable to create record type ' . json_encode($type),
                    RecordException::UNABLE_TO_CREATE_RECORD_TYPE
                );
        }

    }

}