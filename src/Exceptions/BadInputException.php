<?php

namespace Exceptions;

class BadInputException extends \Exception {
    const LIMIT_EXCEEDED = 'The request exceeded the maximum items allowed';
    const BAD_QUERY_VALUE = 'One or more fields have not allowed values';
}