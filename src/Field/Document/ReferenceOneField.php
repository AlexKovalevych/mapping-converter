<?php

namespace Kovalevych\MappingConverter\Field\Document;

class ReferenceOneField extends AbstractReferencedField
{
    /**
     * {@inheritDoc}
     */
    public function generate()
    {
        return <<<EOF
    /**
     * @MongoDB\\ReferenceOne(targetDocument="$this->referencedClass", name="$this->name")
     */
    protected $$this->name = null;
EOF;
    }
}
