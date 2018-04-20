<?php
declare(strict_types=1);
/**
 * @author SuRiKmAn <surikman@surikman.sk>
 */

namespace SuRiKmAn\ProcessManagerBundle\Exception;

use Exception;

/**
 *
 */
class CommandRegister extends Exception
{
    /**
     * @param \Throwable $previous
     */
    public function __construct(\Throwable $previous = null)
    {
        parent::__construct('Command Registration Exception - ' . get_class($this), 0, $previous);
    }
}
