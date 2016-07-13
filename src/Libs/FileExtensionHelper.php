<?php
namespace Libs;

class FileExtensionHelper
{
    private static $extensions = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/gif" => "gif"
    ];

    public static function mimeToExt($mime) {
        if(!key_exists($mime, self::$extensions))
            throw new \Exception("The extension $mime not found in the helper");

        return self::$extensions[$mime];
    }
}