<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\EventBus\Event;

use League\Event\EventInterface as LeagueEventInterface;

/**
 *
 */
interface EventInterface extends LeagueEventInterface
{
    /**
     * @param string $metadataClass
     *
     * @return object
     */
    public function getMetadata(string $metadataClass): object;

    /**
     * @return object[]
     */
    public function getAllMetadata(): array;

    /**
     * @param string $metadataClass
     *
     * @return bool
     */
    public function hasMetadata(string $metadataClass): bool;

    /**
     * @param array $metadata
     *
     * @return void
     */
    public function setMetadata(array $metadata): void;

    /**
     * @param object $metadata
     *
     * @return void
     */
    public function addMetadata(object $metadata): void;

    /**
     * @param array $metadata
     *
     * @return void
     */
    public function appendMetadata(array $metadata): void;
}
