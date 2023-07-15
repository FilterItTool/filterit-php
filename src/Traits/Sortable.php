<?php

namespace FilterIt\Traits;

use FilterIt\Filter;

trait Sortable
{
    public function sortByWhen(mixed $when, string $column, string $direction) : static
    {
        if ( $this->getWhen($when) )
            return $this->sortBy($column, $direction);

        return $this;
    }

    public function sortBy(string $column, string $direction) : static
    {
        $sort = ( new Filter('sort_by', $column, $direction) )->toQuery();
        return $this->sortByString($sort);
    }

    protected function sortByString(string $string) : static
    {
        $sorts = explode(',', str_replace('sort_by=', '', $string));
        foreach ( $sorts as $sort ) {
            [ $column, $direction ] = explode(':', $sort, 2);
            if ( !( empty($column) && empty($direction) ) ) {
                if ( strlen($this->sortBy) === 0 ) {
                    $this->sortBy .= ( new Filter('sort_by', $column, $direction) )->toQuery();
                } else {
                    $this->sortBy .= ',' .
                        str_replace('sort_by=', '', ( new Filter('sort_by', $column, $direction) )->toQuery());
                }
            }
        }
        return $this;
    }
}