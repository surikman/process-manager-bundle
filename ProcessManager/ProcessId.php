<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\ProcessManager;

/**
 *
 */
final class ProcessId
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param ProcessId $id
     *
     * @return bool
     */
    public function isEqual(self $id): bool
    {
        return $id->id === $this->id;
    }

    /**
     * @param ProcessId $id
     *
     * @return ProcessId
     */
    public function newChild(ProcessId $id): self
    {
        return new self($this->id . '|' . $id->id);
    }
}
