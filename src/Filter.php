<?php

namespace FilterIt;

class Filter
{
    public bool    $isRelation = false;
    public ?string $relation   = null;

    public function __construct(
        public string $column,
        public string $operator,
        public mixed $value,
        public bool $or = false)
    {
        $this->isRelation = str_contains($column, '___');
        if ( $this->isRelation ) {
            $exploded       = explode('___', $this->column);
            $this->column   = end($exploded);
            $this->relation = $exploded[0];
        }
    }

    public function toQuery() : string
    {
        $this->value = is_array($this->value) ? join('`', $this->value) : $this->value;
        return "{$this->column}={$this->operator}:{$this->value}";
    }
}