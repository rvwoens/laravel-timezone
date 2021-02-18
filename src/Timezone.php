<?php

namespace JamesMills\LaravelTimezone;

use Carbon\Carbon;

class Timezone
{
    /**
     * @param  Carbon|null  $date
     * @param  null  $format
     * @param  bool  $format_timezone
     * @return string
     */
    public function convertToLocal(?Carbon $date, $format = null, $format_timezone = false, $diff_for_humans = false): string
    {
        if (is_null($date)) {
            return 'Empty';
        }

        $timezone = (auth()->user()->timezone) ?? config('app.timezone');

        $date->setTimezone($timezone);

        if (is_null($format)) {
            return $date->format(config('timezone.format'));
        }

        if ($diff_for_humans) {
            return $date->diffForHumans();
        }

        $formatted_date_time = $date->format($format);

        if ($format_timezone) {
            return $formatted_date_time . ' ' . $this->formatTimezone($date);
        }

        return $formatted_date_time;
    }

    /**
     * @param  Carbon|null  $date
     * @return string
     */
    public function convertToLocalDiffForHumans(?Carbon $date): string
    {
        return $this->convertToLocal($date, null, false, true);
    }

    /**
     * @param $date
     * @return Carbon
     */
    public function convertFromLocal($date): Carbon
    {
        return Carbon::parse($date, auth()->user()->timezone)->setTimezone('UTC');
    }

    /**
     * @param  Carbon  $date
     * @return string
     */
    private function formatTimezone(Carbon $date): string
    {
        $timezone = $date->format('e');
        $parts = explode('/', $timezone);

        if (count($parts) > 1) {
            return str_replace('_', ' ', $parts[1]) . ', ' . $parts[0];
        }

        return str_replace('_', ' ', $parts[0]);
    }
}
