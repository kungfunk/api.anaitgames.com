<?php
namespace API\GetCommentsFromPost;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Domain\Post\PostsRepository as PostsRepository;
use Domain\Comment\CommentsRepository as CommentsRepository;
use API\GetCommentsFromPost\GetCommentsFromPostResponder as Responder;
use API\GetCommentsFromPost\GetCommentsFromPostInput as Input;

class GetCommentsFromPostAction
{
    private $posts_repository;
    private $comments_repository;
    private $responder;
    private $input;

    function __construct() {
        $this->posts_repository = new PostsRepository;
        $this->comments_repository = new CommentsRepository;
        $this->responder = new Responder;
    }

    function __invoke(Request $request, Response $response) {
        $this->input = new Input($request);

        $post = $this->posts_repository->getPostById($this->input->id);
        if(is_null($post)) {
            return $this->responder->notFound($response);
        }

        $comments = $this->comments_repository->getCommentsFromPostIdPaginated(
            $post->id,
            [
                'order' => $this->input->order,
                'limit' => $this->input->limit,
                'offset' => $this->input->offset,
            ]);

        return $this->responder->success($response, $comments);
    }
}