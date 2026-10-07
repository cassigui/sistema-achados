<?php

namespace App\Modules\ModuleGenerator\Templates\Factory;

use App\Modules\ModuleGenerator\Templates\ModuleTemplate;
use App\Modules\ModuleGenerator\Templates\TemplateInterface;

class FactoryTemplate extends ModuleTemplate implements TemplateInterface
{
    public function getType()
    {
        return 'factory';
    }

    public function getPath()
    {
        return parent::getPath();
    }

    public function getFileName()
    {
        return "{$this->name}Factory.php";
    }
}