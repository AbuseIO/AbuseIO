<?php

namespace AbuseIO\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Log;

class BladeTemplate implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            // Maak een Blade compiler aan
            $filesystem = new FileSystem();
            $compiler = new BladeCompiler($filesystem, sys_get_temp_dir());

            // Compileer de template-inhoud direct
            $compiled = $compiler->compileString($value);

            // Sla de gecompileerde code tijdelijk op
            $tempCompiledPath = tempnam(sys_get_temp_dir(), 'blade_');
            $filesystem->put($tempCompiledPath, $compiled);

            // Controleer de PHP-syntaxis
            $command = 'php -l "' . $tempCompiledPath . '"';
            $output = shell_exec($command);
            // Verwijder het tijdelijke bestand
            $filesystem->delete($tempCompiledPath);

            // Als er geen syntax errors zijn, return true
            if(!str_contains($output, 'No syntax errors detected')) {
                Log::warning('bladetemplate validator syntax error: '.$output);
                $fail(trans('validation.bladetemplate', ['attribute' => $attribute]));
            }

        } catch (\Exception $e) {
            // Log de fout voor debugdoeleinden
            \Log::error('Blade template validation error: ' . $e->getMessage());
            $fail(trans('validation.bladetemplate', ['attribute' => $attribute]));
        }
    }
}
