<?php

declare(strict_types=1);

namespace Medine\ERP\Shared\Infrastructure;

use Illuminate\Database\Query\Builder;
use Medine\ERP\Shared\Domain\Criteria;
use function Lambdish\Phunctional\each;

abstract class Filters
{
    protected $builder;

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function __invoke(Criteria $criteria): Builder
    {
        each(function (Criteria\Filter $filter) {
            $method = $filter->field()->value();
            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], array_filter([$filter->value()]));
            }
        }, $criteria->filters()->filters());

        return $this->builder;
    }
}
