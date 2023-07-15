<?php

namespace FilterIt;

class QueryParser
{
    public static function parseQuery(string $queryString): array
    {
        return self::parseRootQueries($queryString);
    }

    private static function parseRootQueries(string $string): array
    {
        $result        = [];
        $level         = 0;
        $startPosition = 0;
        $limitedLevel  = self::limitedLevel($string);
        $delimiter     = 'and';

        for ($i = 0; $i < strlen($string); $i++) {
            $char = $string[$i];
            if ($char === '(') {
                $level++;
            } elseif ($char === ')') {
                $level--;
            }

            $do = $limitedLevel > 0 ? $level < $limitedLevel : $level === $limitedLevel;
            if ($do && in_array($char, [ '|', '&' ])) {
                $query    = substr($string, $startPosition, $i - $startPosition);
                $result[] = self::addToResult($query, $delimiter);

                $startPosition = $i + 1;
                $delimiter     = $char === '|' ? 'or' : 'and';
            }
        }
        $result[] = self::addToResult(substr($string, $startPosition), $delimiter);

        return $result;
    }

    private static function isNestedQuery(string $query): bool
    {
        return str_starts_with($query, '(') && str_ends_with($query, ')');
    }

    private static function parseValues(string $query): array
    {
        [ $column, $value ] = explode('=', $query, 2);
        [ $operator, $originalValue ] = explode(':', $value, 2);
        return [ $column, $operator, self::parseValue($originalValue) ];
    }

    private static function parseValue(string $value): null|string|array
    {
        return str_contains($value, '`') ? explode('`', $value) : $value;
    }

    private static function parseSortValues(string $query): array
    {
        $result   = [];
        $exploded = explode(',', $query);
        foreach ($exploded as $ex) {
            [ $column, $direction ] = explode(':', $ex, 2);
            $result[] = [
                'column'    => $column,
                'direction' => $direction
            ];
        }

        return $result;
    }

    private static function limitedLevel(string $string): int
    {
        $level = 0;
        for ($i = 0; $i < strlen($string); $i++) {
            $char = $string[$i];

            if ($char === '(') {
                $level++;
            } else {
                break;
            }
        }

        return $level;
    }

    private static function addToResult(string $query, string $delimiter): array
    {
        if (self::isNestedQuery($query)) {
            return [
                'query'         => $query,
                'isNestedQuery' => true,
                'parsedQuery'   => self::parseRootQueries(substr($query, 1, -1)),
                'delimiter'     => $delimiter
            ];
        } else {
            [ $column, $operator, $value ] = self::parseValues($query);

            if ($column === 'sort_by') {
                return [
                    'query'    => $query,
                    'operator' => 'sort_by',
                    'value'    => self::parseSortValues("$operator:$value")
                ];
            }

            return [
                'query'         => $query,
                'isNestedQuery' => false,
                'column'        => $column,
                'operator'      => $operator,
                'value'         => $value,
                'delimiter'     => $delimiter
            ];
        }
    }
}
