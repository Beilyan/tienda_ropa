<?php
declare(strict_types=1);

/** Lectura de la configuración: Config::get('app.por_pagina.catalogo'). */
final class Config
{
    private static array $datos = [];

    public static function cargar(string $nombre): mixed
    {
        return self::$datos[$nombre] ??= require RAIZ . '/config/' . $nombre . '.php';
    }

    public static function get(string $ruta, mixed $defecto = null): mixed
    {
        $partes = explode('.', $ruta);
        $valor = self::cargar(array_shift($partes));
        foreach ($partes as $p) {
            if (!is_array($valor) || !array_key_exists($p, $valor)) {
                return $defecto;
            }
            $valor = $valor[$p];
        }
        return $valor;
    }

    /** ¿Existe el archivo con los datos de conexión? */
    public static function instalada(): bool
    {
        return self::cargar('database') !== null;
    }

    /** Zona horaria elegida durante la instalación. */
    public static function zonaHoraria(): string
    {
        $local = is_file(RAIZ . '/config/local.php') ? require RAIZ . '/config/local.php' : [];
        return (string) ($local['zona_horaria'] ?? self::get('app.zona_horaria'));
    }
}
