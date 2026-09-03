<?php


namespace AltenJohn\CrudGenerator\Definitions;

use AltenJohn\CrudGenerator\Enums\CrudAction;
use AltenJohn\CrudGenerator\Enums\CrudFileType;
use AltenJohn\CrudGenerator\Enums\CrudStaticFileType;

final class CrudDefinition
{


public static function definitions(): array
{
    $definitions = CrudFileDefinitions::base();

    foreach (CrudAction::cases() as $action) {
        foreach (self::filesFor($action) as $fileType) {
            $definitions[] = CrudFileDefinitions::resolve(
                action: $action,
                type: $fileType,
            );
        }
    }

    return $definitions;
}



    /**
     * @return array<CrudAction, list<CrudFileType>>
     */
    public static function actions(): array
    {
        return [
            CrudAction::INDEX->value => [
                CrudFileType::CONTROLLER,
                CrudFileType::TEST,
            ],

            CrudAction::STORE->value => [
                CrudFileType::CONTROLLER,
                CrudFileType::REQUEST,
                CrudFileType::DTO,
                CrudFileType::ACTION,
                CrudFileType::TEST,
            ],

            CrudAction::UPDATE->value => [
                CrudFileType::CONTROLLER,
                CrudFileType::REQUEST,
                CrudFileType::DTO,
                CrudFileType::ACTION,
                CrudFileType::TEST,
            ],

            CrudAction::SHOW->value => [
                CrudFileType::CONTROLLER,
                CrudFileType::TEST,
            ],

            CrudAction::DELETE->value => [
                CrudFileType::CONTROLLER,
                CrudFileType::ACTION,
                CrudFileType::TEST,
            ],

            CrudAction::IMPORT->value => [
                CrudFileType::CONTROLLER,
                CrudFileType::REQUEST,
                CrudFileType::DTO,
                CrudFileType::ACTION,
                CrudFileType::TEST,
            ],

            CrudAction::EXPORT->value => [
                CrudFileType::CONTROLLER,
                CrudFileType::ACTION,
                CrudFileType::TEST,
            ],
        ];
    }

    /**
     * @return array<int, CrudFileType>
     */
    public static function filesFor(
        CrudAction $action,
    ): array {
        return self::actions()[$action->value] ?? [];
    }


    /**
     * @return list<CrudStaticFileType>
     */
    public static function staticFiles(): array
    {
        return [
            CrudStaticFileType::MODEL,
            CrudStaticFileType::FACTORY,
            CrudStaticFileType::RESOURCE,
            CrudStaticFileType::MIGRATION,
            CrudStaticFileType::ROUTE,
        ];
    }
}
