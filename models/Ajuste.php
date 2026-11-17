<?php
declare(strict_types=1);

/** Ajustes editables de la tienda (tabla "ajustes": clave/valor). */
final class Ajuste extends Modelo
{
    private static ?array $cache = null;

    public static function todos(bool $recargar = false): array
    {
        if (self::$cache === null || $recargar) {
            self::$cache = Config::get('app.ajustes_base');
            foreach (self::filas('SELECT clave, valor FROM ajustes') as $r) {
                self::$cache[$r['clave']] = (string) $r['valor'];
            }
        }
        return self::$cache;
    }

    public static function obtener(string $clave): string
    {
        return self::todos()[$clave] ?? '';
    }

    public static function guardar(string $clave, string $valor): void
    {
        self::consulta('INSERT INTO ajustes (clave, valor) VALUES (?, ?) ON DUPLICATE KEY UPDATE valor = VALUES(valor)', [$clave, $valor]);
        self::todos(true);
    }

    /** Métodos de pago que el dueño dejó activos. */
    public static function metodosPagoActivos(): array
    {
        $m = [];
        foreach (METODOS_PAGO as $clave => $nombre) {
            if (self::obtener('pago_' . $clave) === '1') {
                $m[$clave] = $nombre;
            }
        }
        return $m ?: ['contra_entrega' => METODOS_PAGO['contra_entrega']];
    }
}
