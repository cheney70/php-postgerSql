<?php
/**
 * Created by
 * User: DB
 * Date: 2020/12/17
 * Time: 14:56
 */

namespace Cheney\Swoft\Pgsql\Db;

use Illuminate\Database\Capsule\Manager;

/**
 * Class DB
 * DB::init(function($database){
 * $database->addConnection([
 * 'driver'    => 'mysql',
 * 'host'      => '127.0.0.1',
 * 'database'  => 'laravel',
 * 'username'  => 'root',
 * 'password'  => 'root',
 * 'charset'   => 'utf8',
 * 'collation' => 'utf8_unicode_ci',
 * 'prefix'    => ''
 * ]);
 * // 只用于开发调试，生产模式不需要，此操作会导致内存泄漏。
 * $database->getConnection()->enableQueryLog();
 * $database->bootEloquent();
})
 */
class DB
{
    private static $instance;

    public static function init(\Closure $callback = null)
    {
        $database = new Manager();
        $callback($database);
        self::$instance = $database->getDatabaseManager();
    }

    public static function getInstance()
    {
        return self::$instance;
    }

    public static function __callStatic($method, $args)
    {
        $instance = self::getInstance();

        return $instance->$method(...$args);
    }
}