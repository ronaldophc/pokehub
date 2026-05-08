<?php

namespace App\Enums;

enum MarketStatus: string
{
    case Active  = 'active';
    case Sold    = 'sold';
    case Expired = 'expired';
}
