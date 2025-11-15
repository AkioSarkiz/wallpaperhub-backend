<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Contracts\FilterInterface;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    #[Scope]
    public function filter(Builder $query, FilterInterface $filter): Builder
    {
        return $filter->apply($query);
    }
}
