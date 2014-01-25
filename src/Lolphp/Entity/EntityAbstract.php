<?php
/**
 * Created for Lolphp on 1/25/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */
namespace Lolphp\Entity;

abstract class EntityAbstract implements EntityInterface
{
    public function __construct($configuration = null)
    {
        $this->configure($configuration);
    }

    /**
     * @param array $configuration
     *
     * @return EntityInterface|void
     */
    public function configure($configuration = null)
    {
        if (!empty($configuration)) {
            foreach ($configuration as $key => $value) {
                $formatKey = 'set' . $this->formatKey($key);

                $this->{$formatKey}($value);
            }
        }

        return $this;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function formatKey($key)
    {
        return str_replace(' ', '', ucwords(strtolower(str_replace('_', ' ', $key))));
    }
}
