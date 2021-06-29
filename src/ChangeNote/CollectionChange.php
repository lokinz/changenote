<?php


namespace Harvest\ChangeNote;


class CollectionChange extends Change
{
    public $type = Change::COLLECTION;

    public $name;
    public $added = [];
    public $removed = [];
    public $changes = [];
}
