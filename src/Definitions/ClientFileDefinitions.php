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
        self::client(),
        self::contract(),
        self::dtoData(),
        self::resource(),
        self::route(),
        self::testUnit(),
    ];
}

///////////// BAGIAN BASE FILE ////////////////////////////////////
    private static function client(): GeneratorDefinition
    {
        return new GeneratorDefinition(
            name: 'Client',
            stub: 'client/client.stub',
            directory: 'app/Clients',
            filename: '{{ model }}/{{ model }}Client.php',
            crud: '',
        );
    }

    private static function contract(): GeneratorDefinition
    {
        return new GeneratorDefinition(
            name: 'Contract',
            stub: 'client/contract.stub',
            directory: 'app/Contracts',
            filename: '{{ model }}/{{ model }}ClientContract.php',
            crud: '',
        );
    }

    private static function dtoData(): GeneratorDefinition {
        return new GeneratorDefinition(  
            name: 'DataDTOs',
            stub: 'client/dto/data.stub',
            directory: 'app/DTOs/{{ model }}',
            filename: 'Data{{ model }}DTO.php',
            crud: '',
        );
    }

    private static function resource(): GeneratorDefinition
    {
        return new GeneratorDefinition(
            name: 'Resource',
            stub: 'client/resource.stub',
            directory: 'app/Http/Resources',
            filename: 'Api/{{ controller }}Resource.php',
            crud: '',
        );
    }

    private static function route(): GeneratorDefinition
    {
        return new GeneratorDefinition(
            name: 'Route',
            stub: 'crud/base/route.stub',
            directory: 'routes/api',
            filename: '{{ route_path }}/{{ variable }}.php',
            crud: '',
        );
    }

    private static function testUnit(): GeneratorDefinition {
        return new GeneratorDefinition(
            name: 'TestClientUnit',
            stub: 'client/tests/unit.client.stub',
            directory: 'tests/Unit',
            filename: 'Clients/{{ model }}/{{ model }}ClientTest.php',
            crud: '',
        );
    }


/////////////////////////////// SAMBIL DARI CRUD ////////////////////////////////////////////////


    private static function controller(
        CrudAction $action,
    ): GeneratorDefinition {
        return new GeneratorDefinition(
            name: $action->value.'Controller',
            stub: 'crud/controllers/'.Str::lower($action->value).'.stub',
            directory: 'app/Http/Controllers',
            filename: 'Api/{{ controller }}/{{ crud }}Controller.php',
            crud: $action->value,
        );
    }

    private static function dto(
        CrudAction $action,
    ): GeneratorDefinition {
        return new GeneratorDefinition(  
            name: $action->value.'DTOs',
            stub: 'crud/dtos/'.Str::lower($action->value).'.stub',
            directory: 'app/DTOs/{{ model }}',
            filename: $action->value.'{{ model }}DTO.php',
            crud: $action->value,
        );
    }


    /////////////////////// REQUES BUAT SENDIRI KARENA VALIDASI UNIQ //////////////////

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

///////////////////////////////  BAGIAN CLIENT /////////////////////

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

}
