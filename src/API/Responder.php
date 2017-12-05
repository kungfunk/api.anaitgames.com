<?php
namespace API;

abstract class Responder
{
    const HTTP_STATUS_CODE_OK = 200;
    const HTTP_STATUS_CODE_CREATED = 201;
    const HTTP_STATUS_CODE_NOT_FOUND = 404;

    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';
}