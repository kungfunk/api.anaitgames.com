<?php

namespace API\GetPostBySlug;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Domain\Post\PostsRepository as PostsRepository;
use API\GetPostBySlug\GetPostBySlugResponder as Responder;
use API\GetPostBySlug\GetPostBySlugInput as Input;

class GetPostBySlugAction
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

        $post = $this->posts_repository->getPostBySlug($this->input->slug);
        if(is_null($post)) {
            return $this->responder->notFound($response);
        }

        return $this->responder->success($response, $post);
    }
}