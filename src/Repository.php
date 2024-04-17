<?php
namespace Jian1098\TpRepository;

use think\App;
use think\Model;

abstract class Repository
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var
     */
    protected $model;

    /**
     * Repository constructor.
     * @param App $app
     * @throws \HttpRequestPoolException
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * 查找单条记录
     * @access public
     * @param mixed $id 查询数据
     */
    public function first($id)
    {
        $result = $this->model->find($id);

        return $result;
    }

    /**
     * 查找记录
     * @access public
     * @param mixed $data 数据
     */
    public function get($data = null)
    {
        $result = $this->model->select($data);

        return $result;
    }

    /**
     * 分页查询
     * @access public
     * @param int|array $listRows 每页数量 数组表示配置参数
     * @param int|bool  $simple   是否简洁模式或者总记录数
     */
    public function paginate($perPage = 10, $simple = false)
    {
        if (is_int($simple)) {
            $total  = $simple;
            $simple = false;
        }

        $result = $this->model->paginate($perPage, $simple);

        return $result;
    }

    /**
     * 写入数据
     * @access public
     * @param array  $data       数据数组
     * @param array  $allowField 允许字段
     * @param bool   $replace    使用Replace
     * @param string $suffix     数据表后缀
     * @return static
     */
    public function create(array $data, array $allowField = [], bool $replace = false, string $suffix = '')
    {
        $result = $this->model->create($data);

        return $result;
    }

    /**
     * 保存当前数据对象
     * @access public
     * @param array  $data     数据
     * @param string $sequence 自增序列名
     * @return bool
     */
    public function save(array $data = [], string $sequence = null)
    {
        $this->model->save($data, $sequence);
    }

    /**
     * 删除记录
     * @param $where
     * @return mixed
     */
    public function delete($where)
    {
        $where = $this->makeWhere($where);

        $data = $this->model->where($where)->delete();

        return $data;
    }

    /**
     * 更新记录
     * @access public
     * @param mixed $data 数据
     * @return integer
     * @throws Exception
     */
    public function update($where, array $data = [])
    {
        $where = $this->makeWhere($where);

        $data = $this->model->where($where)->update($data);

        return $data;
    }

    /**
     * 查找单条记录 如果不存在则抛出异常
     * @access public
     * @param mixed $data 查询数据
     * @return array|Model|null
     */
    public function find($id)
    {
        $model = $this->model->findOrFail($id);

        return $model;
    }

    /**
     * 指定AND查询条件 查找单条记录
     * @param $where
     * @param array $columns
     * @return mixed
     */
    public function findWhere($where, $columns = ['*'])
    {
        $where = $this->makeWhere($where);

        $model = $this->model->field($columns)->where($where)->find();

        return $model;
    }

    /**
     * 按字段和值查找数据
     * @param $field
     * @param null $value
     * @return mixed
     */
    public function findByField($field, $value = null, $columns = ['*'])
    {
        $model = $this->model->field($columns)->where($field, '=', $value)->select();
        $this->resetModel();

        return $model;
    }

    /**
     * WHEREIN查找数据
     * @param $field
     * @param array $values
     * @param array $columns
     * @return mixed
     */
    public function findWhereIn($field, array $values, $columns = ['*'])
    {
        $model = $this->model->field($columns)->whereIn($field, $values)->select();

        return $model;
    }

    /**
     * WHERE NOT IN查找数据
     * @param $field
     * @param array $values
     * @param array $columns
     * @return mixed
     */
    public function findWhereNotIn($field, array $values, $columns = ['*'])
    {
        $model = $this->model->field($columns)->whereNotIn($field, $values)->select();

        return $model;
    }

    /**
     * 区域查询
     * @param $field
     * @param array $values
     * @param array $columns
     * @return mixed
     */
    public function findWhereBetween($field, array $values, $columns = ['*'])
    {
        $model = $this->model->field($columns)->whereBetween($field, $values)->select();

        return $model;
    }

    /**
     * 按多个字段删除存储库中的条目
     * @param array $where
     * @return mixed
     */
    public function deleteWhere(array $where)
    {
        $model = $this->model->where($where)->delete();

        return $model;
    }

    /**
     * 关联查询
     * @param array $relations
     * @return $this
     */
    public function with(array $relations)
    {
        $this->model = $this->model->with($relations);

        return $this;
    }


    /**
     * 数据搜索
     * @param array $keyword
     * @return $this
     */
    public function search(array $keyword)
    {
        $this->model = $this->model->withSearch(array_keys(array_filter($keyword, function ($keyword){
            if($keyword === '' || $keyword === null){
                return false;
            }
            return true;
        })), array_filter($keyword, function ($keyword){
            if($keyword === '' || $keyword === null){
                return false;
            }
            return true;
        }));;

        return $this;
    }

    /**
     * 排序
     * @param $order
     * @return $this
     */
    public function order($order)
    {
        if (is_array($order)) {
            $query = $this->model->order($order);
        } else {
            $query = $this->model->order('id', $order);
        }

        $this->model = $query;

        return $this;
    }



    /**
     * @param $where
     * @return array
     */
    public function makeWhere($where)
    {
        if (is_array($where)) {
            $where = $where;
        } else {
            $where = [
                [
                    'id', '=', $where
                ]
            ];
        }

        return $where;
    }
}