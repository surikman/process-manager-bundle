<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\ProcessManager\Generator;

use SuRiKmAn\ProcessManagerBundle\ProcessManager\ProcessId;
use Ramsey\Uuid\UuidFactoryInterface;

/**
 *
 */
final class UuidProcessIdGenerator implements ProcessIdGeneratorInterface
{
    /**
     * @var UuidFactoryInterface
     */
    private $uuidFactory;

    /**
     * @param UuidFactoryInterface $uuidFactory
     */
    public function __construct(UuidFactoryInterface $uuidFactory)
    {
        $this->uuidFactory = $uuidFactory;
    }

    /**
     * @return ProcessId
     */
    public function generate(): ProcessId
    {
        $uuid = $this->uuidFactory->uuid4()->toString();

        return new ProcessId($uuid);
    }
}
