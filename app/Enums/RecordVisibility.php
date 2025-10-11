<?php

namespace App\Enums;

enum RecordVisibility: string
{
    case WithoutDeleted = 'without_deleted_records';
    case WithDeleted = 'with_deleted_records';
    case OnlyDeleted = 'only_deleted_records';
}
