<?php declare(strict_types=1);
/**
 * Created by
 * User: PDOdb
 * Date: 2020/12/17
 * Time: 9:12
 */

namespace Swoft\Pgsql;
use Swoft\Pgsql\Pdo\PdoPgsql;
/**
 * Class PDOdb
 *
 * @since 2.0
 */
class PDOdb extends PdoPgsql
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
    }
}