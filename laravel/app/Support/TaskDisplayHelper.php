<?php

namespace App\Support;

class TaskDisplayHelper
{
    public static function isEditableDate(string $targetDate, string $todayDate): bool
    {
        $target = new \DateTimeImmutable($targetDate);
        $today = new \DateTimeImmutable($todayDate);

        $diffDays = (int) floor(($today->getTimestamp() - $target->getTimestamp()) / 86400);

        return in_array($diffDays, [2, 1, 0], true);
    }

    public static function resolveStatus(string $status, bool $canEdit): string
    {
        if (! $canEdit) {
            return in_array($status, ['completed', 'failed'], true) ? $status : 'active';
        }

        return $status;
    }
}
