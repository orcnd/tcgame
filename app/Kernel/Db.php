<?php
namespace App\Kernel;
use PDO;

class Db
{
    private static $pdo = null;
    private static $type = null;
    public static function initialize()
    {
        self::$pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
            DB_USER,
            DB_PASS
        );
        self::$type = 'mysql';
    }

    public static function initializeTest()
    {
        self::$pdo = new PDO('sqlite::memory:');
        self::$type = 'sqlite';
    }

    /**
     * drop table(s)
     *
     * @param array|string $tables table or tables
     *
     * @return void
     */
    public static function dropTable($tables)
    {
        if (!is_array($tables)) {
            $tables = [$tables];
        }
        foreach ($tables as $table) {
            self::$pdo->query('DROP TABLE IF EXISTS ' . $table);
        }
    }

    /**
     * create a new table
     */
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

    /**
     * mysql query
     *
     * @param string $query
     *
     * @return mixed
     */
    public static function insertQuery(string $query, array $prepared) : int|bool
    {
        // strip new lines
        $query = str_replace(["\r", "\n"], ' ', $query);
        $st = self::$pdo->prepare($query, $prepared);
        $st->execute($prepared);
        return self::$pdo->lastInsertId();
    }

    /**
     * mysql query
     *
     * @param string $query
     *
     * @return mixed
     */
    public static function query(string $query, array $prepared)
    {
        // strip new lines
        $query = str_replace(["\r", "\n"], ' ', $query);
        $st = self::$pdo->prepare($query, $prepared);
        $st->execute($prepared);
        return $st;
    }

    /**
     * fetch data
     *
     * @param \PDOStatement $state
     *
     * @return mixed
     */
    public static function fetch(\PDOStatement $state)
    {
        return $state->fetch(PDO::FETCH_OBJ);
    }

    /**
     * fetch all data
     *
     * @param \PDOStatement $state
     *
     * @return mixed
     */
    public static function fetchAll(\PDOStatement $state)
    {
        return $state->fetchAll(PDO::FETCH_OBJ);
    }
}