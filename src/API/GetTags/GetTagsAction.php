<?php

namespace API\GetTags;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Domain\Post\TagsRepository as TagsRepository;
use API\GetTags\GetCategoriesResponder as Responder;

class GetTagsAction
{
    private $tags_repository;
    private $responder;

    function __construct() {
        $this->tags_repository = new TagsRepository;
        $this->responder = new Responder;
    }

    function __invoke(Request $request, Response $response) {

        $tags = $this->tags_repository->getAll();

        return $this->responder->success($response, $tags);
    }
}