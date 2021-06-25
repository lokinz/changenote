<?php


namespace Harvest\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self IDLE()
 * @method static self PENDING()
 * @method static self DONE()
 * @method static self ERROR()
 */
final class SendStatus extends Enum
{
     const MAP_VALUE = [
            'IDLE' => 0,
            'PENDING' => 1,
            'DONE' => 2,
            'ERROR' => 3,
        ];
}
