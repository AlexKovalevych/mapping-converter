<?php

namespace Kovalevych\MappingConverter\Field\Document;

abstract class AbstractField
{
    /**
     * Fields name
     * @var string
     */
    protected $name;

    /**
     * Doctrine property name
     * @var string
     */
    protected $doctrineName;

    /**
     * Source field type
     * @var string
     */
    protected $type;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->doctrineName = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $this->name))));

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return  self
     */
    public function setType($type)
    {
        if ($type == 'raw') {
            $this->type = 'hash';
        } elseif ($type == 'integer') {
            $this->type = 'int';
        } else {
            $this->type = $type;
        }

        return $this;
    }

    /**
     * Generates doctrine document field
     * @return string
     */
    abstract public function generate();
}
