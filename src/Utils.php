<?php

declare(strict_types=1);

namespace MLL\GraphQLScalars;

use GraphQL\Error\Error;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Utils\Utils as GraphQLUtils;

class Utils
{
    /**
     * Check if a value can be serialized to a string.
     */
    public static function canBeString($value): bool
    {
        return $value === null
            || is_scalar($value)
            || (is_object($value) && method_exists($value, '__toString'));
    }

    /**
     * Get the underlying string from a GraphQL literal and throw if Literal is not a string.
     *
     * @throws Error
     */
    public static function extractStringFromLiteral($valueNode): string
    {
        if (!$valueNode instanceof StringValueNode) {
            throw new Error(
                "Query error: Can only parse strings got: {$valueNode->kind}",
                [$valueNode]
            );
        }

        return $valueNode->value;
    }

    /**
     * Convert the value to a string and throw an exception if it is not possible.
     *
     * @throws \Exception of type $exceptionClass
     */
    public static function coerceToString($value, string $exceptionClass): string
    {
        if (!self::canBeString($value)) {
            $safeValue = GraphQLUtils::printSafeJson($value);

            throw new $exceptionClass(
                "The given value {$safeValue} can not be serialized."
            );
        }

        return strval($value);
    }
}
