<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\ProcessManager;

use SuRiKmAn\ProcessManagerBundle\ProcessManager\Generator\ProcessIdGeneratorInterface;

/**
 *
 */
final class ProcessFactory implements ProcessFactoryInterface
{
    /**
     * @var ProcessIdGeneratorInterface
     */
    private $idGenerator;

    /**
     * @param ProcessIdGeneratorInterface $idGenerator
     */
    public function __construct(ProcessIdGeneratorInterface $idGenerator)
    {
        $this->idGenerator = $idGenerator;
    }

    /***
     * @inheritdoc
     */
    public function create(
        ProcessConfiguration $processConfiguration,
        Process $parentProcess = null
    ): Process {
        $processId = $this->idGenerator->generate();
        if ($parentProcess === null) {
            return new Process($processId, $processConfiguration);
        }

        return $parentProcess->fork($processId, $processConfiguration);
    }
}
