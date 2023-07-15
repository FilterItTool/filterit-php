<?php

namespace FilterIt\Tests\Unit;

use FilterIt\Enums\Operator;
use FilterIt\FilterIt;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    public FilterIt $filterIt;

    public function setUp(): void
    {
        parent::setUp();
        $this->filterit = new FilterIt();
    }

    public function testFilterBy()
    {
        $this->filterit->clear()->filterBy('id', Operator::Equal, 10);

        $expect = 'id=equal:10';

        $this->assertEquals($expect, rawurldecode($this->filterit->toQuery()));
    }

    public function testMultiFilterBy()
    {
        $this->filterit->clear()->filterBy('id', Operator::Equal, 10)
            ->filterBy('age', Operator::GreaterThanEqual, 18);

        $expect = 'id=equal:10&age=gte:18';

        $this->assertEquals($expect, rawurldecode($this->filterit->toQuery()));
    }

    public function testFilterByWithOr()
    {
        $this->filterit->clear()->filterBy('id', Operator::Equal, 10)
            ->or(function (FilterIt $filterIt) {
                return $filterIt->filterBy('age', Operator::GreaterThanEqual, 18);
            });

        $expect = 'id=equal:10|age=gte:18';

        $this->assertEquals($expect, rawurldecode($this->filterit->toQuery()));
    }

    public function testFilterByWithAnd()
    {
        $this->filterit->clear()->filterBy('id', Operator::Equal, 10)
            ->and(function (FilterIt $filterIt) {
                return $filterIt->filterBy('age', Operator::GreaterThanEqual, 18);
            });

        $expect = 'id=equal:10&age=gte:18';

        $this->assertEquals($expect, rawurldecode($this->filterit->toQuery()));
    }

    public function testFilterByWithOrAnd()
    {
        $this->filterit->clear()->filterBy('id', Operator::Equal, 10)
            ->and(function (FilterIt $filterIt) {
                return $filterIt->filterBy('age', Operator::GreaterThanEqual, 18);
            })->or(function (FilterIt $filterIt) {
                return $filterIt->filterBy('age', Operator::LessThan, 12);
            });

        $expect = 'id=equal:10&age=gte:18|age=lt:12';

        $this->assertEquals($expect, rawurldecode($this->filterit->toQuery()));
    }

    public function testComplexFilterByWithOrAnd()
    {
        $this->filterit->clear()->filterBy('id', Operator::Equal, 10)
            ->and(function (FilterIt $filterIt) {
                return $filterIt->filterBy('age', Operator::GreaterThanEqual, 18);
            })->or(function (FilterIt $filterIt) {
                return $filterIt->filterBy('age', Operator::LessThan, 12)
                    ->and(function (FilterIt $filterIt) {
                        return $filterIt->filterBy('birthday', Operator::Between, [ '2022', '2023' ])
                            ->or(function (FilterIt $filterIt) {
                                return $filterIt->filterBy('birthday', Operator::Between, [ '2010', '2013' ]);
                            });
                    });
            });

        $expect = 'id=equal:10&age=gte:18|(age=lt:12&(birthday=between:2022`2023|birthday=between:2010`2013))';

        $this->assertEquals($expect, rawurldecode($this->filterit->toQuery()));
    }

    public function testSortByWhen()
    {
        foreach ([false,true] as $when) {
            $this->filterit->clear();
            $this->filterit->filterByWhen($when, 'id', Operator::Equal, 10);

            if ($when) {
                $expect = 'id=equal:10';
            } else {
                $expect = '';
            }
            $this->assertEquals($expect, rawurldecode($this->filterit->toQuery()));
        }
    }

    public function testSortByWhenWithOr()
    {
        foreach ([false,true] as $when) {
            $this->filterit->clear();
            $this->filterit->filterBy('id', Operator::Equal, 10)
                ->orWhen($when, function (FilterIt $filterIt) {
                    return $filterIt->filterBy('name', Operator::Like, 'Emad');
                });

            if ($when) {
                $expect = 'id=equal:10|name=like:Emad';
            } else {
                $expect = 'id=equal:10';
            }
            $this->assertEquals($expect, rawurldecode($this->filterit->toQuery()));
        }
    }
}
