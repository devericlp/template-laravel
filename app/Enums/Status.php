<?php

namespace App\Enums;

enum Status: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;
    case BLOCKED = 2;
    case PENDING = 3;

    public function label(): string
    {
        return __("messages." . strtolower($this->name));
    }

    public static function random(): self
    {
        return collect(self::cases())->random();
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'green',
            self::INACTIVE => 'gray',
            self::BLOCKED => 'red',
            self::PENDING => 'yellow',
        };
    }

    public static function fromName(string $name): int
    {
        foreach (self::cases() as $case) {
            if ($case->name === strtoupper($name)) {
                return $case->value;
            }
        }
    }
}
