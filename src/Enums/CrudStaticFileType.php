<?php

namespace AltenJohn\CrudGenerator\Enums;

enum CrudStaticFileType: string
{
    case MODEL = 'model';
    case FACTORY = 'factory';
    case RESOURCE = 'resource';
    case MIGRATION = 'migration';
    case ROUTE = 'route';
}
