<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Dotenv\Dotenv as Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Slim\App as App;
use Slim\Container as Container;

require "../vendor/autoload.php";

$dotenv = new Dotenv(__DIR__."/..");
$dotenv->load();

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => getenv('SQL_HOST'),
    'database' => getenv('SQL_DB'),
    'username' => getenv('SQL_USER'),
    'password' => getenv('SQL_PASS'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci'
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container = new Container;
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

$app = new App($container);
$app->get('/posts', '\API\GetPosts\GetPostsAction');
$app->get('/posts/{id}', '\API\GetPostById\GetPostByIdAction');
$app->get('/posts/{id}/comments', '\API\GetCommentsFromPost\GetCommentsFromPostAction');
$app->run();