<?php

namespace Tests\Unit;

use App\Support\TaskDisplayHelper;
use PHPUnit\Framework\TestCase;

class TaskDisplayHelperTest extends TestCase
{
    public function test_editable_date_includes_yesterday_and_other_adjacent_days(): void
    {
        $this->assertTrue(TaskDisplayHelper::isEditableDate('2026-07-08', '2026-07-10'));
        $this->assertTrue(TaskDisplayHelper::isEditableDate('2026-07-09', '2026-07-10'));
        $this->assertTrue(TaskDisplayHelper::isEditableDate('2026-07-10', '2026-07-10'));

        $this->assertFalse(TaskDisplayHelper::isEditableDate('2026-07-07', '2026-07-10'));
        $this->assertFalse(TaskDisplayHelper::isEditableDate('2026-07-11', '2026-07-10'));
        $this->assertFalse(TaskDisplayHelper::isEditableDate('2026-07-12', '2026-07-10'));
    }

    public function test_completed_and_failed_statuses_are_kept_for_non_editable_dates(): void
    {
        $this->assertSame('completed', TaskDisplayHelper::resolveStatus('completed', false));
        $this->assertSame('failed', TaskDisplayHelper::resolveStatus('failed', false));
        $this->assertSame('active', TaskDisplayHelper::resolveStatus('active', false));
    }
}
