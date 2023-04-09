<?php
namespace App\Kernel;
use PDO;

/** database class */
class Db
{
    /** pdo object for db operations */
    private static $pdo = null;

    /** database type mysql or sqlite */
    private static $type = null;

    /** initializes mysql database */
    public static function initialize() : void
    {
        self::$pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
            DB_USER,
            DB_PASS
        );
        self::$type = 'mysql';
    }

    /** initializes test database sqlite memory */
    public static function initializeTest() : void
    {
        self::$pdo = new PDO('sqlite::memory:');
        self::$type = 'sqlite';        
    }

    /** drop table(s) */
    public static function dropTable($tables) : void
    {
        if (!is_array($tables)) {
            $tables = [$tables];
        }
        foreach ($tables as $table) {
            self::$pdo->query('DROP TABLE IF EXISTS ' . $table);
        }
    }

    /** create a new table */
    public static function createTable(string $name, array $columns) : void
    {
        $sql = 'CREATE TABLE IF NOT EXISTS ' . $name . ' (';

        foreach ($columns as $key => $column) {
            if (self::$type == 'sqlite') {
                $column = str_replace(
                    'AUTO_INCREMENT',
                    'AUTOINCREMENT',
                    $column
                );
                $column = str_replace('timestamp', 'datetime', $column);
                $column = str_replace('int(11)', 'integer', $column);
                $columns[$key] = $column;
            }
        }
        $sql .= implode(', ', $columns) . ')';
        self::$pdo->query($sql)->execute();
    }

    /** mysql query returns insert id */
    public static function insertQuery(string $query, array $prepared) : int|bool
    {
        // strip new lines
        $query = str_replace(["\r", "\n"], ' ', $query);
        $st = self::$pdo->prepare($query, $prepared);
        $st->execute($prepared);
        return self::$pdo->lastInsertId();
    }

    /** mysql query */
    public static function query(string $query, array $prepared=[]) : \PDOStatement
    {
        // strip new lines
        $query = str_replace(["\r", "\n"], ' ', $query);
        $st = self::$pdo->prepare($query, $prepared);
        $st->execute($prepared);
        return $st;
    }

    /**  fetch data */
    public static function fetch(\PDOStatement $state) : mixed
    {
        return $state->fetch(PDO::FETCH_OBJ);
    }

    /** fetch all data */
    public static function fetchAll(\PDOStatement $state) : mixed
    {
        return $state->fetchAll(PDO::FETCH_OBJ);
    }
}