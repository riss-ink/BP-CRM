<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TermType extends Enum
{
    const OneTime = 0;
    const Monthly = 1;
    const Hourly = 2;
    const Indefinite = 3;

}
