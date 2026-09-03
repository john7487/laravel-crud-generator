<?php

namespace AltenJohn\CrudGenerator\Enums;

enum CrudAction: string
{
    case INDEX = 'Index';
    case STORE = 'Store';
    case UPDATE = 'Update';
    case SHOW = 'Show';
    case DELETE = 'Delete';
    case IMPORT = 'Import';
    case EXPORT = 'Export';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }


}