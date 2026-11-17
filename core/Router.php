<?php
declare(strict_types=1);

/**
 * Enrutador mínimo. Las rutas (routes/web.php) conservan los nombres de
 * página originales (principal.php, lista_ropa.php, admin/productos.php…)
 * para que los enlaces guardados y los banners del dueño sigan funcionando.
 */
final class Router
{
    private array $rutas = [];

    /**
     * @param array $accion  [Controlador::class, 'metodo']
     * @param string $acceso 'publico' | 'login' | 'admin'
     */
    public function ruta(string $ruta, array $accion, string $acceso = 'publico', bool $csrf = true): self
    {
        $this->rutas[trim($ruta, '/')] = compact('accion', 'acceso', 'csrf');
        return $this;
    }

    public function despachar(string $ruta): void
    {
        $r = $this->rutas[$ruta] ?? null;
        if ($r === null) {
            pagina_error('No encontramos esa página.', 404);
        }
        if ($r['acceso'] === 'admin') {
            Auth::requerirAdmin();
        } elseif ($r['acceso'] === 'login') {
            Auth::requerirLogin();
        }
        if (es_post() && $r['csrf']) {
            Sesion::verificarCsrf();
        }
        [$clase, $metodo] = $r['accion'];
        (new $clase())->$metodo();
    }
}
