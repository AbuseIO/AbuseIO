<?php

namespace tests\Models;

use AbuseIO\Models\TicketGraphPoint;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class TicketGraphPointTest extends TestCase
{
    use DatabaseTransactions;

    public function testDailyGraph()
    {
        $this->createDateSeries();

        $this->assertEquals(TicketGraphPoint::getTotalSeries()['legend'], 'total');
        $this->assertCount(31, TicketGraphPoint::getTotalSeries()['data']);
    }

    private function createDateSeries()
    {
        $begin = new \DateTime( '2016-08-01' );
        $end = new \DateTime( '2016-08-31' );
        $end = $end->modify( '+1 day' );

        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval ,$end);

        foreach($daterange as $date) {
            factory(TicketGraphPoint::class, 10)
                ->create([
                    'day_date' => $date,
                    'class' => 'demo',
                    'type' => 'demo',
                    'status' => 'demo',
                    'count' => rand(10, 100),
                    'lifecycle' => 'created_at'
                ]);
        }
    }
}
