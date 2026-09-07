<?php

declare(strict_types=1);

namespace AltenJohn\CrudGenerator\Definitions;

use AltenJohn\CrudGenerator\Enums\CrudAction;
use AltenJohn\CrudGenerator\Enums\CrudFileType;
use AltenJohn\CrudGenerator\Support\GeneratorDefinition;
use Illuminate\Support\Str;

final class ClientFileDefinitions
{

public static function resolve( 
    CrudAction $action, 
    CrudFileType $type, 
    ): GeneratorDefinition { 
    return match ($type) { 
        CrudFileType::CONTROLLER => self::controller($action), 
        CrudFileType::REQUEST => self::request($action), 
        CrudFileType::DTO => self::dto($action), 
        CrudFileType::ACTION => self::action($action), 
        CrudFileType::TEST => self::test($action), 
    }; 
}


/**
 * @return array<int, GeneratorDefinition>
 */
public static function base(): array
{
    return [
      //  self::model(),
       // self::factory(),
        self::resource(),
      //  self::migration(),
        self::route(),
    ];
}


    private static function controller(
        CrudAction $action,
    ): GeneratorDefinition {
        return new GeneratorDefinition(
            name: $action->value.'Controller',
            stub: 'client/controllers/'.Str::lower($action->value).'.stub',
            directory: 'app/Http/Controllers',
            filename: 'Api/{{ controller }}/{{ crud }}Controller.php',
            crud: $action->value,
        );
    }

    private static function request(
        CrudAction $action,
    ): GeneratorDefinition {
        return new GeneratorDefinition(        
            name: $action->value.'Request',
            stub: 'client/requests/'.Str::lower($action->value).'.stub',
            directory: 'app/Http/Requests',
            filename: 'Api/{{ controller }}/{{ crud }}Request.php',
            crud: $action->value,
        );
    }

    private static function dto(
        CrudAction $action,
    ): GeneratorDefinition {
        return new GeneratorDefinition(  
            name: $action->value.'DTOs',
            stub: 'client/dtos/'.Str::lower($action->value).'.stub',
            directory: 'app/DTOs/{{ model }}',
            filename: $action->value.'{{ model }}DTO.php',
            crud: $action->value,
        );
    }

    private static function action(
        CrudAction $action,
    ): GeneratorDefinition {
        return new GeneratorDefinition(
            name: $action->value.'Action',
            stub: 'client/actions/'.Str::lower($action->value).'.stub',
            directory: 'app/Actions/{{ model }}',
            filename: $action->value.'{{ model }}Action.php',
            crud: $action->value,
        );
    }

    private static function test(
        CrudAction $action,
    ): GeneratorDefinition {
        return new GeneratorDefinition(
            name: 'Test'.$action->value.'Controller',
            stub: 'client/tests/controller.'.Str::lower($action->value).'.stub',
            directory: 'tests/Feature',
            filename: 'Api/{{ controller }}/{{ crud }}ControllerTest.php',
            crud: $action->value,
        );
    }


    // private static function model(): GeneratorDefinition
    // {
    //     return new GeneratorDefinition(
    //         name: 'Model',
    //         stub: 'client/base/model.stub',
    //         directory: 'app/Models',
    //         filename: '{{ model }}.php',
    //         crud: '',
    //     );
    // }


    // private static function factory(): GeneratorDefinition
    // {
    //     return new GeneratorDefinition(
    //         name: 'Factory',
    //         stub: 'client/base/factory.stub',
    //         directory: 'database/factories',
    //         filename: '{{ model }}Factory.php',
    //         crud: '',
    //     );
    // }


    private static function resource(): GeneratorDefinition
    {
        return new GeneratorDefinition(
            name: 'Resource',
            stub: 'client/base/resource.stub',
            directory: 'app/Http/Resources',
            filename: 'Api/{{ controller }}Resource.php',
            crud: '',
        );
    }


    // private static function migration(): GeneratorDefinition
    // {
    //     return new GeneratorDefinition(
    //         name: 'Migration',
    //         stub: 'client/base/migration.stub',
    //         directory: 'database/migrations',
    //         filename: 'create_{{ table }}_table.php',
    //         crud: '',
    //         timestamp: true,
    //         option: 'migration',
    //     );
    // }

    private static function route(): GeneratorDefinition
    {
        return new GeneratorDefinition(
            name: 'Route',
            stub: 'client/base/route.stub',
            directory: 'routes/api',
            filename: '{{ route_path }}/{{ variable }}.php',
            crud: '',
        );
    }

}