<?php

namespace FilterIt;

use Closure;

class MoreFilterIt extends FilterIt
{
    public function orWhen(mixed $when, Closure $callback) : static
    {
        if ( $this->getWhen($when) )
            return $this->or($callback);

        return $this;
    }

    public function or(Closure $callback) : static
    {
        $result       = $callback(new FilterIt());
        $filterResult = $result->toQueryOnlyFilters();
        $sortResult   = $result->toQueryOnlySorts();
        if ( strlen($filterResult) > 0 )
            $this->query .= substr_count($filterResult, '=') == 1 ? ( '|' . $filterResult ) : ( '|(' . $filterResult .
                ')' );
        $this->sortByString($sortResult);
        return $this;
    }

    public function andWhen(mixed $when, Closure $callback) : static
    {
        if ( $this->getWhen($when) )
            return $this->and($callback);

        return $this;
    }

    public function and(Closure $callback) : static
    {
        $result       = $callback(new FilterIt());
        $filterResult = $result->toQueryOnlyFilters();
        $sortResult   = $result->toQueryOnlySorts();
        if ( strlen($filterResult) > 0 )
            $this->query .= substr_count($filterResult, '=') == 1 ? ( '&' . $filterResult ) : ( '&(' . $filterResult .
                ')' );
        $this->sortByString($sortResult);
        return $this;
    }
}