<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\EventBus\Event;

use InvalidArgumentException;
use League\Event\AbstractEvent as LeagueAbstractEvent;

/**
 *
 */
abstract class AbstractEvent extends LeagueAbstractEvent implements EventInterface
{
    /**
     * {@inheritdoc}
     */
    final public function getName()
    {
        return get_class($this);
    }

    /**
     * LeagueAbstractEvent contains this public method and there is no way to get rid of it,
     * so at least it was marked as @internal
     *
     * @inheritdoc
     * @internal
     */
    public function getEmitter()
    {
        return parent::getEmitter();
    }

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
    public function getMetadata(string $metadataClass): object
    {
        if (!$this->hasMetadata($metadataClass)) {
            throw new InvalidArgumentException(sprintf('Invalid MetadataClass %s', $metadataClass));
        }

        return $this->metadata[$metadataClass];
    }

    /**
     * @return object[]
     */
    public function getAllMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * @param string $metadataClass
     *
     * @return bool
     */
    public function hasMetadata(string $metadataClass): bool
    {
        return isset($this->metadata[$metadataClass]);
    }

    /**
     * @param object[] $metadata
     */
    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * @param object $metadata
     *
     * @return void
     */
    public function addMetadata(object $metadata): void
    {
        $this->metadata[get_class($metadata)] = $metadata;
    }

    /**
     * @param object[] $metadata
     *
     * @return void
     */
    public function appendMetadata(array $metadata): void
    {
        $this->metadata = array_merge($this->metadata, $metadata);
    }
}
