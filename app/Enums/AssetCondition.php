<?php

namespace App\Enums;

enum AssetCondition: string
{
    case Excellent = 'excellent';
    case Good = 'good';
    case Fair = 'fair';
    case Poor = 'poor';
    case Broken = 'broken';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
