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
        // If value is null or empty string, treat it as false
        if ($value === null || $value === '') {
            $fail("The '{$attribute}' field must be a boolean value");
            return false;
        }

        // If the value is an array, it's invalid for a boolean field
        if (is_array($value)) {
            $fail("The '{$attribute}' field must be a boolean value, not an array");
            return false;
        }

        // If the value is a string, check for common boolean representations
        if (is_string($value)) {
            $lowerValue = strtolower($value);
            if (!in_array($lowerValue, ['true', 'false', '1', '0'])) {
                $fail("The '{$attribute}' field must be a boolean string value (true, false, 1, or 0)");
                return false;
            }

            // convert string to boolean
            return in_array($lowerValue, ['true', '1']);
        }

        // If the value is an integer, check for 0 or 1
        if (is_int($value)) {
            if (!in_array($value, [0, 1])) {
                $fail("The '{$attribute}' field must be a boolean integer value (0 or 1)");
                return false;
            }

            return $value === 1;
        }

        // if the value is a boolean return it as is
        if (is_bool($value)) {
            return $value;
        }

        // if the value is of any other type, it's invalid for a boolean field
        $fail("The '{$attribute}' field must be a boolean, string (true/false/1/0), or integer (0/1)");
        return false;
    }
}
