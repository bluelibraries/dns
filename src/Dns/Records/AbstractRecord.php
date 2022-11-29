<?php

namespace MamaOmida\Dns\Records;

abstract class AbstractRecord implements RecordInterface
{

    protected array $data = [];

    public abstract function getTypeId(): int;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function setData(array $rawData): self
    {
        $this->data = $rawData;
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

}