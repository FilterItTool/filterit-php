<?php

namespace FilterIt\Tests\Unit;

use FilterIt\QueryParser;
use PHPUnit\Framework\TestCase;

class QueryParserTest extends TestCase
{
    public function testSimpleQuery()
    {
        $parsedQuery = QueryParser::parseQuery('id=in:10`15`20|age=gt:20');
        $expect      = [
            [
                "query"         => "id=in:10`15`20",
                "isNestedQuery" => false,
                "column"        => "id",
                "operator"      => "in",
                "value"         => [ '10', '15', '20' ],
                "delimiter"     => "and"
            ],
            [
                "query"         => "age=gt:20",
                "isNestedQuery" => false,
                "column"        => "age",
                "operator"      => "gt",
                "value"         => '20',
                "delimiter"     => "or"
            ],
        ];

        $this->assertEquals($parsedQuery, $expect);
    }

    public function testComplexQuery()
    {
        $parsedQuery = QueryParser::parseQuery('id=equal:10&(id=equal:10|id=equal:12)&(id=equal:5&(likes=gte:100&comments=between:10`25))|(id=not_equal:5&(likes=gte:200&comments=between:100`250))');
        $expect      = [
            [
                "query"         => "id=equal:10",
                "isNestedQuery" => false,
                "column"        => "id",
                "operator"      => "equal",
                "value"         => "10",
                "delimiter"     => "and"
            ],
            [
                "query"         => "(id=equal:10|id=equal:12)",
                "isNestedQuery" => true,
                "delimiter"     => "and",
                "parsedQuery"   => [
                    [
                        "query"         => "id=equal:10",
                        "isNestedQuery" => false,
                        "column"        => "id",
                        "operator"      => "equal",
                        "value"         => "10",
                        "delimiter"     => "and"
                    ],
                    [
                        "query"         => "id=equal:12",
                        "isNestedQuery" => false,
                        "column"        => "id",
                        "operator"      => "equal",
                        "value"         => "12",
                        "delimiter"     => "or"
                    ]
                ]
            ],
            [
                "query"         => "(id=equal:5&(likes=gte:100&comments=between:10`25))",
                "isNestedQuery" => true,
                "delimiter"     => "and",
                "parsedQuery"   => [
                    [
                        "query"         => "id=equal:5",
                        "isNestedQuery" => false,
                        "column"        => "id",
                        "operator"      => "equal",
                        "value"         => "5",
                        "delimiter"     => "and"
                    ],
                    [
                        "query"         => "(likes=gte:100&comments=between:10`25)",
                        "isNestedQuery" => true,
                        "delimiter"     => "and",
                        "parsedQuery"   => [
                            [
                                "query"         => "likes=gte:100",
                                "isNestedQuery" => false,
                                "column"        => "likes",
                                "operator"      => "gte",
                                "value"         => "100",
                                "delimiter"     => "and"
                            ],
                            [
                                "query"         => "comments=between:10`25",
                                "isNestedQuery" => false,
                                "column"        => "comments",
                                "operator"      => "between",
                                "value"         => [
                                    "10",
                                    "25"
                                ],
                                "delimiter"     => "and"
                            ]
                        ]
                    ]
                ]
            ],
            [
                "query"         => "(id=not_equal:5&(likes=gte:200&comments=between:100`250))",
                "isNestedQuery" => true,
                "delimiter"     => "or",
                "parsedQuery"   => [
                    [
                        "query"         => "id=not_equal:5",
                        "isNestedQuery" => false,
                        "column"        => "id",
                        "operator"      => "not_equal",
                        "value"         => "5",
                        "delimiter"     => "and"
                    ],
                    [
                        "query"         => "(likes=gte:200&comments=between:100`250)",
                        "isNestedQuery" => true,
                        "delimiter"     => "and",
                        "parsedQuery"   => [
                            [
                                "query"         => "likes=gte:200",
                                "isNestedQuery" => false,
                                "column"        => "likes",
                                "operator"      => "gte",
                                "value"         => "200",
                                "delimiter"     => "and"
                            ],
                            [
                                "query"         => "comments=between:100`250",
                                "isNestedQuery" => false,
                                "column"        => "comments",
                                "operator"      => "between",
                                "value"         => [
                                    "100",
                                    "250"
                                ],
                                "delimiter"     => "and"
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals($parsedQuery, $expect);
    }

    public function testComplexNestedQuery()
    {
        $parsedQuery = QueryParser::parseQuery('id=in:10`15`20|(age=gt:20|((title=starts_with:Emad|title=starts_with:Ali)|age=lt:20))');
        $expect      = [
            [
                "query"         => "id=in:10`15`20",
                "isNestedQuery" => false,
                "column"        => "id",
                "operator"      => "in",
                "value"         => [ '10', '15', '20' ],
                "delimiter"     => "and"
            ],
            [
                "query"         => "(age=gt:20|((title=starts_with:Emad|title=starts_with:Ali)|age=lt:20))",
                "isNestedQuery" => true,
                "parsedQuery"   => [
                    [
                        "query"         => "age=gt:20",
                        "isNestedQuery" => false,
                        "column"        => "age",
                        "operator"      => "gt",
                        "value"         => '20',
                        "delimiter"     => "and"
                    ],
                    [
                        "query"         => "((title=starts_with:Emad|title=starts_with:Ali)|age=lt:20)",
                        "isNestedQuery" => true,
                        "parsedQuery"   => [
                            [
                                "query"         => "(title=starts_with:Emad|title=starts_with:Ali)",
                                "isNestedQuery" => true,
                                "parsedQuery"   => [
                                    [
                                        "query"         => "title=starts_with:Emad",
                                        "isNestedQuery" => false,
                                        "column"        => "title",
                                        "operator"      => "starts_with",
                                        "value"         => 'Emad',
                                        "delimiter"     => "and"
                                    ],
                                    [
                                        "query"         => "title=starts_with:Ali",
                                        "isNestedQuery" => false,
                                        "column"        => "title",
                                        "operator"      => "starts_with",
                                        "value"         => 'Ali',
                                        "delimiter"     => "or"
                                    ],
                                ],
                                "delimiter"     => "and"
                            ],
                            [
                                "query"         => "age=lt:20",
                                "isNestedQuery" => false,
                                "column"        => "age",
                                "operator"      => "lt",
                                "value"         => '20',
                                "delimiter"     => "or"
                            ]
                        ],
                        "delimiter"     => "or"
                    ]
                ],
                "delimiter"     => "or"
            ]
        ];

        $this->assertEquals($parsedQuery, $expect);
    }
}