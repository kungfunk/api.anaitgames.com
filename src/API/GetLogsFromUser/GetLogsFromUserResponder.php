<?php
namespace API\GetLogsFromUser;

use Psr\Http\Message\ResponseInterface as Response;
use API\Responder as Responder;

class GetLogsFromUserResponder extends Responder
{
    const USER_NOT_FOUND_ERROR_MSG = 'The user does not exists in our database';

    public function success(Response $response, $data) {
        return $response
            ->withStatus($this::HTTP_STATUS_CODE_OK)
            ->withHeader("Content-Type", "application/json")
            ->withJson([
                'status' => $this::STATUS_SUCCESS,
                'data' => $data,
            ]);
    }

    public function notFound($response) {
        return $response
            ->withStatus($this::HTTP_STATUS_CODE_NOT_FOUND)
            ->withJson([
                'status' => $this::STATUS_ERROR,
                'message' => self::USER_NOT_FOUND_ERROR_MSG
            ]);
    }
}