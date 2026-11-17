<?php
declare(strict_types=1);

/** Catálogo: consultas de prendas, precio vigente y administración de productos. */
final class Producto extends Modelo
{
    /* Precio vigente: la oferta se apaga sola al llegar su fecha de fin */
    public const SQL_EN_OFERTA = "(p.precio_oferta IS NOT NULL AND p.precio_oferta < p.precio AND (p.oferta_hasta IS NULL OR p.oferta_hasta >= CURDATE()))";
    public const SQL_PRECIO_FINAL = "IF(" . self::SQL_EN_OFERTA . ", p.precio_oferta, p.precio)";
    public const SQL_STOCK = "(SELECT COALESCE(SUM(t.stock), 0) FROM producto_tallas t WHERE t.producto_id = p.id)";

    public static function sql(): string
    {
        return "SELECT p.*, c.nombre AS categoria_nombre, c.slug AS categoria_slug, c.icono AS icono,
                       " . self::SQL_PRECIO_FINAL . " AS precio_final,
                       " . self::SQL_EN_OFERTA . " AS en_oferta,
                       " . self::SQL_STOCK . " AS stock_total
                FROM productos p LEFT JOIN categorias c ON c.id = p.categoria_id";
    }

    /** Porcentaje de descuento visible (0 si no hay oferta). */
    public static function porcentajeDescuento(array $p): int
    {
        if (empty($p['en_oferta']) || (float) $p['precio'] <= 0) {
            return 0;
        }
        return (int) round((1 - (float) $p['precio_final'] / (float) $p['precio']) * 100);
    }

    /* ---------- Tienda ---------- */

    /** Convierte los filtros de la URL en condiciones SQL seguras. */
    private static function armarFiltros(array $f): array
    {
        $where = [];
        $params = [];
        if (empty($f['incluir_ocultos'])) {
            $where[] = 'p.activo = 1';
            $where[] = '(c.id IS NULL OR c.activa = 1)';
        }
        if (!empty($f['q'])) {
            foreach (preg_split('/\s+/', trim((string) $f['q'])) as $palabra) {
                if ($palabra === '') {
                    continue;
                }
                $like = '%' . addcslashes($palabra, '%_\\') . '%';
                $where[] = '(p.nombre LIKE ? OR p.descripcion LIKE ? OR c.nombre LIKE ? OR p.color_nombre LIKE ?)';
                array_push($params, $like, $like, $like, $like);
            }
        }
        if (!empty($f['categoria'])) {
            $where[] = 'c.slug = ?';
            $params[] = (string) $f['categoria'];
        }
        if (!empty($f['talla']) && in_array($f['talla'], TALLAS, true)) {
            $where[] = 'EXISTS (SELECT 1 FROM producto_tallas t WHERE t.producto_id = p.id AND t.talla = ? AND t.stock > 0)';
            $params[] = $f['talla'];
        }
        if (isset($f['min']) && $f['min'] !== '' && is_numeric($f['min'])) {
            $where[] = self::SQL_PRECIO_FINAL . ' >= ?';
            $params[] = (float) $f['min'];
        }
        if (isset($f['max']) && $f['max'] !== '' && is_numeric($f['max'])) {
            $where[] = self::SQL_PRECIO_FINAL . ' <= ?';
            $params[] = (float) $f['max'];
        }
        if (!empty($f['oferta'])) {
            $where[] = self::SQL_EN_OFERTA;
        }
        if (!empty($f['destacado'])) {
            $where[] = 'p.destacado = 1';
        }
        if (!empty($f['favoritos_de'])) {
            $where[] = 'p.id IN (SELECT producto_id FROM favoritos WHERE usuario_id = ?)';
            $params[] = (int) $f['favoritos_de'];
        }
        if (!empty($f['excluir'])) {
            $where[] = 'p.id <> ?';
            $params[] = (int) $f['excluir'];
        }
        return [$where ? ' WHERE ' . implode(' AND ', $where) : '', $params];
    }

    public static function buscar(array $f = []): array
    {
        [$where, $params] = self::armarFiltros($f);
        $orden = match ($f['orden'] ?? 'nuevos') {
            'precio_asc'  => 'precio_final ASC, p.id DESC',
            'precio_desc' => 'precio_final DESC, p.id DESC',
            'nombre'      => 'p.nombre ASC',
            default       => 'p.destacado DESC, p.creado_en DESC, p.id DESC',
        };
        if (($f['orden'] ?? '') === 'recientes') {
            $orden = 'p.creado_en DESC, p.id DESC';
        }
        $limite = isset($f['limite']) ? ' LIMIT ' . max(1, (int) $f['limite']) . ' OFFSET ' . max(0, (int) ($f['desde'] ?? 0)) : '';
        return self::filas(self::sql() . $where . ' ORDER BY ' . $orden . $limite, $params);
    }

    public static function contar(array $f = []): int
    {
        [$where, $params] = self::armarFiltros($f);
        return (int) self::valor('SELECT COUNT(*) FROM productos p LEFT JOIN categorias c ON c.id = p.categoria_id' . $where, $params);
    }

    public static function obtener(int $id, bool $incluirOcultos = false): ?array
    {
        $extra = $incluirOcultos ? '' : ' AND p.activo = 1 AND (c.id IS NULL OR c.activa = 1)';
        return self::fila(self::sql() . ' WHERE p.id = ?' . $extra, [$id]);
    }

    public static function tallas(int $idProducto): array
    {
        return self::filas("SELECT talla, stock FROM producto_tallas WHERE producto_id = ?
                            ORDER BY FIELD(talla, 'XS','S','M','L','XL','XXL','Única')", [$idProducto]);
    }

    /** Prendas parecidas para la ficha: misma categoría y, si faltan, las más recientes. */
    public static function relacionados(array $p, int $cantidad = 4): array
    {
        $id = (int) $p['id'];
        $rel = self::buscar(['categoria' => $p['categoria_slug'] ?? '', 'excluir' => $id, 'limite' => $cantidad, 'orden' => 'recientes']);
        if (count($rel) < $cantidad) {
            $extra = array_filter(self::buscar(['excluir' => $id, 'limite' => 8, 'orden' => 'recientes']),
                fn($x) => !in_array($x['id'], array_column($rel, 'id')));
            $rel = array_slice(array_merge($rel, $extra), 0, $cantidad);
        }
        return $rel;
    }

    /* ---------- Panel del dueño ---------- */

    /** Fila cruda de la tabla (sin precio vigente calculado). */
    public static function cruda(int $id): ?array
    {
        return self::fila('SELECT * FROM productos WHERE id = ?', [$id]);
    }

    private static function condicionAdmin(array $f): array
    {
        $where = [];
        $params = [];
        if (($f['q'] ?? '') !== '') {
            $where[] = '(p.nombre LIKE ? OR c.nombre LIKE ?)';
            $like = '%' . addcslashes($f['q'], '%_\\') . '%';
            array_push($params, $like, $like);
        }
        if ((int) ($f['categoria'] ?? 0) > 0) {
            $where[] = 'p.categoria_id = ?';
            $params[] = (int) $f['categoria'];
        }
        $where[] = match ($f['estado'] ?? '') {
            'activos' => 'p.activo = 1',
            'ocultos' => 'p.activo = 0',
            'agotados' => self::SQL_STOCK . ' <= 0',
            'ofertas' => self::SQL_EN_OFERTA,
            'vencidas' => 'p.precio_oferta IS NOT NULL AND p.oferta_hasta < CURDATE()',
            default => '1 = 1',
        };
        return [' WHERE ' . implode(' AND ', $where), $params];
    }

    public static function contarAdmin(array $f): int
    {
        [$cond, $params] = self::condicionAdmin($f);
        return (int) self::valor('SELECT COUNT(*) FROM productos p LEFT JOIN categorias c ON c.id = p.categoria_id' . $cond, $params);
    }

    public static function listarAdmin(array $f, int $limite, int $desde): array
    {
        [$cond, $params] = self::condicionAdmin($f);
        return self::filas(self::sql() . $cond . ' ORDER BY p.actualizado_en DESC, p.id DESC LIMIT ' . max(1, $limite) . ' OFFSET ' . max(0, $desde), $params);
    }

    public static function guardarRapido(int $id, float $precio, ?float $oferta, ?string $hasta, bool $activo, bool $destacado): void
    {
        self::consulta('UPDATE productos SET precio = ?, precio_oferta = ?, oferta_hasta = ?, activo = ?, destacado = ? WHERE id = ?',
            [$precio, $oferta, $hasta, $activo ? 1 : 0, $destacado ? 1 : 0, $id]);
    }

    public static function limpiarOferta(int $id): void
    {
        self::consulta('UPDATE productos SET precio_oferta = NULL, oferta_hasta = NULL WHERE id = ?', [$id]);
    }

    public static function eliminar(int $id): void
    {
        self::consulta('DELETE FROM productos WHERE id = ?', [$id]);
    }

    /**
     * Crea (o actualiza si $id > 0) una prenda con sus tallas, todo en una transacción.
     * $d: categoria_id, nombre, descripcion, precio, precio_oferta, oferta_hasta, color_nombre,
     *     color_hex, imagen, activo, destacado.  $stock: [talla => '' | número]
     * Tallas: vacío = no se ofrece; 0 = talla agotada.
     */
    public static function guardar(int $id, array $d, array $stock): int
    {
        return (int) Database::transaccion(function () use ($id, $d, $stock): int {
            if ($id > 0) {
                self::consulta('UPDATE productos SET categoria_id=?, nombre=?, descripcion=?, precio=?, precio_oferta=?, oferta_hasta=?, color_nombre=?, color_hex=?, imagen=?, activo=?, destacado=? WHERE id=?',
                    [$d['categoria_id'], $d['nombre'], $d['descripcion'], $d['precio'], $d['precio_oferta'], $d['oferta_hasta'], $d['color_nombre'], $d['color_hex'], $d['imagen'], $d['activo'], $d['destacado'], $id]);
            } else {
                self::consulta('INSERT INTO productos (categoria_id, nombre, slug, descripcion, precio, precio_oferta, oferta_hasta, color_nombre, color_hex, imagen, activo, destacado)
                                VALUES (?,?,?,?,?,?,?,?,?,?,?,?)',
                    [$d['categoria_id'], $d['nombre'], self::slugUnico('productos', $d['nombre']), $d['descripcion'], $d['precio'], $d['precio_oferta'], $d['oferta_hasta'], $d['color_nombre'], $d['color_hex'], $d['imagen'], $d['activo'], $d['destacado']]);
                $id = Database::ultimoId();
            }
            foreach ($stock as $t => $n) {
                if ($n === '') {
                    self::consulta('DELETE FROM producto_tallas WHERE producto_id = ? AND talla = ?', [$id, $t]);
                } else {
                    self::consulta('INSERT INTO producto_tallas (producto_id, talla, stock) VALUES (?,?,?) ON DUPLICATE KEY UPDATE stock = VALUES(stock)', [$id, $t, (int) $n]);
                }
            }
            return $id;
        });
    }
}
