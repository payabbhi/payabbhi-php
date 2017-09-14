<?php

namespace Payabbhi;

use Countable;

Class Collection extends ApiResource implements Countable
{
    public function count()
    {
        $totalCount = 0;

        if (isset($this->attributes['total_count']))
        {
            return $this->attributes['total_count'];
        }

        return $totalCount;
    }
}
