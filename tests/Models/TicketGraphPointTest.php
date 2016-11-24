<?php

namespace tests\Models;

use AbuseIO\Models\TicketGraphPoint;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class TicketGraphPointTest extends TestCase
{
    use DatabaseTransactions;

    public function testDailyTotalNewGraph()
    {
        $this->createDateSeries('1-8-2016', '31-8-2016', 'created_at');
        $this->assertEquals(TicketGraphPoint::getTotalNewSeries()['legend'], 'total');
        $this->assertCount(31, TicketGraphPoint::getTotalNewSeries()['data']);
    }

    public function testDailyTotalTouchedGraph()
    {
        $this->createDateSeries('1-8-2016', '31-8-2016', 'updated_at');
        $this->assertEquals(TicketGraphPoint::getTotalTouchedSeries()['legend'], 'total');
        $this->assertCount(31, TicketGraphPoint::getTotalTouchedSeries()['data']);
    }

    private function createDateSeries($startDate, $endDate, $lifecycle)
    {
        $begin = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $end = $end->modify('+1 day');

        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($begin, $interval, $end);

        foreach ($dateRange as $date) {
            factory(TicketGraphPoint::class, 10)
                ->create([
                    'day_date' => $date,
                    'class' => 'demo',
                    'type' => 'demo',
                    'status' => 'demo',
                    'count' => rand(10, 100),
                    'lifecycle' => $lifecycle
                ]);
        }
    }
}
