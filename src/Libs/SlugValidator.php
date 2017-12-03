<?php
namespace Libs;

class SlugValidator
{
    const SLUG_REGEX_WITH_DASHES = '/^[a-z0-9]+(?:-[a-z0-9]+)*$/';
    const SLUG_REGEX_WITH_UNDERSCORES = '/^[a-z0-9]+(?:_[a-z0-9]+)*$/';

    public static function checkSlug($slug, $use_underscores = false) {
        $regex = $use_underscores ? self::SLUG_REGEX_WITH_UNDERSCORES : self::SLUG_REGEX_WITH_DASHES;
        return preg_match($regex, $slug);
    }
}