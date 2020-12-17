<?php
namespace PgsqlTest\Testing;
/**
 * Created by
 * User: ${NAME}
 * Date: 2020/12/16
 * Time: 10:47
 */

require_once __DIR__."/../vendor/autoload.php";
use Cheney\Swoft\Pgsql\Model\DB;
use Cheney\Swoft\Pgsql\Model\BaseModel;

DB::init(function($database) {
    $database->addConnection([
        'driver'   => 'pgsql',
        'host'     => '172.16.200.58',
        'port'     => 5432,
        'database' => 'postgres',
        'username' => 'postgres',
        'password' => '',
        'charset'  => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => ''
    ]);

    // 只用于开发调试，生产模式不需要，此操作会导致内存泄漏。
    $database->getConnection()->enableQueryLog();
    $database->bootEloquent();
});

$res = DB::table('users_tbl')->get();
$res = BaseModel::query()->get();
var_dump($res->toArray());