<?php

namespace Kovalevych\MappingConverter\Converter;

use Kovalevych\MappingConverter\Generator\DoctrineDocumentGenerator;

class MandangoToDoctrineConverter
{
    /**
     * @var \Kovalevych\MappingConverter\Generator
     */
    protected $generator;

    public function __construct()
    {
        $this->generator = new DoctrineDocumentGenerator();
    }

    /**
     * @param  array  $config Mandango mappings config
     * @return string
     */
    public function generateDocument(array $config, $fromNamespace = null, $toNamespace = null)
    {
        $data = array_values($config)[0];
        $fields = [];
        foreach ($data['fields'] as $name => $fieldData) {
            $fields[] = $this->generator->createSimpleField($name, $fieldData)->generate();
        }

        if (array_key_exists('referencesOne', $data)) {
            foreach ($data['referencesOne'] as $name => $value) {
                $fields[] = $this->generator->createReferenceOneField($name, $value, $fromNamespace, $toNamespace)->generate();
            }
        }

        if (array_key_exists('referencesMany', $data)) {
            foreach ($data['referencesMany'] as $name => $value) {
                $fields[] = $this->generator->createReferenceManyField($name, $value, $fromNamespace, $toNamespace)->generate();
            }
        }
        $collection = $data['collection'];
        $classname = substr(array_keys($config)[0], strrpos(array_keys($config)[0], '\\') + 1);
        $result = <<<EOF
<?php

namespace $toNamespace;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="$collection")
 */
class $classname
{
    /**
     * @MongoDB\Id
     */
    protected \$id = null;

EOF;

        return $result .= PHP_EOL . implode(PHP_EOL . PHP_EOL, $fields) . PHP_EOL . '}' . PHP_EOL;
    }
}
