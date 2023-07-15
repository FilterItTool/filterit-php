<?php

namespace FilterIt\Enums;

enum Operator: string
{
    const Equal            = 'equal';
    const NotEqual         = 'not_equal';
    const GreaterThan      = 'gt';
    const GreaterThanEqual = 'gte';
    const LessThan         = 'lt';
    const LessThanEqual    = 'lte';
    const IsNull           = 'is_null';
    const IsNotNull        = 'is_null';
    const In               = 'in';
    const NotIn            = 'not_in';
    const Between          = 'between';
    const NotBetween       = 'not_between';
    const EndsWith         = 'ends_with';
    const StartsWith       = 'starts_with';
    const Like             = 'like';
    const NotLike          = 'not_like';
}
