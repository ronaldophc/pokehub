<?php

namespace App\Enums;

enum MarketOfferStatus: string
{
    case Pending  = 'pending';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
}
