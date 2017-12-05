<?php
namespace API\GetLogsFromUser;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Domain\User\LogsRepository as LogsRepository;
use Domain\User\UsersRepository as UsersRepository;
use API\GetLogsFromUser\GetLogsFromUserResponder as Responder;
use API\GetLogsFromUser\GetLogsFromUserInput as Input;

class GetLogsFromUserAction
{
    private $users_repository;
    private $logs_repository;
    private $responder;
    private $input;

    function __construct() {
        $this->logs_repository = new LogsRepository;
        $this->users_repository = new UsersRepository;
        $this->responder = new Responder;
    }

    function __invoke(Request $request, Response $response) {
        $this->input = new Input($request);

        $user = $this->users_repository->getUserById($this->input->id);
        if(is_null($user)) {
            return $this->responder->notFound($response);
        }

        $comments = $this->logs_repository->getLogsFromUserIdPaginated(
            $user->id,
            [
                'order' => $this->input->order,
                'limit' => $this->input->limit,
                'offset' => $this->input->offset,
            ]);

        return $this->responder->success($response, $comments);
    }
}