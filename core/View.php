<?php
declare(strict_types=1);

/** Renderiza vistas (views/*.php) dentro de un layout (views/layouts/*.php). */
final class View
{
    public static function parcial(string $nombre, array $datos = []): string
    {
        $archivo = RAIZ . '/views/' . $nombre . '.php';
        if (!is_file($archivo)) {
            throw new RuntimeException("Vista no encontrada: $nombre");
        }
        extract($datos, EXTR_SKIP);
        ob_start();
        try {
            require $archivo;
        } catch (Throwable $ex) {
            ob_end_clean();
            throw $ex;
        }
        return (string) ob_get_clean();
    }

    /**
     * @param array $datos   variables que verá la vista
     * @param string|null $layout  nombre en views/layouts/ (null = sin layout)
     */
    public static function renderizar(string $vista, array $datos = [], ?string $layout = 'tienda'): void
    {
        $contenido = self::parcial($vista, $datos);
        if ($layout === null) {
            echo $contenido;
            return;
        }
        echo self::parcial('layouts/' . $layout, $datos + ['contenido' => $contenido]);
    }
}
