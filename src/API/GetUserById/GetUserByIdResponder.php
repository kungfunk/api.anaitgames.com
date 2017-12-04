<?php
namespace API\GetUserById;

use Psr\Http\Message\ResponseInterface as Response;
use API\Responder as Responder;

class GetUserByIdResponder extends Responder
{
    const POST_NOT_FOUND_ERROR_MSG = 'The entity does not exists in our database';

    public function success(Response $response, $data) {
        return $response
            ->withStatus(parent::HTTP_STATUS_CODE_OK)
            ->withHeader("Content-Type", "application/json")
            ->withJson([
                'status' => parent::STATUS_SUCCESS,
                'data' => $data,
            ]);
    }

    public function notFound($response) {
        return $response
            ->withStatus(parent::HTTP_STATUS_CODE_NOT_FOUND)
            ->withJson([
                'status' => parent::STATUS_ERROR,
                'message' => self::POST_NOT_FOUND_ERROR_MSG
            ]);
    }
}