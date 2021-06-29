<?php


namespace Harvest\Tests\unit\ChangeNote\ChangeTypes\Fixtures;


use Spatie\Enum\Enum;

/**
 * @method static self IDLE()
 * @method static self PENDING()
 * @method static self DONE()
 * @method static self ERROR()
 */
final class TestEnum extends Enum
{
    const MAP_VALUE = [
        'IDLE' => 0,
        'PENDING' => 1,
        'DONE' => 2,
        'ERROR' => 3,
    ];
}
