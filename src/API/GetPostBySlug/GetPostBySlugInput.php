<?php
namespace API\GetPostBySlug;

use Psr\Http\Message\ServerRequestInterface as Request;
use Exceptions\BadInputException as BadInputException;
use Domain\Post\Post as Post;
use Libs\SlugValidator as SlugValidator;

class GetPostBySlugInput
{
    const MINIMUM_ID = 1;

    public $slug;

    public function __construct(Request $request) {
        $this->slug = $request->getAttribute(Post::SLUG);

        if(!SlugValidator::checkSlug($this->slug)) {
            throw new BadInputException(BadInputException::IDENTIFIER_MALFORMED);
        }
    }
}