<?php

namespace Kovalevych\MappingConverter\Generator;

use Kovalevych\MappingConverter\Field\Document\SimpleField;
use Kovalevych\MappingConverter\Field\Document\ReferenceOneField;
use Kovalevych\MappingConverter\Field\Document\ReferenceManyField;

class DoctrineDocumentGenerator
{
    /**
     * @param  string        $name
     * @param  array|string  $fieldData
     * @return \Kovalevych\MappingConverter\Field\SimpleField
     */
    public function createSimpleField($name, $fieldData)
    {
        if (is_array($fieldData)) {
            if (array_key_exists('type', $fieldData)) {
                $field = new SimpleField();
                $field
                    ->setType($fieldData['type'])
                    ->setName($name)
                ;
                if (array_key_exists('default', $fieldData)) {
                    $field->setDefault($fieldData['default']);
                }

                return $field;
            }
        }

        switch ($fieldData) {
            case 'string':
            case 'integer':
            case 'raw':
            case 'boolean':
            case 'date':
            case 'float':
                $field = new SimpleField();
                $field->setType($fieldData);
                break;
            default:
                throw new \RuntimeException(sprintf('Type %s is not supported', $fieldData));
                break;
        }
        $field->setName($name);

        return $field;
    }

    /**
     * @param  string $name
     * @param  array  $fieldData
     * @param  string $fromNamespace
     * @param  string $toNamespace
     * @return Kovalevych\MandangoToDoctrineConverter\Field\ReferenceOneField
     */
    public function createReferenceOneField($name, array $fieldData, $fromNamespace = null, $toNamespace = null)
    {
        $field = new ReferenceOneField();
        $field->setType('ReferenceOne');

        return $field
            ->setName($name)
            ->setReferencedClass($fieldData['class'], $fromNamespace, $toNamespace)
        ;
    }

    /**
     * @param  string $name
     * @param  array  $fieldData
     * @param  string $fromNamespace
     * @param  string $toNamespace
     * @return Kovalevych\MandangoToDoctrineConverter\Field\ReferenceManyField
     */
    public function createReferenceManyField($name, array $fieldData, $fromNamespace = null, $toNamespace = null)
    {
        $field = new ReferenceManyField();
        $field->setType('ReferenceMany');

        return $field
            ->setName($name)
            ->setReferencedClass($fieldData['class'], $fromNamespace, $toNamespace)
        ;
    }
}
