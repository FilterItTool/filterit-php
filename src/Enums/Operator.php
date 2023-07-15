<?php

namespace FilterIt\Enums;

enum Operator: string
{
    public const Equal            = 'equal';
    public const NotEqual         = 'not_equal';
    public const GreaterThan      = 'gt';
    public const GreaterThanEqual = 'gte';
    public const LessThan         = 'lt';
    public const LessThanEqual    = 'lte';
    public const IsNull           = 'is_null';
    public const IsNotNull        = 'is_null';
    public const In               = 'in';
    public const NotIn            = 'not_in';
    public const Between          = 'between';
    public const NotBetween       = 'not_between';
    public const EndsWith         = 'ends_with';
    public const StartsWith       = 'starts_with';
    public const Like             = 'like';
    public const NotLike          = 'not_like';
}
