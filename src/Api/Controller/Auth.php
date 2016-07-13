<?php
namespace Api\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT as JWT;
use \Slim\Container as ContainerInterface;
use \Datetime;

class Auth
{
    public $decoded;
    protected $ci;

    public function __construct(ContainerInterface $ci) {
        $this->ci = $ci;
    }

    public function token(Request $request, Response $response, $arguments) {
        $data = $request->getParsedBody();

        //TODO: check user/pass

        $now = new DateTime();
        $future = new DateTime("now +2 hours");
        $payload = [
            "iat" => $now->getTimeStamp(),
            "exp" => $future->getTimeStamp(),
            //TODO: safe implementation for this
            "jti" => base64_encode(openssl_random_pseudo_bytes(16)),
            "sub" => $server["PHP_AUTH_USER"]
        ];

        $token = JWT::encode($payload, getenv("JWT_SECRET"), "HS256");

        return $response->withStatus(201)
            ->withHeader("Content-Type", "application/json")
            ->withJson([
                "status" => "ok",
                "token" => $token
            ]);
    }
}
