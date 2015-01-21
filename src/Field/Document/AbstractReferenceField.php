<?php

namespace Kovalevych\MappingConverter\Field\Document;

abstract class AbstractReferencedField extends AbstractField
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
     * @return  self
     */
    public function setReferencedClass($referencedClass, $fromNamespace, $toNamespace)
    {
        $this->referencedClass = str_replace($fromNamespace, $toNamespace, $referencedClass);

        return $this;
    }
}
