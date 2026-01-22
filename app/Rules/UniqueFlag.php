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
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($this->table) || empty($this->field)) {
            $fail("uniqueflag validator: called without the needed parameters");
            return;
        }

        // Checks if the value is a sting and represents a boolean true
        if (gettype($value) == 'string') {
            $value = ($value == 'true' or $value == '1');
        }

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
}
