## tp-repository扩展
thinkphp框架命令行创建Repository和Transform Class(即仓库层和转化器，基于controller-service-repository目录架构)





## 如何使用

- 1.安装扩展

  thinkphp5.*装1.0.1版本

  ```bash
  composer require jian1098/tp-repository:1.0.1
  ```

  thinkphp6以上装2.0.1版本

  ```bash
  composer require jian1098/tp-repository:2.0.1
  ```

- 2.注册命令

  - thinkphp5.*

    在`application/command.php`文件中添加两行

    ```php
    return [
        'Jian1098\TpRepository\Command\Repository',
        'Jian1098\TpRepository\Command\Transform',
        'Jian1098\TpRepository\Command\Model',  //如果是tp5.0框架，需要make:model命令可以增加这一行实现
    ];
    ```

  - thinkphp6+

    在`config/console.php`文件中添加两行

    ```php
    return [
        // 指令定义
        'commands' => [
            'Jian1098\TpRepository\Command\Repository',
            'Jian1098\TpRepository\Command\Transform',
        ],
    ];
    ```

  配置完后，在命令行执行`php think`命令，可以看到增加了`repository`和`make:transform`命令

    ```bash
      ...
      make:command      Create a new command class
      make:controller   Create a new resource controller class
      make:event        Create a new event class
      make:listener     Create a new listener class
      make:middleware   Create a new middleware class
      make:model        Create a new model class
      make:repository   Create a new repository class
      make:service      Create a new service class
      make:subscribe    Create a new subscribe class
      make:transform    Create a new transform class
      make:validate     Create a validate class
      ...
    ```

- 3.命令行创建文件

  ```bash
  # 创建repository
  php think make:repository TestRepository
  
  # 创建transform
  php think make:transform TestTransform
  ```

  执行上面的命令将创建文件`application/common/repository/TestRepository.php`（tp5）或 `app\repository\TestRepository`（tp6）
  
  TestRepository代码如下：
  
  ```php
  <?php
  
  namespace app\repository;
  
  use think\App;
  use app\model\Test;
  use Jian1098\TpRepository\Repository;
  
  /**
   * Class TestRepository
   */
  class TestRepository extends Repository
  {
      protected $model;
  
      public function __construct()
      {
          parent::__construct(new App());
          //绑定模型
          $this->model = new Test();
      }
  
  }
  ```
  
  TestTransform代码如下：
  
  ```php
  <?php
  
  namespace app\transform;
  
  use Jian1098\TpRepository\Command\Transform;
  
  class TestTransform extends Transform
  {
      public function transform($items)
      {
          return [
              'id'            => $items['id'],
              'createTime'    => $items['create_time'],
              'updateTime'    => $items['update_time'],
          ];
      }
  }
  ```
  
  在控制器中调用
  
  ```php
  <?php
  namespace app\controller;
  
  use app\BaseController;
  use app\repository\TestRepository;
  use app\transform\TestTransform;
  
  class Index extends BaseController
  {
      public function test(TestRepository $repository, TestTransform $transform)
      {
          //使用repository查询数据
          $data = $repository->first(1);
  
          //使用转换器将结果
          echo json_encode($transform->transform($data), JSON_UNESCAPED_UNICODE);
      }
  }
  ```
  
  



## 更多

[Thinkphp命令行创建Service(服务层)扩展包-CSDN博客](https://blog.csdn.net/C_jian/article/details/137814400?spm=1001.2014.3001.5501)

[Thinkphp5.0命令行创建验证器validate类-CSDN博客](https://blog.csdn.net/C_jian/article/details/137771056?spm=1001.2014.3001.5501)

[几个好用的Thinkphp第三方扩展-CSDN博客](https://blog.csdn.net/C_jian/article/details/137681152?spm=1001.2014.3001.5501)

更多扩展持续开发中...
