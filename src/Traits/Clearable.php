<?php

namespace FilterIt\Traits;

use Closure;

trait Clearable
{
    public function clear(): static
    {
        $this->query = '';
        return $this;
    }

    public function clearWhen(bool|Closure $when): static
    {
        if ($this->getWhen($when)) {
            $this->query = '';
        }

        return $this;
    }
}
