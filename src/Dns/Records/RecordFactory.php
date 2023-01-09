<?php

namespace MamaOmida\Dns\Records;

use MamaOmida\Dns\Records\Types\A;
use MamaOmida\Dns\Records\Types\AAAA;
use MamaOmida\Dns\Records\Types\CAA;
use MamaOmida\Dns\Records\Types\CNAME;
use MamaOmida\Dns\Records\Types\DnsSec\CDNSKey;
use MamaOmida\Dns\Records\Types\DnsSec\CDS;
use MamaOmida\Dns\Records\Types\DnsSec\DNSKey;
use MamaOmida\Dns\Records\Types\DnsSec\DS;
use MamaOmida\Dns\Records\Types\DnsSec\NSEC;
use MamaOmida\Dns\Records\Types\DnsSec\NSEC3Param;
use MamaOmida\Dns\Records\Types\DnsSec\RRSig;
use MamaOmida\Dns\Records\Types\HInfo;
use MamaOmida\Dns\Records\Types\HTTPS;
use MamaOmida\Dns\Records\Types\MX;
use MamaOmida\Dns\Records\Types\NAPTR;
use MamaOmida\Dns\Records\Types\NS;
use MamaOmida\Dns\Records\Types\SOA;
use MamaOmida\Dns\Records\Types\SRV;
use MamaOmida\Dns\Records\Types\Txt;

class RecordFactory
{

    private ExtendedTxtRecords $extendedTxtRecords;

    public function __construct(ExtendedTxtRecords $extendedTxtRecords = null)
    {
        if (is_null($extendedTxtRecords)) {
            $extendedTxtRecords = new ExtendedTxtRecords();
        }
        $this->extendedTxtRecords = $extendedTxtRecords;
    }

    /**
     * @param array $recordData
     * @param bool $extendedRecords
     * @return RecordInterface
     * @throws RecordException
     */
    public function create(array $recordData, bool $extendedRecords)
    {

        if (
            !isset($recordData['type'])
            || is_null($type = RecordTypes::getType($recordData['type']))
        ) {
            throw new RecordException(
                'Invalid record type for recordData: ' .
                json_encode($recordData),
                RecordException::UNABLE_TO_CREATE_RECORD
            );
        }

        switch ($type) {

            case RecordTypes::A:
                return new A($recordData);

            case RecordTypes::NS:
                return new NS($recordData);

            case RecordTypes::CNAME:
                return new CNAME($recordData);

            case RecordTypes::SOA:
                return new SOA($recordData);

            case RecordTypes::MX:
                return new MX($recordData);

            case RecordTypes::CAA:
                return new CAA($recordData);

            case RecordTypes::HINFO:
                return new HInfo($recordData);

            case RecordTypes::RRSIG:
                return new RRSig($recordData);

            case RecordTypes::DNSKEY:
                return new DNSKey($recordData);

            case RecordTypes::CDNSKEY:
                return new CDNSKey($recordData);

            case RecordTypes::NSEC3_PARAM:
                return new NSEC3Param($recordData);

            case RecordTypes::CDS:
                return new CDS($recordData);

            case RecordTypes::NSEC:
                return new NSEC($recordData);

            case RecordTypes::SRV:
                return new SRV($recordData);

            case RecordTypes::TXT:

                $record = new TXT($recordData);

                if ($extendedRecords) {
                    $extendedRecord = $this->extendedTxtRecords->getExtendedTxtRecord($recordData);
                    return is_null($extendedRecord) ? $record : $extendedRecord;
                } else {
                    return $record;
                }

            case RecordTypes::AAAA:
                return new AAAA($recordData);

            case RecordTypes::HTTPS:
                return new HTTPS($recordData);

            case RecordTypes::DS:
                return new DS($recordData);

            case RecordTypes::NAPTR:
                return new NAPTR($recordData);

            default:
                throw new RecordException(
                    'Unable to create record type ' . json_encode($type) .
                    ' for record data: ' . json_encode($recordData),
                    RecordException::UNABLE_TO_CREATE_RECORD_TYPE
                );
        }

    }

}
