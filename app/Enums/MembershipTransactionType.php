<?php

namespace App\Enums;

enum MembershipTransactionType: string
{
    case Registration = 'registration';
    case Renewal = 'renewal';

    public function label(): string
    {
        return match ($this) {
            self::Registration => 'Member Baru',
            self::Renewal => 'Perpanjangan',
        };
    }
}
