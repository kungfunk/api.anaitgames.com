<?php

namespace Exceptions;

class BadInputException extends \Exception {
    const LIMIT_EXCEEDED = 'The request exceeded the maximum items allowed';
    const IDENTIFIER_MALFORMED = 'The identifier is malformed or has invalid characters';
    const BAD_QUERY_VALUE = 'One or more fields have not allowed values';
}