<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Utils
{

    /**
     *
     * @param $query
     * @param array $filters
     * @return mixed
     */
    public static function getRecordUsingWhereArrays($query, array $filters): mixed
    {
        # check that the $key exists in the array of $acceptedFilters
        foreach ($filters as $column => $value) {
            $query->where($column, $value);
        }

        return $query;
    }

    /**
     *
     * @param float $amount
     * @return float
     */
    public static function convert_to_2_decimal_places(float $amount): float
    {
        if (! $amount) return $amount;

        return number_format($amount,2, '.', '');
    }

    /**
     * This converts a value from Kilograms to grams
     *
     * @param int $amountInKg
     * @return float
     */
    public static function convertKgToGrams(int $amountInKg): float
    {
        return self::convert_to_2_decimal_places($amountInKg * 1000);
    }

    /**
     *
     * @param float $amountInKilograms
     * @return float
     */
    public static function getHalfTheAmountInGrams(float $amountInKilograms): float
    {
        return self::convert_to_2_decimal_places($amountInKilograms / 2);
    }

    /**
     *
     * @param array $references
     * @return string|null
     */
    public static function generateUniqueReference(array $references): ?string
    {
        try {

            $started       = true;
            $reference     = null;

            while ($started) {
                # generate the transaction reference
                $reference = str_replace('-', '', Str::uuid()->toString());
                if (! in_array($reference, $references)) $started = false;
            }

            return $reference;

        } catch (\Exception $exception) {
            Log::error($exception);

            return null;
        }
    }
}
