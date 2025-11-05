<?php

namespace AbuseIO\Console\Commands;

use Illuminate\Console\Command;

/**
 * Trait ExitCodeHooks
 * Provides overridable exit code hooks for success and common failure cases.
 */
trait ExitCodeHooks
{
    // Success hook
    protected function getSuccessExitCode(): int
    {
        return Command::SUCCESS;
    }

    // Generic failure hook (fallback when a more specific one is not present)
    protected function getFailureExitCode(): int
    {
        return Command::FAILURE;
    }

    // Specific failure hooks for common scenarios; defaults to generic failure
    protected function getInvalidOptionExitCode(): int
    {
        return $this->getFailureExitCode();
    }

    protected function getValidationFailedExitCode(): int
    {
        return $this->getFailureExitCode();
    }

    protected function getSaveFailedExitCode(): int
    {
        return $this->getFailureExitCode();
    }

    protected function getNotFoundExitCode(): int
    {
        return $this->getFailureExitCode();
    }

    protected function getDeleteBlockedExitCode(): int
    {
        return $this->getFailureExitCode();
    }

    protected function getDeleteFailedExitCode(): int
    {
        return $this->getFailureExitCode();
    }
}
