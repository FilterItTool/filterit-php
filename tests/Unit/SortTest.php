<?php

namespace FilterIt\Tests\Unit;

use FilterIt\Enums\Direction;
use FilterIt\Enums\Operator;
use FilterIt\FilterIt;
use PHPUnit\Framework\TestCase;

class SortTest extends TestCase
{
    public FilterIt $filterIt;

    public function setUp() : void
    {
        parent::setUp();
        $this->filterit = new FilterIt();
    }

    public function testSortBy()
    {
        $this->filterit->clear()->sortBy('id', Direction::DESC);

        $expect = 'sort_by=id:desc';

        $this->assertEquals($expect, rawurldecode($this->filterit->toQuery()));
    }

    public function testMultiSortBy()
    {
        $this->filterit->clear()->sortBy('id', Direction::DESC)
            ->sortBy('title', Direction::ASC);

        $expect = 'sort_by=id:desc,title:asc';

        $this->assertEquals($expect, rawurldecode($this->filterit->toQuery()));
    }

    public function testNestedSortBy()
    {
        $this->filterit->clear()->sortBy('id', Direction::DESC)
            ->filterBy('id', Operator::Equal, 10)
            ->or(function (FilterIt $filterIt) {
                return $filterIt->sortBy('title', Direction::ASC);
            })->sortBy('age', Direction::DESC);

        $expect = 'id=equal:10&sort_by=id:desc,title:asc,age:desc';

        $this->assertEquals($expect, rawurldecode($this->filterit->toQuery()));
    }

    public function testSortByWhen()
    {
        foreach ( [false,true] as $when ) {
            $this->filterit->clear();
            $this->filterit->sortByWhen($when, 'id', Direction::DESC);

            if ( $when ) {
                $expect = 'sort_by=id:desc';
            }else{
                $expect = '';
            }
            $this->assertEquals($expect, rawurldecode($this->filterit->toQuery()));
        }
    }
}