<?php
namespace Domain\Usuario;

use \Domain\Usuario\Usuario as Usuario;
use \Libs\FileExtensionHelper as FileExtensionHelper;

class UsuarioFactory
{
    public static function createEmptyUsuario() {
        $usuario = new Usuario;

        return $usuario;
    }

    public static function createUsuarioFromData($data) {
        $usuario = new Usuario;

        $usuario->id = $data["id"];
        $usuario->username = $data["usuario_url"];
        $usuario->email = $data["email"];
        $usuario->nombre = $data["suario"];
        $usuario->last_seen = $data["lastseen"];
        $usuario->ip = $data["ip"];
        $usuario->fecha_alta = $data["fecha_alta"];
        $usuario->confirmado = !!$data["email_confirmado"];
        $usuario->rol = $data["id_rol"]; //TODO: change this to enum.
        $usuario->visitas = (int) $data["num_logins"];
        $usuario->rango = $data["rango"];
        $usuario->twitter = $data["url_twitter"];
        $usuario->descripcion_anait = $data["equipo_anait_desc"]; //TODO: remove this field
        $usuario->avatar = $data["id"].".".FileExtensionHelper::mimeToExt($data["avatar_mime_type"]);
        $usuario->cabecera = $data["id"].".".FileExtensionHelper::mimeToExt($data["cabecera_mime_type"]);
        $usuario->patreon = !!$data["patreon"];
        $usuario->options = [
            "notificacion_mail_mensaje" => !!$data["notificacion_mail_mensaje"],
            "notificacion_mail_seguimiento" => !!$data["notificacion_mail_seguimiento"],
            "notificacion_mail_mencion" => !!$data["notificacion_mail_mencion"],
            "notificacion_mail_comentario" => !!$data["notificacion_mail_comentario"],
            "notificacion_mail_retwito" => !!$data["notificacion_mail_retwit"],
            "notificacion_mail_logro" => !!$data["notificacion_mail_logro"]
        ];

        return $usuario;
    }
    
    public static function createDBOFromUsuario(Usuario $usuario) {
        $dbo = [];

        $dbo["id"] = $usuario->id;
        $dbo["email"] = $usuario->email;
        $dbo["lastseen"] = $usuario->last_seen;
        $dbo["ip"] = $usuario->ip;
        $dbo["fecha_alta"] = $usuario->fecha_alta;
        $dbo["email_confirmado"] = (int) $usuario->confirmado;
        $dbo["id_rol"] = $usuario->rol;
        $dbo["usuario"] = $usuario->nombre;
        $dbo["rango"] = $usuario->rango;
        $dbo["url_twitter"] = $usuario->twitter;
        $dbo["usuario_url"] = $usuario->username;
        $dbo["equipo_anait_desc"] = $usuario->descripcion_anait;
        $dbo["notificacion_mail_mensaje"] = $usuario->options["notificacion_mail_mensaje"];
        $dbo["notificacion_mail_seguimiento"] = $usuario->options["notificacion_mail_seguimiento"];
        $dbo["notificacion_mail_mencion"] = $usuario->options["notificacion_mail_mencion"];
        $dbo["notificacion_mail_comentario"] = $usuario->options["notificacion_mail_comentario"];
        $dbo["notificacion_mail_retwit"] = $usuario->options["notificacion_mail_retwit"];
        $dbo["notificacion_mail_logro"] = $usuario->options["notificacion_mail_logro"];
        $dbo["patreon"] = (int) $usuario->patreon;

        if(file_exists($usuario->getAvatarURI()))
            $dbo["avatar_mime_type"] = image_type_to_mime_type(exif_imagetype($usuario->getAvatarURI()));

        if(file_exists($usuario->getCabeceraURI()))
            $dbo["cabecera_mime_type"] = image_type_to_mime_type(exif_imagetype($usuario->getCabeceraURI()));


        if($usuario->isPasswordChanged()) {
            $dbo["password"] = $usuario->getNewPassword();
        }

        if($usuario->isUserRegistry()) {
            $dbo["codconfirm_email"] = $usuario->getCodConfirmacion();
        }
        
        return $dbo;
    }

    public static function createUsuarioFromDataWithNotificaciones($data) {
        $usuario = self::createUsuarioFromData($data);
        $usuario->num_notificaciones = $data['notificaciones'];

        return $usuario;
    }
}