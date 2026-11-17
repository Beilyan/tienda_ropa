<?php
declare(strict_types=1);

/** Conexión única a MySQL (PDO con consultas preparadas). */
final class Database
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }
        $c = Config::cargar('database');
        if (!$c) {
            pagina_error('La tienda aún no está instalada.');
        }
        try {
            self::$pdo = new PDO(
                "mysql:host={$c['host']};port={$c['puerto']};dbname={$c['nombre']};charset=utf8mb4",
                $c['usuario'],
                $c['clave'],
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $ex) {
            error_log('Conexión a la base de datos: ' . $ex->getMessage());
            pagina_error('No se pudo conectar a la base de datos. Revisa que MySQL esté encendido en AMPPS.');
        }
        return self::$pdo;
    }

    public static function consulta(string $sql, array $params = []): PDOStatement
    {
        $st = self::pdo()->prepare($sql);
        $st->execute($params);
        return $st;
    }

    public static function fila(string $sql, array $params = []): ?array
    {
        $r = self::consulta($sql, $params)->fetch();
        return $r === false ? null : $r;
    }

    public static function filas(string $sql, array $params = []): array
    {
        return self::consulta($sql, $params)->fetchAll();
    }

    public static function valor(string $sql, array $params = []): mixed
    {
        $r = self::consulta($sql, $params)->fetchColumn();
        return $r === false ? null : $r;
    }

    public static function ultimoId(): int
    {
        return (int) self::pdo()->lastInsertId();
    }

    /** Ejecuta $accion dentro de una transacción; si lanza una excepción, deshace todo. */
    public static function transaccion(callable $accion): mixed
    {
        $pdo = self::pdo();
        $pdo->beginTransaction();
        try {
            $r = $accion();
            $pdo->commit();
            return $r;
        } catch (Throwable $ex) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $ex;
        }
    }
}
