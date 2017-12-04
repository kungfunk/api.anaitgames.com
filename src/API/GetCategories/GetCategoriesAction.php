<?php

namespace API\GetCategories;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Domain\Post\CategoriesRepository as CategoriesRepository;
use API\GetCategories\GetCategoriesResponder as Responder;

class GetCategoriesAction
{
    private $categories_repository;
    private $responder;

    function __construct() {
        $this->categories_repository = new CategoriesRepository;
        $this->responder = new Responder;
    }

    function __invoke(Request $request, Response $response) {

        $tags = $this->categories_repository->getAll();

        return $this->responder->success($response, $tags);
    }
}