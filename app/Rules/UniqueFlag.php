<?php

namespace AbuseIO\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\QueryException;

class UniqueFlag implements ValidationRule, DataAwareRule
{
    protected array $data = [];

    public function __construct(private $table, private $field)
    {
    }

    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Validate that a boolean flag is unique in the given table and field.
     *
     * @param string $attribute, the attribute that is being validated
     * @param mixed $value, the value of the attribute
     * @param Closure $fail
     *
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($this->table) || empty($this->field)) {
            $fail("uniqueflag validator: called without the needed parameters");
            return;
        }

        // Convert the value to boolean
        $value = $this->valueToBoolean($attribute, $value, $fail);

        // If the value is true, we need to check if there is already another entry with the flag set to true
        if ($value) {
            $query = \DB::table($this->table)->where($this->field, true);

            if (array_key_exists('id', $this->data)) {
                $query->where('id', '!=', $this->data['id']);
            }

            try {
                $object = $query->first();
            } catch (QueryException $e) {
                $message = $e->getMessage();

                $fail("uniqueflag validator: database query failed with message: {$message}");

                return;
            }

            if (!empty($object)) {
                $fail("The {$attribute} field must be unique in table {$this->table}");
            }
        }
    }

    // Convert various representations of boolean to actual boolean
    private function valueToBoolean(string $attribute, mixed $value, Closure $fail): bool
    {
        if (!isset($value) || $value === []) {
            $fail("The '{$attribute}' field must be a boolean value");
            return false;
        }

        // Checks if the value is a string and representation of a boolean true
        if (gettype($value) == 'string' && !in_array($value, ['true', 'false', '1', '0'])) {
            $fail("The '{$attribute}' field must be a boolean string value");
            return false;
        }

        if (gettype($value) == 'integer' && !is_bool((bool)$value)) {
            $fail("The '{$attribute}' field must be a boolean integer value");
            return false;
        }

        if ($value === 'false' || $value === '0') {
            $value = false;
        }

        return (bool) $value;
    }
}
