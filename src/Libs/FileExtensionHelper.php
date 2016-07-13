<?php
namespace Libs;

class FileExtensionHelper
{
    private static $extensions = array(
        "image/jpeg" => "jpeg",
        "image/png" => "png",
        "image/gif" => "gif"
    );

    public static function mimeToExt($mime) {
        if(!key_exists(self::$extensions, $mime))
            throw new \Exception("The extension $mime not found in the helper");

        return self::$extensions[$mime];
    }
}