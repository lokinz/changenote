<?php


namespace Harvest\Models;


use Harvest\ChangeNote\Annotations\Generic;
use Harvest\ChangeNote\Annotations\PropertyName;

class TestModel
{
    /**
     * @PropertyName(name="IP Address")
     * @Generic()
     */
    public $ip = '192.168.0.100';
}
