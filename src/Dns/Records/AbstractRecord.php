<?php

namespace MamaOmida\Dns\Records;

abstract class AbstractRecord implements RecordInterface
{

    protected array $data = [];

    public abstract function getTypeId(): int;

    public function __construct(array $data)
    {
        $this->setData($data);
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        if (!isset($this->data['host'])) {
            $this->data['host'] = '';
        }

        if (!isset($this->data['ttl'])) {
            $this->data['ttl'] = 0;
        }

        if (!isset($this->data['class'])) {
            $this->data['class'] = 'IN';
        }

        if (!isset($this->data['type'])) {
            $this->data['type'] = DnsRecordTypes::getName($this->getTypeId());
        }

        return $this;
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function getHost(): ?string
    {
        return $this->data['host'] ?? null;
    }

    public function getClass(): ?string
    {
        return $this->data['class'] ?? null;
    }

    public function getTtl(): ?int
    {
        return isset($this->data['ttl'])
            ? (int)$this->data['ttl']
            : null;
    }

    public function toString(string $separator = ' '): string
    {
        return implode(
            $separator,
            DnsRecordProperties::getFilteredProperties($this->getTypeId(), $this->data)
        );
    }

}