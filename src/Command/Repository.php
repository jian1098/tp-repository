<?php

namespace Jian1098\TpRepository\Command;

use think\App;
use think\console\command\Make;

class Repository extends Make
{
    protected $type = "Repository";

    protected function configure()
    {
        parent::configure();
        $this->setName('make:repository')
            ->setDescription('Create a new repository class');
    }

    protected function getStub()
    {
        $stubPath = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR;

        return $stubPath . 'repository.stub';
    }

    protected function getNamespace($appNamespace, $module)
    {
        return parent::getNamespace($appNamespace, $module) . '\repository';
    }

    protected function buildClass($name)
    {
        $stub = file_get_contents($this->getStub());

        $namespace = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');

        $class = str_replace($namespace . '\\', '', $name);

        $model = str_replace('Repository', '', $class);

        return str_replace(['{%className%}', '{%namespace%}', '{%modelName%}'], [
            $class,
            $namespace,
            $model,
        ], $stub);

    }
}
