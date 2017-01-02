<?php

namespace tests\Models;

use AbuseIO\Models\TicketGraphPoint;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use tests\TestCase;

class TicketGraphPointTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetCompoundStatistics()
    {
        $today = date('Y-m-d');
        $oneYearAgo = date('Y-m-d', strtotime($today.' -1 year'));
        $this->createDateSeries($oneYearAgo, $today, 'created_at');

        $statistics = (TicketGraphPoint::getStatistics('created_at'));

        $this->assertArrayHasKey('year', $statistics);
        $this->assertArrayHasKey('month', $statistics);
        $this->assertArrayHasKey('day', $statistics);
    }

    public function testTotalNewGraph()
    {
        $this->createDateSeries('1-8-2016', '31-8-2016', 'created_at');
        $this->assertEquals(TicketGraphPoint::getLifecycle('created_at')['legend'], 'Total new by day');
        $this->assertCount(31, TicketGraphPoint::getLifecycle('created_at')['data']);
    }

    public function testTotalTouchedGraph()
    {
        $this->createDateSeries('1-8-2016', '31-8-2016', 'updated_at');
        $this->assertEquals(TicketGraphPoint::getLifecycle('updated_at')['legend'], 'Total touched by day');
        $this->assertCount(31, TicketGraphPoint::getLifecycle('updated_at')['data']);
    }

    public function testNewGraphWithTwoClasses()
    {
        $this->createDateSeries('1-8-2016', '31-8-2016', 'created_at', ['class' => ['red', 'blue']]);

        $red = TicketGraphPoint::getLifecycleClass('created_at', 'red');
        $blue = TicketGraphPoint::getLifecycleClass('created_at', 'blue');
        $total = TicketGraphPoint::getLifecycle('created_at');

        //$this->assertEquals('blue', $blue['legend']);
        $this->assertEquals(31, count($blue['data']) + count($red['data']));
        $this->assertCount(31, $total['data']);
    }

    public function testNewGraphWithTwoClassesOverlappingTimeseries()
    {
        $this->createDateSeries('10-8-2016', '31-8-2016', 'created_at', ['class' => ['blue']]);
        $this->createDateSeries('1-8-2016', '31-8-2016', 'created_at', ['class' => ['red']]);

        $red = TicketGraphPoint::getLifecycleClass('created_at', 'red');
        $blue = TicketGraphPoint::getLifecycleClass('created_at', 'blue');

        $this->assertCount(31, TicketGraphPoint::getLifecycle('created_at')['data']);

        //$this->assertEquals('red', $red['legend']);
        $this->assertCount(31, $red['data']);

        //$this->assertEquals('blue', $blue['legend']);
        $this->assertCount(22, $blue['data']);
    }

    private function createDateSeries($startDate, $endDate, $lifecycle, $config = [])
    {
        $config = $this->loadConfig($config);

        $begin = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $end = $end->modify('+1 day');

        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($begin, $interval, $end);

        foreach ($dateRange as $date) {
            factory(TicketGraphPoint::class)
                ->create([
                    'day_date'  => $date,
                    'class'     => $config['class'][array_rand($config['class'], 1)],
                    'type'      => $config['type'][array_rand($config['type'], 1)],
                    'status'    => $config['status'][array_rand($config['status'], 1)],
                    'count'     => rand(10, 100),
                    'lifecycle' => $lifecycle,
                ]);
        }
    }

    private function loadConfig($config)
    {
        $defaultConfig = [
            'class'  => ['demo'],
            'type'   => ['demo'],
            'status' => ['demo'],
        ];

        foreach ($defaultConfig as $key => $value) {
            if (!array_key_exists($key, $config)) {
                $config[$key] = $value;
            }
        }

        return $config;
    }
}
