<?php

namespace MamaOmida\Dns\Handlers\Raw;

use Exception;

class RawDataException extends Exception
{
    const ERR_INVALID_CLASS_ID = 1;
    const ERR_INVALID_ADDRESS = 2;
}