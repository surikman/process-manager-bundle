<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\CommandBus\Metadata;

use InvalidArgumentException;

/**
 *
 */
final class Metadata
{
    /**
     * @var object[]
     */
    private $metadata = [];

    /**
     * @param string $metadataClass
     *
     * @return object
     * @throws InvalidArgumentException
     */
    public function get(string $metadataClass): object
    {
        if (!$this->has($metadataClass)) {
            throw new InvalidArgumentException(sprintf('Invalid MetadataClass %s', $metadataClass));
        }

        return $this->metadata[$metadataClass];
    }

    /**
     * @return object[]
     */
    public function getAll(): array
    {
        return $this->metadata;
    }

    /**
     * @param string $metadataClass
     *
     * @return bool
     */
    public function has(string $metadataClass): bool
    {
        return isset($this->metadata[$metadataClass]);
    }

    /**
     * @param object[] $metadata
     */
    public function set(array $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * @param object $metadata
     *
     * @return void
     */
    public function add(object $metadata): void
    {
        $this->metadata[get_class($metadata)] = $metadata;
    }

    /**
     * @param array $metadata
     *
     * @return void
     */
    public function append(array $metadata): void
    {
        $this->metadata = array_merge($this->metadata, $metadata);
    }
}
