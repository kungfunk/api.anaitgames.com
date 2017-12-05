<?php
namespace API\GetPosts;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Domain\Post\PostsRepository as PostsRepository;
use API\GetPosts\GetPostsResponder as Responder;
use API\GetPosts\GetPostsInput as Input;

class GetPostsAction
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

        $posts = $this->posts_repository->getPostsPaginated(
            [
                'search' => $this->input->search,
                'type' => $this->input->type,
                'slug' => $this->input->slug,
                'status' => $this->input->status,
                'order_by' => $this->input->order_by,
                'order' => $this->input->order,
                'limit' => $this->input->limit,
                'offset' => $this->input->offset
            ]
        );

        return $this->responder->success($response, $posts);
    }
}