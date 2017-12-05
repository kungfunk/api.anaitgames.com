<?php
namespace API\GetCommentsFromPost;

use Psr\Http\Message\ResponseInterface as Response;
use API\Responder as Responder;

class GetCommentsFromPostResponder extends Responder
{
    const POST_NOT_FOUND_ERROR_MSG = 'The entity does not exists in our database';

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
                'message' => self::POST_NOT_FOUND_ERROR_MSG
            ]);
    }
}