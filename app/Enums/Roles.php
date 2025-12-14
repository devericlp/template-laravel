<?php

namespace App\Enums;

enum Roles: int
{
    public function label(): string
    {
        return match ($this) {
            static::ADMIN => 'admin',
            static::USER => 'user',
        };
    }

    /**
     * Get all statuses as array for select options with translated labels
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $case) {

            $options[] = [
                'id' => $case->value,
                'name' => __('messages.' . $case->label())
            ];
        }

        // Sort options alphabetically by label
        asort($options);

        return $options;
    }
    case ADMIN = 1;
    case USER = 2;
}
