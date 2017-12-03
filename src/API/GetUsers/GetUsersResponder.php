<?php
namespace API\GetUsers;

use Psr\Http\Message\ResponseInterface as Response;
use API\Responder as Responder;

class GetUsersResponder extends Responder
{
    public function success(Response $response, $data) {
        return $response
            ->withStatus(parent::HTTP_STATUS_CODE_OK)
            ->withHeader("Content-Type", "application/json")
            ->withJson([
                'status' => parent::STATUS_SUCCESS,
                'data' => $data,
            ]);
    }
}