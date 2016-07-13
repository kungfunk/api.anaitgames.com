<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Api\Authentication\Token as Token;
use \Slim\Middleware\JwtAuthentication as JwtAuthentication;
use \Dotenv\Dotenv as Dotenv;

require "../vendor/autoload.php";
require "config.php";

$dotenv = new Dotenv(__DIR__."/..");
$dotenv->load();

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

$container["token"] = function ($container) {
    return new Token;
};

$app->add(new JwtAuthentication([
    "path" => "/",
    "passthrough" => [
        "/auth/token",
        "/help"
    ],
    "secret" => getenv("jwt_secret"),
    "error" => function (Request $request, Response $response, $arguments) {
        return $response
            ->withHeader("Content-Type", "application/json")
            ->withJson([
                "status" => "error",
                "message" => $arguments["message"]
            ]);
    },
    "callback" => function ($request, $response, $arguments) use ($container) {
        //$container["token"]->hydrate($arguments["decoded"]);
    }
]));

$app->post('/auth/token', '\Api\Controller\Auth:token');
$app->get('/usuarios', '\Api\Controller\Usuarios:getPaginated');

$app->get("/hello/{name}", function (Request $request, Response $response) {
    $name = $request->getAttribute("name");
    $response->withJson(['name' => $name, 'age' => 40]);

    return $response;
});
$app->run();
