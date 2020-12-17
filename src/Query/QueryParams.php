<?php
/**
 * Created by
 * User: QueryParams
 * Date: 2020/12/16
 * Time: 17:09
 */

namespace Swoft\Pgsql\Query;

trait QueryParams
{
    /**
     * 查询字段
     * @var string
     */
    private $field='*';

    /**
     * 数据表名称
     * @var string
     */
    private $table;

    /**
     * @var array
     */
    private $paramWhere;

    /**
     * 排序支付串
     * @var string
     */
    private $orderStr;

    /**
     * 分组查询
     * @var string
     */
    private $groupStr;

    /**
     * 分页查询
     * @var string
     */
    private $pageStr;

    /**
     * 设置查询条件
     * @param $col
     * @return $this
     */
    public function select($col)
    {
        $this->field = $col;
        return $this;
    }

    /**
     * 设置表名称
     * @param string $name
     * @return $this
     */
    public function table(string $name)
    {
        $this->table = $name;
        return $this;
    }

    /**
     * @param $field
     * @param $operator      操作符
     * @param $value
     * @param string $connector 连接符
     * @return $this
     */
    public function where($field,$operator,$value,$connector='')
    {
        $tmpType = gettype($value);
        $_value = ($tmpType =='string') ? "'.$value.'": $value;
        $wherStr = $connector .' '. $field.' '.$operator.' '.$_value.' ';
        $this->setParams($wherStr);
        return $this;
    }

    /**
     * @param $field
     * @param $value
     * @return $this
     */
    public function like($field,$value,$connector='')
    {
        $wherStr = $connector ." ".$field." like '".$value."' ";
        $this->setParams($wherStr);
        return $this;
    }

    /**
     * @param $field
     * @param $value
     * @return $this
     */
    public function whereIn($field,$value,$connector='')
    {
        $wherStr = $connector.' '.$field.' in ('.$value.') ';
        $this->setParams($wherStr);
        return $this;
    }

    /**
     * @param $field
     * @param string $sort
     * @return $this
     */
    public function orderBy($field,$sort='DESC')
    {
        $this->orderStr = ' ORDER BY '.$field.' '.$sort;
        return $this;
    }

    /**
     * @param $field
     * @return $this
     */
    public function groupBy($field)
    {
        $this->groupStr = ' GROUP BY '.$field;
        return $this;
    }

    /**
     * @param int $page 当前页
     * @param int $size 没页总数
     */
    public function paginate($page=1,$size=10)
    {
        $limit = ($page-1) * $size;
        $this->pageStr = sprintf("limit %d offset %d",$size,$limit);
        return $this;
    }

    /**
     * 处理查询条件
     * @return bool|string
     */
    private function gatParams()
    {
        if(isset($this->paramWhere) && count($this->paramWhere) > 0){
            return implode('',$this->paramWhere);
        }else{
            return false;
        }
    }

    /**
     * 设置查询条件
     * @param null $value
     */
    private function setParams($value=null){
        if(is_null($value) || !isset($value) || empty($value)){
            $this->paramWhere = null;
        }
        if(is_array($this->paramWhere)){
            array_push($this->paramWhere,$value);
        }else{
            $this->paramWhere = array($value);
        }
    }

    /**
     * 构造查询语句
     * @return string
     * @throws \Exception
     */
    private function query()
    {
        try{
            $where = $this->gatParams();
            $sql = sprintf("select %s from %s",$this->field, $this->table);
            if($where){
                $sql .= ' WHERE '.$where;
            }
            if($this->groupStr != null){
                $sql .= $this->groupStr;
            }
            if($this->orderStr != null){
                $sql .= $this->orderStr;
            }
            if($this->pageStr != null){
                $sql .= $this->pageStr;
            }
            $sql .= ';';
            return $sql;
        }catch (\Exception $e){
            throw $e;
        }
    }
}