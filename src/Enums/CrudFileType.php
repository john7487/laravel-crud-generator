<?php

namespace AltenJohn\CrudGenerator\Enums;

enum CrudFileType: string
{
    case CONTROLLER = 'controller';
    case REQUEST = 'request';
    case DTO = 'dto';
    case ACTION = 'action';
    case TEST = 'test';
}
