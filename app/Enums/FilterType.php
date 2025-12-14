<?php

namespace App\Enums;

enum FilterType: string
{
    case BOOLEAN = 'boolean';
    case DATE = 'date';
    case DATETIME = 'datetime';
    case NUMBER = 'number';
    case SELECT = 'select';
    case TEXT = 'text';
}
