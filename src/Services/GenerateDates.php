<?php

namespace App\Services;

class GenerateDates
{
// тут отступы не нужны


    public function allDatesForWeek(string $date): ?array
    {

        // 4 days must be added Then we can get all days per week
        $start = date('Y-m-d', strtotime("monday this week", strtotime($date)));
        $end          = date('Y-m-d', strtotime("friday this week", strtotime($date)));

        // generate dates which should be in base. It is a Dictonary
        $generatePeriod = function ($date, $acc) use (&$generatePeriod, $end) {
            if ($date == $end) {
                $acc[] = $end;

                return $acc;
            }
            $newDate = date('Y-m-d', strtotime("+1days", strtotime($date)));
            $acc[]   = $date;

            return $generatePeriod($newDate, $acc);
        };

        return $generatePeriod($start, []);
    }
}
