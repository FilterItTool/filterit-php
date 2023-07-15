<?php

namespace FilterIt;

use Closure;
use FilterIt\Traits\Clearable;
use FilterIt\Traits\Queryable;
use FilterIt\Traits\Sortable;

class FilterIt
{
    use Sortable;
    use Clearable;
    use Queryable;

    public function __construct(protected string &$query = '', protected string &$sortBy = '')
    {
    }

    public function filterByWhen(mixed $when, string $column, string $operator, mixed $value): MoreFilterIt
    {
        if ($this->getWhen($when)) {
            return $this->filterBy($column, $operator, $value);
        }

        return new MoreFilterIt($this->query, $this->sortBy);
    }

    protected function getWhen(mixed $when)
    {
        if ($when instanceof Closure) {
            $when = $when();
        }

        return $when;
    }

    public function filterBy(string $column, string $operator, mixed $value): MoreFilterIt
    {
        $this->query .= '&' . ( new Filter($column, $operator, $value) )->toQuery();
        return new MoreFilterIt($this->query, $this->sortBy);
    }
}
