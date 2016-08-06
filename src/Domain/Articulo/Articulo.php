<?php
namespace Domain\Articulo;

class Articulo
{
    public $id;
    public $tipo;
    public $estado;
    public $fecha_publicacion;
    public $titular;
    public $subtitular;
    public $url;
    public $extracto;
    public $cuerpo;
    public $autor;
    public $video_youtube;
    public $numero_comentarios;
    public $visitas;
    public $nota;
    public $destacada;
    public $destacada_titulo;
    public $destacada_texto;
    public $relaciones; //TODO: change all this db complexity to string text tag based system

    private $comentarios_hilo_id; //TODO: remove this shit

    public function __construct() {
        $this->id = null;
        $this->tipo = null;
        $this->estado= null;
        $this->fecha_publicacion = null;
        $this->titular = null;
        $this->subtitular = null;
        $this->url = null;
        $this->extracto = null;
        $this->cuerpo = null;
        $this->autor = [
            "id" => null,
            "username" => null,
            "nombre" => null,
        ];
        $this->video_youtube = null;
        $this->numero_comentarios = 0;
        $this->visitas = 0;
        $this->nota = [
            "anait" => null,
            "usuarios_suma_total" => null,
            "usuarios_numero_votos" => null
        ];
        $this->destacada = false;
        $this->destacada_titulo = null;
        $this->destacada_texto = null;
        $this->relaciones = [
            "plataforma" => [],
            "fichas" => [],
            "juegos" => [],
        ];
    }

    public function getHiloComentariosId() {
        return $this->comentarios_hilo_id;
    }

    public function addVisita() {
        $this->visitas++;
    }
}
