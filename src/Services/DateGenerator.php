<?php

namespace App\Services;

use function count;
use function date;
use function strtotime;

class DateGenerator
{
    /**
     * @param string $date
     * @param int $length
     *
     * @return array
     */
    public function rangeOfDates(string $date, int $length = 4): array
    {
        // 4 days must be added Then we can get all days per week
        // generate dates which should be in base. It is a Dictonary
        $generatePeriod = function ($date, $acc, $rangeLength) use (&$generatePeriod) {
            if (count($acc) > $rangeLength) {
                return $acc;
            }
            $acc[]   = $date;
            $newDate = date('Y-m-d', strtotime("+1days", strtotime($date)));

            return $generatePeriod($newDate, $acc, $rangeLength++);
        };

        return $generatePeriod($this->getMonday($date), [], $length);
    }

    public function getMonday(string $date)
    {
        return date('Y-m-d', strtotime("monday this week", strtotime($date)));
    }

    public function getFriday(string $date)
    {
        return date('Y-m-d', strtotime("friday this week", strtotime($date)));

    }

}
