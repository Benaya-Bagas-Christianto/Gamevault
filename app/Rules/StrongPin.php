<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class StrongPin implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = (string) $value;

        // 1. All same digits (111111, 222222, etc)
        if (preg_match('/^(\d)\1{5}$/', $value)) {
            $fail('PIN tidak boleh berisi angka yang sama berulang.');
            return;
        }

        // 2. Sequential ascending (123456, 234567, etc)
        $sequentialAsc = '01234567890';
        if (str_contains($sequentialAsc, $value)) {
            $fail('PIN tidak boleh berupa angka berurutan.');
            return;
        }

        // 3. Sequential descending (987654, 876543, etc)
        $sequentialDesc = '09876543210';
        if (str_contains($sequentialDesc, $value)) {
            $fail('PIN tidak boleh berupa angka berurutan terbalik.');
            return;
        }

        // 4. Repeating patterns (121212, 123123)
        if (preg_match('/^(\d{2})\1{2}$/', $value) || preg_match('/^(\d{3})\1{1}$/', $value)) {
            $fail('PIN tidak boleh berupa pola angka yang berulang (misal: 121212 atau 123123).');
            return;
        }
    }
}
