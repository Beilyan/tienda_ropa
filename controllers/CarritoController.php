<?php
declare(strict_types=1);

/**
 * CARRITO: agregar, cambiar cantidades, quitar y aplicar cupón.
 * Los precios y el stock se leen siempre de la base de datos.
 */
final class CarritoController extends Controller
{
    public function manejar(): void
    {
        if ($this->esPost()) {
            $this->procesar();
        }

        $t = Carrito::totales();
        foreach ($t['avisos'] as $aviso) {
            $this->flash('info', $aviso);
        }
        $this->vista('tienda/carrito', ['titulo' => 'Carrito', 't' => $t]);
    }

    private function procesar(): never
    {
        $clave = (string) ($_POST['clave'] ?? '');

        switch ((string) ($_POST['accion'] ?? '')) {
            case 'agregar':
                $talla = (string) ($_POST['talla'] ?? '');
                if (!in_array($talla, TALLAS, true)) {
                    $this->flash('error', 'Elige una talla antes de agregar la prenda.');
                    $this->redirigir('producto.php?id=' . (int) ($_POST['producto_id'] ?? 0));
                }
                $err = Carrito::agregar((int) ($_POST['producto_id'] ?? 0), $talla, (int) ($_POST['cantidad'] ?? 1));
                $this->flash($err ? 'info' : 'ok', $err ?? 'Prenda agregada a tu carrito.');
                break;
            case 'actualizar':
                Carrito::actualizar($clave, (int) ($_POST['cantidad'] ?? 1));
                break;
            case 'quitar':
                if (Carrito::quitar($clave)) {
                    $this->flash('info', 'Quitamos la prenda de tu carrito.');
                }
                break;
            case 'cupon':
                $r = Carrito::aplicarCupon((string) ($_POST['codigo'] ?? ''));
                $this->flash($r['ok'] ? 'ok' : 'error', $r['ok'] ? '¡Cupón ' . $r['codigo'] . ' aplicado!' : $r['mensaje']);
                break;
            case 'quitar_cupon':
                Carrito::quitarCupon();
                $this->flash('info', 'Quitamos el cupón.');
                break;
        }
        $this->redirigir('carrito.php');
    }
}
