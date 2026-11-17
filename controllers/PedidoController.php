<?php
declare(strict_types=1);

/**
 * PEDIDOS DEL CLIENTE: finalizar compra, confirmación e historial.
 * Esta tienda NO cobra con tarjeta en línea: el pago se acuerda según el método
 * que el dueño active (contra entrega, transferencia, WhatsApp).
 */
final class PedidoController extends Controller
{
    /** Finalizar pedido: datos de entrega y forma de pago. */
    public function checkout(): void
    {
        $t = Carrito::totales();
        if (!$t['lineas']) {
            $this->flash('info', 'Tu carrito está vacío.');
            $this->redirigir('carrito.php');
        }
        $u = Auth::usuario();
        $metodos = Ajuste::metodosPagoActivos();
        $v = [
            'nombre' => $u['nombre'] ?? '', 'email' => $u['email'] ?? '', 'telefono' => $u['telefono'] ?? '',
            'direccion' => '', 'ciudad' => '', 'cp' => '', 'notas' => '', 'metodo' => array_key_first($metodos),
        ];
        $errores = [];

        if ($this->esPost()) {
            foreach ($v as $k => $_) {
                $v[$k] = trim((string) ($_POST[$k] ?? ''));
            }
            if (mb_strlen($v['nombre']) < 3) $errores['nombre'] = 'Escribe tu nombre completo.';
            if (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) $errores['email'] = 'Escribe un correo válido.';
            if (strlen(solo_digitos($v['telefono'])) < 8) $errores['telefono'] = 'Escribe un teléfono con al menos 8 dígitos.';
            if (mb_strlen($v['direccion']) < 8) $errores['direccion'] = 'Escribe calle, número y colonia.';
            if (mb_strlen($v['ciudad']) < 2) $errores['ciudad'] = 'Escribe tu ciudad.';
            if (strlen(solo_digitos($v['cp'])) < 4) $errores['cp'] = 'Escribe tu código postal.';
            if (!isset($metodos[$v['metodo']])) $errores['metodo'] = 'Elige cómo quieres pagar.';
            $v['notas'] = mb_substr($v['notas'], 0, 500);

            if (!$errores) {
                try {
                    $codigo = Pedido::crear($v, $t, $u);
                    Carrito::vaciar();
                    $_SESSION['ultimo_pedido'] = $codigo;
                    $this->redirigir('gracias.php?c=' . urlencode($codigo));
                } catch (RuntimeException $ex) {
                    $this->flash('error', $ex->getMessage());
                    $this->redirigir('carrito.php');
                } catch (Throwable $ex) {
                    error_log('Error al crear pedido: ' . $ex->getMessage());
                    $this->flash('error', 'No pudimos registrar tu pedido. Inténtalo de nuevo en un momento.');
                    $this->redirigir('checkout.php');
                }
            }
        }

        $this->vista('tienda/checkout', [
            'titulo' => 'Finalizar pedido', 't' => $t, 'v' => $v, 'errores' => $errores, 'metodos' => $metodos,
            'descripciones' => [
                'contra_entrega' => 'Pagas en efectivo cuando recibes tu pedido.',
                'transferencia'  => 'Al confirmar verás los datos para hacer tu transferencia.',
                'whatsapp'       => 'Coordinamos el pago y la entrega contigo por WhatsApp.',
            ],
        ]);
    }

    /** Confirmación: solo la persona que hizo el pedido (o el dueño) puede verla. */
    public function gracias(): void
    {
        $pedido = Pedido::porCodigo((string) ($_GET['c'] ?? ''));
        $u = Auth::usuario();
        $permitido = $pedido && (
            ($_SESSION['ultimo_pedido'] ?? '') === $pedido['codigo']
            || ($u && ((int) $pedido['usuario_id'] === (int) $u['id'] || $u['rol'] === 'admin'))
        );
        if (!$permitido) {
            $this->redirigir('principal.php');
        }
        $wa = solo_digitos(aj('whatsapp'));
        $this->vista('tienda/gracias', [
            'titulo'    => 'Pedido recibido',
            'pedido'    => $pedido,
            'items'     => Pedido::items((int) $pedido['id']),
            'u'         => $u,
            'wa'        => $wa,
            'mensajeWa' => "Hola, acabo de hacer el pedido {$pedido['codigo']} por " . dinero($pedido['total']) . ". Mi nombre es {$pedido['nombre']}.",
        ]);
    }

    /** Historial y estado de cada compra del cliente. */
    public function misPedidos(): void
    {
        $u = Auth::usuario();
        $this->vista('tienda/mis_pedidos', ['titulo' => 'Mis pedidos', 'pedidos' => Pedido::delUsuario((int) $u['id'])]);
    }
}
