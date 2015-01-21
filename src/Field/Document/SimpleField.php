<?php

namespace Kovalevych\MappingConverter\Field\Document;

class SimpleField extends AbstractField
{
    /**
     * Default field value
     * @var string
     */
    protected $default = 'null';

    /**
     * @return string
     */
    public function getDefault()
    {
        return $default;
    }

    /**
     * @param string $default
     * @return  self
     */
    public function setDefault($default)
    {
        if (is_array($default)) {
            $this->default = '[]';
        } elseif (is_bool($default)) {
            $this->default = $default ? 'true' : 'false';
        } else {
            $this->default = $default;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function generate()
    {
        $doctrineType = ucfirst($this->type);

        return <<<EOF
    /**
     * @MongoDB\\$doctrineType(name="$this->name")
     */
    protected $$this->doctrineName = $this->default;
EOF;
    }
}
