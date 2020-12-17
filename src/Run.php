<?php declare(strict_types=1);
/**
 * Created by
 * User: Run
 * Date: 2020/12/17
 * Time: 9:12
 */

use Cheney\Swoft\Pgsql\Model\DB;
/**
 * Class Run
 *
 * @since 2.0
 */
class Run
{
    public function __construct($beanName='pgsql')
    {
        $pgsqlDb = bean($beanName);
        parent::__construct($pgsqlDb->getDatabase(),
            $pgsqlDb->getUser(),
            $pgsqlDb->getPassword(),
            $pgsqlDb->getHost(),
            $pgsqlDb->getPort()
        );

        DB::init(function($database) use ($pgsqlDb) {

            $prefix = $pgsqlDb->getPrefix();

            $database->addConnection([
                'driver'   => 'pgsql',
                'host'     => $pgsqlDb->getHost(),
                'port'     => $pgsqlDb->getPort(),
                'database' => $pgsqlDb->getDatabase(),
                'username' => $pgsqlDb->getUser(),
                'password' => $pgsqlDb->getPassword(),
                'charset'  => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => isset($pgsqlDb->getPrefix()) ? $pgsqlDb->getpprefix() : '';
            ]);

            // 只用于开发调试，生产模式不需要，此操作会导致内存泄漏。
            //$database->getConnection()->enableQueryLog();
            $database->bootEloquent();
        });
    }
}