<?php
namespace Jian1098\TpRepository\Command;

use think\console\command\Make;

class Transform extends Make
{
    protected $type = "Transform";

    protected function configure()
    {
        parent::configure();
        $this->setName('make:transform')
            ->setDescription('Create a new transform class');
    }

    protected function getStub()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'transform.stub';
    }

    protected function getNamespace($appNamespace, $module)
    {
        return parent::getNamespace($appNamespace, $module) . '\transform';
    }
}
