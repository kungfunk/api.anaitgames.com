<?php
namespace Libs;

class SlugValidator
{
    const SLUG_VALID_REGEX = '/^[a-z0-9]+(?:-[a-z0-9]+)*$/';

    public static function checkSlug($slug) {
        return preg_match(self::SLUG_VALID_REGEX, $slug);
    }
}