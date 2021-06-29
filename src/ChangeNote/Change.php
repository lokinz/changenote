<?php


namespace Harvest\ChangeNote;


abstract class Change
{
    public const NOTE = 'note';
    public const COLLECTION = 'collection';

    public $type;
}
