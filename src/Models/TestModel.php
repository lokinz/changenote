<?php


namespace Harvest\Models;


use Harvest\ChangeNote\ChangeTypes;


class TestModel
{
    /**
     * @ChangeTypes\PropertyName(name="IP Address")
     * @ChangeTypes\Generic()
     */
    public $ip = '192.168.0.100';
}
