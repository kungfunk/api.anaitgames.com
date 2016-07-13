<?php
namespace Domain\Usuario;

class Usuario
{
    public $id;
    public $username;
    public $email;
    public $nombre;
    public $lastseen;
    public $ip;
    public $fecha_alta;
    public $confirmado;
    public $rol;  //TODO: change this to enum.
    public $visitas;
    public $rango;
    public $twitter;
    public $descripcion_anait; //TODO: remove this field
    public $avatar;
    public $cabecera;
    public $patreon;
    public $options;

    //Optionals
    public $num_notificaciones;
    public $notificaciones;

    private $password_changed;
    private $new_password;

    private $user_registry;
    private $cod_confirmacion;

    public function __construct() {
        $this->password_changed = false;
        $this->new_password = null;
        $this->user_registry = false;

        $this->id = null;
        $this->username = null;
        $this->email = null;
        $this->nombre = null;
        $this->last_seen = null;
        $this->ip = null;
        $this->fecha_alta = null;
        $this->confirmado = false;
        $this->rol = 0;
        $this->visitas = 0;
        $this->rango = null;
        $this->twitter = null;
        $this->descripcion_anait = null;
        $this->avatar = null;
        $this->cabecera = null;
        $this->patreon = false;
        $this->options = [
            "notificacion_mail_mensaje" => false,
            "notificacion_mail_seguimiento" => false,
            "notificacion_mail_mencion" => false,
            "notificacion_mail_comentario" => false,
            "notificacion_mail_retwito" => false,
            "notificacion_mail_logro" => false,
        ];

        $this->password_changed = false;
    }

    public function isBaneado() {
        return $this->rol === "1";
    }

    public function isPasswordChanged() {
        return $this->password_changed;
    }

    public function isUserRegistry() {
        return $this->user_registry;
    }

    public function getNewPassword() {
        return $this->new_password;
    }

    public function getCodConfirmacion() {
        return $this->cod_confirmacion;
    }
    
    public function getAvatarURI() {
        return AVATAR_PATH.$this->avatar;
    }

    public function getCabeceraURI() {
        return CABECERA_PATH.$this->cabecera;
    }

    public function changePassword($new_password) {
        $this->new_password = $new_password; //TODO: add SHA1 or custom secure here
        $this->password_changed = true;
    }

    public function registerUsuarioWithPassword($password) {
        $this->changePassword($password);
        $this->cod_confirmacion = uniqid();
        $this->user_registry = true;
    }

    public function addVisita() {
        $this->visitas++;
    }
}
