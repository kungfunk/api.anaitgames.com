<?php
namespace API\GetPostById;

use API\Input;
use Psr\Http\Message\ServerRequestInterface as Request;
use Exceptions\BadInputException as BadInputException;
use Domain\Post\Post as Post;

class GetPostByIdInput extends Input
{
    public $id;

    public function __construct(Request $request) {
        $this->id = (int) $request->getAttribute(Post::ID);

        if($this->id < $this::MINIMUM_ID) {
            throw new BadInputException(BadInputException::IDENTIFIER_MALFORMED);
        }
    }
}