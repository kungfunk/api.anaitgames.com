<?php

namespace API\GetPostById;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Domain\Post\PostsRepository as PostsRepository;
use API\GetPostById\GetPostByIdResponder as Responder;
use API\GetPostById\GetPostByIdInput as Input;

class GetPostByIdAction
{
    private $posts_repository;
    private $responder;
    private $input;

    function __construct() {
        $this->posts_repository = new PostsRepository;
        $this->responder = new Responder;
    }

    function __invoke(Request $request, Response $response) {
        $this->input = new Input($request);

        $post = $this->posts_repository->getPostById($this->input->id);
        if(is_null($post)) {
            return $this->responder->notFound($response);
        }

        return $this->responder->success($response, $post);
    }
}