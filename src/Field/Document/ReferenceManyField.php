<?php

namespace Kovalevych\MappingConverter\Field\Document;

class ReferenceManyField extends AbstractReferencedField
{
    /**
     * {@inheritDoc}
     */
    public function generate()
    {
        return <<<EOF
    /**
     * @MongoDB\\ReferenceMany(targetDocument="$this->referencedClass", name="$this->name")
     */
    protected $$this->name = null;
EOF;
    }
}
