<?php

namespace Kovalevych\MappingConverter\Field\Document;

abstract class AbstractReferenceField extends AbstractField
{
    /**
     * @var string
     */
    protected $referencedClass;

    /**
     * @return string
     */
    public function getReferencedClass()
    {
        return $this->referencedClass;
    }

    /**
     * @param string $referencedClass
     * @param string $fromNamespace
     * @param string $toNamespace
     */
    public function setReferencedClass($referencedClass, $fromNamespace = null, $toNamespace = null)
    {
        if ($fromNamespace && $toNamespace) {
            $this->referencedClass = str_replace($fromNamespace, $toNamespace, $referencedClass);
        } else {
            $this->referencedClass = $referencedClass;
        }

        return $this;
    }
}
