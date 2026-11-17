<?php
declare(strict_types=1);

/** Base de los controladores del panel del dueño (el acceso lo exige el Router: ruta con acceso 'admin'). */
abstract class AdminController extends Controller
{
    /**
     * Muestra una pantalla del panel dentro del layout "admin".
     * @param array|null $accion botón de la cabecera: ['href' => ..., 'texto' => ..., 'estilo' => 'primario'|'secundario']
     */
    protected function panel(string $vista, array $datos, string $titulo, string $activo, string $subtitulo = '', ?array $accion = null): void
    {
        $this->vista('admin/' . $vista, $datos + compact('titulo', 'activo', 'subtitulo', 'accion'), 'admin');
    }

    /** Opción de un select/filtro permitida (o vacío). */
    protected function enLista(string $valor, array $permitidas): string
    {
        return in_array($valor, $permitidas, true) ? $valor : '';
    }
}
