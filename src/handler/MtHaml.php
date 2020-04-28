<?php

namespace fize\view\handler;

use MtHaml\Environment;
use MtHaml\Support\Php\Executor;
use fize\view\ViewHandler;

/**
 * MtHaml
 *
 * composer require mthaml/mthaml
 */
class MtHaml implements ViewHandler
{

    /**
     * @var Executor MtHaml引擎
     */
    private $engine;

    /**
     * @var array 配置
     */
    private $config;

    /**
     * @var array 变量
     */
    private $assigns = [];

    /**
     * 初始化
     * @param array $config 配置
     */
    public function __construct($config = [])
    {
        $default = [
            'view'   => './view',
            'cache'  => './cache',
            'suffix' => 'haml'
        ];
        $config = array_merge($default, $config);
        $this->config = $config;
        $haml = new Environment('php');
        $this->engine = new Executor($haml, $this->config);
    }

    /**
     * 获取底部引擎对象
     * @return Executor
     */
    public function engine()
    {
        return $this->engine;
    }

    /**
     * 变量赋值
     * @param string $name  变量名
     * @param mixed  $value 变量
     */
    public function assign($name, $value)
    {
        $this->assigns[$name] = $value;
    }

    /**
     * 返回渲染内容
     * @param string $path    模板文件路径
     * @param array  $assigns 指定变量赋值
     * @return string
     */
    public function render($path, $assigns = [])
    {
        $path = $this->config['view'] . '/' . $path . '.' . $this->config['suffix'];
        if ($assigns) {
            foreach ($assigns as $name => $value) {
                $this->assign($name, $value);
            }
        }
        return $this->engine->render($path, $this->assigns);
    }
}
