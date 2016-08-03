<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Api\Authentication\Token as Token;
use \Slim\Middleware\JwtAuthentication as JwtAuthentication;
use \Dotenv\Dotenv as Dotenv;
use \Slim\App as App;
use \Slim\Container as Container;

require "../vendor/autoload.php";
require "config.php";

$dotenv = new Dotenv(__DIR__."/..");
$dotenv->load();

$container = new Container;
$app = new App($container);

$container["errorHandler"] = function ($container) {
    return function (Request $request, Response $response, $exception) use ($container) {
        return $container["response"]
            ->withStatus(500)
            ->withHeader("Content-Type", "application/json")
            ->withJson([
                "status" => "error",
                "message" => $exception->getMessage()
            ]);
    };
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
        $container["token"] = new Token;
        $container["token"]->hydrate($arguments["decoded"]);
    }
]));

$app->post('/auth/token', '\Api\Controller\Auth:token');
$app->get('/usuarios', '\Api\Controller\Usuarios:getPaginated');
$app->get('/usuarios/{id:[0-9]+}', '\Api\Controller\Usuarios:getUsuario');
$app->post('/usuarios/{id:[0-9]+}', '\Api\Controller\Usuarios:saveUsuario');

$app->run();
