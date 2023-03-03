<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ClientStatus extends Enum
{
    const Canceled = 0;
    const Active = 1;
    const OnHold = 2;
}
