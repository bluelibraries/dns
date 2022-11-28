<?php

namespace MamaOmida\Dns\Handlers;

use Exception;

class DnsHandlerException extends Exception
{
    const HOSTNAME_EMPTY = 1;
    const HOSTNAME_LENGTH_TOO_SMALL = 2;
    const HOSTNAME_FORMAT_INVALID = 3;
    const HOSTNAME_LENGTH_INVALID = 4;
    const HOSTNAME_TLD_LENGTH_INVALID = 5;
}