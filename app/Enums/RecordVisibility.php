<?php

namespace App\Enums;

enum RecordVisibility: string
{
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            $options[] = [
                'id' => $case->value,
                'name' => __('messages.' . strtolower($case->value))
            ];
        }
        return $options;
    }
    case WITH_DELETED = 'with_deleted_records';
    case ONLY_DELETED = 'only_deleted_records';
}
