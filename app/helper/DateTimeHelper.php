<?php

namespace app\helper;

use DateTime;
use DateTimeZone;
use Throwable;

class DateTimeHelper {
    public static function timeTrans(
        string $timeStr,
        string $tgtFmt = 'Y-m-d H:i:s',
        string $srcFmt = 'Y-m-d H:i:s',
        string $tgtTimezone = 'PRC',
        string $srcTimezone = 'UTC'
    ) {
        try {
            return DateTime::createFromFormat($srcFmt, $timeStr, new DateTimeZone($srcTimezone))
                           ->setTimezone(new DateTimeZone($tgtTimezone))
                           ->format($tgtFmt);
        } catch (Throwable $e) {
            return $timeStr;
        }
    }
}