<?php

namespace App\Library\Models\Traits;

trait IncrementDecrement
{
    public function increment($column, $amount = 1, array $extra = [])
    {
        $this->$column = $this->$column + $amount;

        $this->save();
    }

    public function decrement($column, $amount = 1, array $extra = [])
    {
        $this->$column = $this->$column - $amount;

        $this->save();
    }
}
