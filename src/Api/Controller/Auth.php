<?php
namespace Api\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT as JWT;
use \Slim\Container as ContainerInterface;
use \Database\PDO\Repositories\UsuarioRepository as UsuarioRepository;
use \Api\Exceptions\AuthenticationException as AuthenticationException;

class Auth
{
    public $decoded;
    protected $ci;
    private $usuarios_repository;

    public function __construct(ContainerInterface $ci) {
        $this->ci = $ci;
        $this->usuarios_repository = new UsuarioRepository;
    }

    public function token(Request $request, Response $response, $arguments) {
        $data = $request->getParsedBody();

        if(!isset($data["username"], $data["password"]) || !$data["username"] || !$data["password"])
            throw new AuthenticationException("Wrong parameters");

        //TODO: check user/pass
        $usuario = $this->usuarios_repository->getByUsername($data["username"]);
        if(!$usuario)
            throw new AuthenticationException("Incorrect username");

        if(!$usuario->compareWithEncryptedPassword($data["password"]))
            throw new AuthenticationException("Incorrect password");

        if(!$usuario->isAdministrator())
            throw new AuthenticationException("Not enought permissions");

        $now = new \DateTime();
        $future = new \DateTime("now +2 hours");
        $payload = [
            "iat" => $now->getTimeStamp(),
            "exp" => $future->getTimeStamp(),
            "jti" => base64_encode(openssl_random_pseudo_bytes(16)),
            //TODO: add posible scopes for the user
            "uid" => $usuario->id,
            "role" => $usuario->rol
        ];

        $token = JWT::encode($payload, getenv("JWT_SECRET"), "HS256");

        return $response
            ->withStatus(201)
            ->withHeader("Content-Type", "application/json")
            ->withJson([
                "status" => "ok",
                "token" => $token
            ]);
    }
}
