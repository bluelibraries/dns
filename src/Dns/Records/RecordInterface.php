<?php

namespace MamaOmida\Dns\Records;

interface RecordInterface
{

    public function setData(array $data): self;

    public function getTypeId(): int;

    public function getHost(): ?string;

    public function getClass(): ?string;

    public function getTtl(): ?int;

    public function toArray(): array;

    public function toString(string $separator = ' '): string;

}