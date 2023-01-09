<?php

namespace MamaOmida\Dns\Records\Types\DnsSec;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\RecordTypes;

class RRSig extends AbstractRecord
{
    public function getTypeId(): int
    {
        return RecordTypes::RRSIG;
    }

    public function getSignatureType(): ?int
    {
        return $this->data['type-covered'] ?? null;
    }

    public function getAlgorithm(): ?int
    {
        return $this->data['algorithm'] ?? null;
    }

    public function getLabelsNumber(): ?int
    {
        return $this->data['labels-number'] ?? null;
    }

    public function getOriginalTtl(): ?int
    {
        return $this->data['original-ttl'] ?? null;
    }

    public function getExpiration(): ?int
    {
        return $this->data['signature-expiration'] ?? null;
    }

    /**
     * @return int|null
     * @meta same with Inception
     */
    public function getCreation(): ?int
    {
        return $this->data['signature-creation'] ?? null;
    }

    public function getTag(): ?int
    {
        return $this->data['key-tag'] ?? null;
    }

    public function getTarget(): ?string
    {
        return $this->data['signer-name'] ?? null;
    }

    public function getSignature(): ?string
    {
        return $this->data['signature'] ?? null;
    }

}
