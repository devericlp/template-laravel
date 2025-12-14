<?php

namespace App\Enums;

enum Status: int
{
    public function label(): string
    {
        return __('messages.' . strtolower($this->name));
    }

    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            $options[] = [
                'id' => $case->value,
                'name' => $case->label()
            ];
        }
        return $options;
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
    case INACTIVE = 1;
    case ACTIVE = 2;
    case BLOCKED = 3;
    case PENDING = 4;
}
