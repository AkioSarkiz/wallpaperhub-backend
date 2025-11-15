<?php

declare(strict_types=1);

namespace App\Models\Filters;

use App\Contracts\FilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

abstract class QueryFilter implements FilterInterface
{
    protected Builder $builder;

    protected Request $request;

    /**
     * QueryFilter constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        $this->preFiltering();

        foreach ($this->filters() as $filter => $value) {
            $filter = str_replace('_', '', $filter);

            if (method_exists($this, $filter) && $value !== null) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    protected function preFiltering(): void
    {
        //
    }

    public function filters(): array
    {
        if ($this->request instanceof FormRequest) {
            return $this->request->validated();
        }

        return $this->request->all();
    }
}
