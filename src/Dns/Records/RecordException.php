<?php

namespace MamaOmida\Dns\Records;

use Exception;

class RecordException extends Exception
{
    const UNABLE_TO_CREATE_RECORD = 1;
    const UNABLE_TO_CREATE_RECORD_TYPE = 2;
    const UNABLE_TO_NORMALIZE_TXT_RECORD = 3;
}