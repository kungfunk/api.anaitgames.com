<?php
namespace API\GetTags;

use Psr\Http\Message\ResponseInterface as Response;
use API\Responder as Responder;

class GetTagsResponder extends Responder
{
    public function success(Response $response, $data) {
        return $response
            ->withStatus($this::HTTP_STATUS_CODE_OK)
            ->withHeader("Content-Type", "application/json")
            ->withJson([
                'status' => $this::STATUS_SUCCESS,
                'data' => $data,
            ]);
    }
}