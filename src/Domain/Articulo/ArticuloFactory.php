<?php
namespace Domain\Articulo;

use \Domain\Articulo\Articulo as Articulo;

class ArticuloFactory
{
    public static function createEmptyArticulo()
    {
        $articulo = new Articulo;

        return $articulo;
    }

    public static function createArticuloFromData($data) {
        $articulo = new Articulo;

        $articulo->id = $data["id"];
        $articulo->tipo = $data["tipo"];
        $articulo->estado = $data["estado"];
        $articulo->fecha_publicacion = $data["fecha_publicacion"];
        $articulo->titular = $data["titular"];
        $articulo->subtitular = $data["subtitular"];
        $articulo->url = $data["url"];
        $articulo->extracto = $data["extracto"];
        $articulo->cuerpo = $data["cuerpo"];
        $articulo->autor = [
            "id" => $data["creador"],
            "username" => null,
            "nombre" => null,
        ];
        $articulo->video_youtube = $data["video"];
        $articulo->numero_comentarios = (int) $data["numero_comentarios"];
        $articulo->visitas = (int) $data["numero_visitas"];
        $articulo->nota = [
            "anait" => $data["nota_anait"],
            "usuarios_suma_total" => $data["suma_total_votos"],
            "usuarios_numero_votos" => $data["numero_total_votos"]
        ];
        $articulo->destacada = filter_var($data["destacada"], FILTER_VALIDATE_BOOLEAN);
        $articulo->destacada_titulo = $data["destacada_boton"];
        $articulo->destacada_texto = $data["destacada_texto"];
        $articulo->relaciones  = [
            "plataforma" => [],
            "fichas" => [],
            "juegos" => [],
        ];

        $articulo->setHiloComentariosId($data["id_foro_hilo"]);

        return $articulo;
    }

    public static function createDBOFromArticulo(Articulo $articulo) {
        $dbo = [];

        $dbo["id"] = $articulo->id;
        $dbo["tipo"] = $articulo->tipo;
        $dbo["estado"] = $articulo->estado;
        $dbo["fecha_publicacion"] = $articulo->fecha_publicacion;
        $dbo["titular"] = $articulo->titular;
        $dbo["subtitular"] = $articulo->subtitular;
        $dbo["url"] = $articulo->url;
        $dbo["extracto"] = $articulo->extracto;
        $dbo["cuerpo"] = $articulo->cuerpo;
        $dbo["creador"] = $articulo->autor["id"];
        $dbo["video"] = $articulo->video_youtube;
        $dbo["numero_comentarios"] = $articulo->numero_comentarios;
        $dbo["numero_visitas"] = $articulo->visitas;
        $dbo["nota_anait"] = $articulo->nota["anait"];
        $dbo["suma_total_votos"] = $articulo->nota["usuarios_suma_total"];
        $dbo["numero_total_votos"] = $articulo->nota["usuarios_numero_votos"];
        $dbo["destacada"] = (int) $articulo->destacada;
        $dbo["destacada_titulo"] = $articulo->destacada_boton;
        $dbo["destacada_texto"] = (int) $articulo->destacada_texto;

        $dbo["id_foro_hilo"] = $articulo->getHiloComentariosId();

        return $dbo;
    }
}