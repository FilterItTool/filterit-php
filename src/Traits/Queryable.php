<?php

namespace FilterIt\Traits;

trait Queryable
{
    public function toQuery() : string
    {
        return $this->trim($this->toQueryOnlyFilters() . '&' . $this->toQueryOnlySorts());
    }

    private function trim(string $query) : string
    {
        return trim($query, '&,|');
    }

    protected function toQueryOnlyFilters() : string
    {
        return $this->trim($this->query);
    }

    protected function toQueryOnlySorts() : string
    {
        return $this->trim($this->sortBy);
    }
}