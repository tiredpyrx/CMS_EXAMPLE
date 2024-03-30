<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self text()
 * @method static self longtext()
 * @method static self image()
 * @method static self images()
 * @method static self video()
 * @method static self videos()
 * @method static self file()
 * @method static self files()
 * @method static self select()
 * @method static self number()
 * @method static self color()
 * @method static self range()
 * @method static self multifield()
 * @method static self siblingfield()
 * @method static self texteditor()
 */
final class FieldTypes extends Enum
{
    public static function values(): array
    {
        return [
            'text' => 'text',
            'longtext' => 'longtext',
            'number' => 'number',
            'color' => 'color',
            'range' => 'range',
            'image' => 'image',
            'images' => 'images',
            'video' => 'video',
            'videos' => 'videos',
            'file' => 'file',
            'files' => 'files',
            'multifield' => 'multifield',
            'siblingfield' => 'siblingfield',
            'texteditor' => 'texteditor',
            'select' => 'select',
        ];
    }

    public static function typesAndFeatures(): array
    {
        $responseSet = [
            'text' => [
                'placeholder',
                'value',
                'prefix',
                'suffix',
                'min_value',
                'max_value',
            ],
            'longtext' => [
                'placeholder',
                'value',
                'prefix',
                'suffix',
                'min_value',
                'max_value',
            ],
            'number' => [
                'value',
                'step',
                'min_value',
                'max_value',
            ],
            'range' => [
                'value',
                'step',
                'min_value',
                'max_value',
            ],
            'color' => [
                'value'
            ],
            'image' => [],
            'images' => [],
        ];
        foreach ($responseSet as &$response) {
            foreach (FieldTypes::typesAndFeaturesSharedDatas() as $sharedData) {
                $response[] = $sharedData;
            }
        }

        return $responseSet;
    }

    public static function getFeaturesForType(string $type): array
    {
        $type = strtolower($type);
        return FieldTypes::typesAndFeatures()[$type];
    }

    private static function typesAndFeaturesSharedDatas(): array
    {
        return [
            'label',
            'handler',
            'type',
            'column',
            'description',
            'active',
            'required',
        ];
    }

    public static function getMediaTypes(): array
    {
        return [
            FieldTypes::image(),
            FieldTypes::images(),
            FieldTypes::video(),
            FieldTypes::videos(),
            FieldTypes::file(),
            FieldTypes::files(),
        ];
    }

    public static function getRulesForTypes()
    {
        return [
            'text' => [
                'placeholder' => ['nullable', 'string', 'max:32'],
                'prefix' => ['nullable', 'string', 'max:24'],
                'suffix' => ['nullable', 'string', 'max:24'],
                'min_value' => ['nullable', 'numeric'],
                'max_value' => ['nullable', 'numeric'],
            ],
            'longtext' => [
                'placeholder' => ['nullable', 'string', 'max:32'],
                'prefix' => ['nullable', 'string', 'max:24'],
                'suffix' => ['nullable', 'string', 'max:24'],
                'min_value' => ['nullable', 'numeric'],
                'max_value' => ['nullable', 'numeric'],
            ],
            'number' => [
                'step' => ['nullable', 'numeric'],
                'min_value' => ['nullable', 'numeric'],
                'max_value' => ['nullable', 'numeric'],
            ],
            'range' => [
                'step' => ['nullable', 'numeric'],
                'min_value' => ['nullable', 'numeric'],
                'max_value' => ['nullable', 'numeric'],
            ],
            'image' => [
                'image' => ['nullable', 'image'],
                'image_width' => ['nullable', 'numeric'],
                'image_height' => ['nullable', 'numeric'],
            ],
            'images' => [
                'images' => ['nullable', 'array'],
                'images.*' => ['image'],
                'image_width' => ['nullable', 'numeric'],
                'image_height' => ['nullable', 'numeric'],
            ],
        ];
    }

    private static function isTypeHaveAdditionalRules(string $type)
    {
        return isset(FieldTypes::getRulesForTypes()[$type]);
    }

    public static function getRulesForType(string $type)
    {
        if (!FieldTypes::isTypeAllowed($type))
            throw new \BadMethodCallException("Given type value $type is not present in allowed field types!");

        if (!FieldTypes::isTypeHaveAdditionalRules($type))
            return [];

        return FieldTypes::getRulesForTypes()[$type];
    }

    private static function isTypeAllowed(string $type)
    {
        return in_array($type, FieldTypes::values());
    }
}
