<?php
declare(strict_types=1);

/** CUPONES DE DESCUENTO: crear, programar, pausar y borrar. */
final class AdminCuponController extends AdminController
{
    public function index(): void
    {
        $errores = [];
        $editarId = (int) ($_GET['editar'] ?? $_POST['id'] ?? 0);
        $nuevo = isset($_GET['nuevo']);
        $c = $editarId ? Cupon::porId($editarId) : null;
        $v = [
            'codigo' => $c['codigo'] ?? '', 'tipo' => $c['tipo'] ?? 'porcentaje', 'valor' => $c['valor'] ?? '',
            'minimo_compra' => $c['minimo_compra'] ?? '0', 'usos_max' => $c['usos_max'] ?? '', 'inicio' => $c['inicio'] ?? '',
            'fin' => $c['fin'] ?? '', 'activo' => $c ? (int) $c['activo'] : 1,
        ];

        if ($this->esPost()) {
            $accion = (string) ($_POST['accion'] ?? '');
            $id = (int) ($_POST['id'] ?? 0);

            if ($accion === 'eliminar' && Cupon::porId($id)) {
                Cupon::eliminar($id);
                $this->flash('ok', 'Eliminamos el cupón.');
                $this->redirigir('admin/cupones.php');
            }
            if ($accion === 'alternar' && ($x = Cupon::porId($id))) {
                Cupon::alternar($id);
                $this->flash('ok', $x['activo'] ? 'Pausamos el cupón.' : 'El cupón vuelve a estar activo.');
                $this->redirigir('admin/cupones.php');
            }
            if ($accion === 'guardar') {
                $v['codigo'] = strtoupper(preg_replace('/[^A-Za-z0-9_-]/', '', (string) ($_POST['codigo'] ?? '')));
                $v['tipo'] = ($_POST['tipo'] ?? '') === 'monto' ? 'monto' : 'porcentaje';
                $v['valor'] = $this->texto($_POST, 'valor');
                $v['minimo_compra'] = $this->texto($_POST, 'minimo_compra', '0');
                $v['usos_max'] = $this->texto($_POST, 'usos_max');
                $v['inicio'] = $this->texto($_POST, 'inicio');
                $v['fin'] = $this->texto($_POST, 'fin');
                $v['activo'] = isset($_POST['activo']) ? 1 : 0;

                $valor = numero_o_null($v['valor']);
                $min = numero_o_null($v['minimo_compra']) ?? 0.0;
                $usos = $v['usos_max'] === '' ? null : (ctype_digit($v['usos_max']) ? (int) $v['usos_max'] : -1);
                $ini = fecha_o_null($v['inicio']);
                $fin = fecha_o_null($v['fin']);

                if (strlen($v['codigo']) < 3) $errores['codigo'] = 'Usa al menos 3 letras o números, sin espacios.';
                elseif (Cupon::existeCodigo($v['codigo'], (int) ($c['id'] ?? 0))) $errores['codigo'] = 'Ya existe un cupón con ese código.';
                if ($valor === null || $valor <= 0) $errores['valor'] = 'Escribe un descuento mayor a 0.';
                elseif ($v['tipo'] === 'porcentaje' && $valor > 100) $errores['valor'] = 'El porcentaje no puede pasar de 100.';
                if ($min < 0) $errores['minimo_compra'] = 'No puede ser negativo.';
                if ($usos === -1 || $usos === 0) $errores['usos_max'] = 'Escribe un número mayor a 0 o déjalo vacío.';
                if ($v['inicio'] !== '' && !$ini) $errores['inicio'] = 'Fecha no válida.';
                if ($v['fin'] !== '' && !$fin) $errores['fin'] = 'Fecha no válida.';
                if ($ini && $fin && $fin < $ini) $errores['fin'] = 'Debe ser posterior al inicio.';

                if (!$errores) {
                    $datos = ['codigo' => $v['codigo'], 'tipo' => $v['tipo'], 'valor' => $valor, 'minimo_compra' => $min,
                              'usos_max' => $usos, 'inicio' => $ini, 'fin' => $fin, 'activo' => $v['activo']];
                    if ($c) {
                        Cupon::actualizar((int) $c['id'], $datos);
                        $this->flash('ok', "Guardamos el cupón {$v['codigo']}.");
                    } else {
                        Cupon::crear($datos);
                        $this->flash('ok', "Creamos el cupón {$v['codigo']}. Ya se puede usar en el carrito.");
                    }
                    $this->redirigir('admin/cupones.php');
                }
                $nuevo = !$c;
            }
        }

        $cupones = Cupon::todos();
        $mostrarForm = $nuevo || $c;
        $this->panel('cupones', compact('cupones', 'c', 'v', 'errores', 'mostrarForm'), 'Cupones', 'cupones', 'Promociones',
            $mostrarForm ? null : ['href' => 'admin/cupones.php?nuevo=1', 'texto' => '+ Nuevo cupón']);
    }
}
