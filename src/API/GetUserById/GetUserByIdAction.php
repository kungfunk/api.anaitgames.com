<?php

namespace API\GetUserById;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Domain\User\UsersRepository as UsersRepository;
use API\GetUserById\GetUserByIdResponder as Responder;
use API\GetUserById\GetUserByIdInput as Input;

class GetUserByIdAction
{
    private $users_repository;
    private $responder;
    private $input;

    function __construct() {
        $this->users_repository = new UsersRepository;
        $this->responder = new Responder;
    }

    function __invoke(Request $request, Response $response) {
        $this->input = new Input($request);

        $post = $this->users_repository->getUserById($this->input->id);
        if(is_null($post)) {
            return $this->responder->notFound($response);
        }

        return $this->responder->success($response, $post);
    }
}