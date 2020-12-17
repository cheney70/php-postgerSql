<?php
/**
 * Created by
 * User: Cheney
 * Date: 2020/12/16
 * Time: 10:11
 */

namespace Swoft\Pgsql\Query;

use Cheney\Pgsql\Exception\PgsqlException;
use function pg_connect;
use function pg_fetch_all;
use function pg_fetch_assoc;
use function pg_query;
use function pg_insert;
use function pg_update;
use function pg_conver;
use function pg_fetch_object;
use function pg_free_result;
use function pg_close;

class Pgsql
{
    use QueryParams;
    /**
     * 链接字符串
     * @var string
     */
    private $dsn;
    /**
     * 链接标识
     * @var string
     */
    private $conn;

    public function __construct($dbName,$user,$password,$host='127.0.0.1',$port=5432)
    {
        $dsn = sprintf("host=%s port=%d dbname=%s user=%s password=%s",
            $host,
            $port,
            $dbName,
            $user,
            $password
        );
        $this->dsn = $dsn;
    }

    /**
     * connection
     * @return $this
     * @throws PgsqlException
     */
    public function connect()
    {
        try{
            $this->conn = pg_connect($this->dsn);
            return $this;
        }catch (PgsqlException $e){
            throw new PgsqlException("connection failed");
        }
    }

    /**
     * @return $this
     * @throws PgsqlException
     */
    public function pconnect()
    {
        try{
            $this->conn = pg_pconnect($this->dsn);
            return $this;
        }catch (PgsqlException $e){
            throw new PgsqlException("connection failed");
        }
    }

    /**
     * Reconnect to the database if a connection is missing.
     *
     * @return void
     */
    protected function reconnect()
    {
        if (is_null($this->conn) || !$this->conn) {
            $this->connect();
        }
    }

    /**
     * 关闭连接
     */
    protected function pgClose(){
        pg_close($this->conn);
    }

    /**
     * 恢复初始值
     */
    private function clear(){
        //查询完成重置结果
        $this->field = '*';
        $this->table    = null;
        $this->orderStr = null;
        $this->groupStr = null;
        $this->pageStr  = null;
        $this->paramWhere = [];
        return $this;
    }

    /**
     * 查询列表
     * @return array
     * @throws PgsqlException
     */
    public function get(){
        try{
            $this->reconnect();
            $result = pg_query($this->conn, $this->query());
            $data = pg_fetch_all($result);
            //释放结果内存
            pg_free_result($result);
            $this->clear();
            return $data;
        }catch (PgsqlException $e){
            throw $e;
        }
    }

    /**
     * 查询单条记录
     * @return array|null
     * @throws PgsqlException
     */
    public function find()
    {
        try{
            $this->reconnect();
            $result = pg_query($this->conn, $this->query());
            $data = pg_fetch_assoc($result);
            //释放结果内存
            pg_free_result($result);
            $this->clear();
            return $data ? $data : null;
        }catch (PgsqlException $e){
            throw $e;
        }
    }

    /**
     * @param $query
     * @return array
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function insert(array $data): array
    {
        try{
            $this->reconnect();
            $result = pg_insert($this->conn, $this->table, $data);
            $this->pgClose();
            return $result;
        }catch (\Exception $e){
            throw $e;
        }
    }

    /**
     * @param $table
     * @param $updateData
     * @param $where
     */
    public function update(array $updateData,array $where)
    {
        try{
            $this->reconnect();
            $result = pg_update($this->client, $this->table, $updateData, $where);
            $this->pgClose();
            return ($result === false) ? [] : $result;
        }catch (\Exception $e){
            throw $e;
        }
    }

    /**
     * @param array $where
     * @throws PgsqlException
     */
    public function delete(array $where)
    {
        try{
            $this->reconnect();
            $result = pg_delete($this->client,$this->table,$where);
            return $result;
        }catch (\Exception $e){
            throw $e;
        }
    }
}