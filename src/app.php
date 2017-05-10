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

$app->post('/auth/token', '\Api\Controller\Auth:getToken');
$app->get('/usuarios', '\Api\Controller\Usuarios:getUsuariosPaginated');
$app->get('/usuarios/{id:[0-9]+}', '\Api\Controller\Usuarios:getUsuario');
$app->post('/usuarios/{id:[0-9]+}', '\Api\Controller\Usuarios:saveUsuario');
$app->delete('/usuarios/{id:[0-9]+}', '\Api\Controller\Usuarios:deleteUsuario');
$app->get('/usuarios/{id:[0-9]+}/logros', '\Api\Controller\Usuarios:getLogros');
$app->post('/usuarios/{id:[0-9]+}/logros', '\Api\Controller\Usuarios:addLogro');

$app->get('/logros', '\Api\Controller\Logros:getLogrosPaginated');
$app->get('/logros/{id:[0-9]+}', '\Api\Controller\Logros:getLogro');
$app->post('/logros/{id:[0-9]+}', '\Api\Controller\Logros:saveLogro');

$app->get('/articulos', '\Api\Controller\Articulos:getArticulosPaginated');
$app->get('/articulos/{id:[0-9]+}', '\Api\Controller\Articulos:getArticulo');
$app->post('/articulos/{id:[0-9]+}', '\Api\Controller\Articulos:saveArticulo');
$app->delete('/articulos/{id:[0-9]+}', '\Api\Controller\Articulos:deleteArticulo');
$app->get('/articulos/{id:[0-9]+}/comentarios', '\Api\Controller\Articulos:getComentarios');
$app->post('/articulos/{url:[a-z0-9-]+}/comentarios', '\Api\Controller\Articulos:getComentarios');

$app->get('/articulos/slug/{url:[a-z0-9-]+}', '\Api\Controller\Articulos:getArticuloByUrl');
$app->get('/usuarios/slug/{url:[a-z0-9-]+}', '\Api\Controller\Usuarios:getUsuarioByUrl');

$app->run();
