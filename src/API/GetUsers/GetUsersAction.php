<?php
namespace API\GetUsers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Domain\User\UsersRepository as UsersRepository;
use API\GetUsers\GetUsersResponder as Responder;
use API\GetUsers\GetUsersInput as Input;

class GetUsersAction
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

        $posts = $this->users_repository->GetUsersPaginated(
            [
                'search' => $this->input->search,
                'username' => $this->input->username,
                'order_by' => $this->input->order_by,
                'order' => $this->input->order,
                'limit' => $this->input->limit,
                'offset' => $this->input->offset
            ]
        );

        return $this->responder->success($response, $posts);
    }
}