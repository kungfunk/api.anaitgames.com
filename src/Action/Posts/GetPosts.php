<?php

namespace Action\Posts;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Domain\Post\PostsRepository as PostsRepository;
use Action\Posts\GetPostsResponder as Responder;
use Action\Posts\GetPostsInput as Input;

class GetPosts
{
    private $posts_repository;
    private $responder;
    private $input;

    const POSTS_LIMIT = 10;

    function __construct() {
        $this->posts_repository = new PostsRepository;
        $this->responder = new Responder;
    }

    function __invoke(Request $request, Response $response) {
        $this->input = new Input($request);

        $posts = $this->posts_repository->getPostsPaginated(
            [
                'search' => $this->input->search,
                'type' => $this->input->type,
                'status' => $this->input->status,
                'tags' => $this->input->tags,
                'order_by' => $this->input->order_by,
                'order' => $this->input->order,
            ],
            $this::POSTS_LIMIT
        );

        return $this->responder->success($response, $posts);
    }
}