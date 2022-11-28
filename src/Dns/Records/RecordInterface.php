<?php

namespace MamaOmida\Dns\Records;

interface RecordInterface
{

    public function setData(array $rawData): self;

    public function toArray(): array;

    public function getTypeId(): int;

    public function getHost(): ?string;

    public function getClass(): ?string;

    public function getTtl(): ?int;

}