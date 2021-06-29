<?php


namespace Harvest\ChangeNote;


class ChangeNote extends Change
{
    public $type = Change::NOTE;

    public $name = 'Unknown';
    public $from = 'Unknown';
    public $to = 'Unknown';
}
