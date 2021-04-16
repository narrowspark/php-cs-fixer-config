<?php

declare(strict_types=1);

/**
 * Copyright (c) 2015-2021 Daniel Bannert
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/narrowspark/php-cs-fixer-config
 */

namespace Narrowspark\CS\Config\Tests\Constraint;

use ArrayObject;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Traversable;
use function is_array;
use function Safe\array_replace_recursive;

/**
 * Constraint that asserts that the array it is evaluated for has a specified subset.
 *
 * Uses array_replace_recursive() to check if a key value subset is part of the
 * subject array.
 */
final class ArraySubset extends Constraint
{
    /** @var mixed[] */
    private array $subset = [];

    /**
     * @param ArrayObject|iterable|mixed[]|Traversable $subset
     */
    public function __construct(iterable $subset, private bool $strict = false)
    {
        $this->subset = $this->toArray($subset);
    }

    /**
     * Evaluates the constraint for parameter $other.
     *
     * If $returnResult is set to false (the default), an exception is thrown
     * in case of a failure. null is returned otherwise.
     *
     * If $returnResult is true, the result of the evaluation is returned as
     * a boolean value instead: true in case of success, false in case of a
     * failure.
     *
     * @psalm-param mixed $other
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function evaluate($other, string $description = '', bool $returnResult = false): ?bool
    {
        // type cast $other & $this->subset as an array to allow
        // support in standard array functions.
        $arr = $this->toArray($other);
        $patched = array_replace_recursive($arr, $this->subset);

        if ($this->strict) {
            $result = new IsIdentical($patched);
        } else {
            $result = new IsEqual($patched);
        }

        $result = $result->evaluate($arr, '', true);

        if ($returnResult) {
            return $result;
        }

        if ($result === true) {
            return null;
        }

        $comparisonFailure = new ComparisonFailure(
            $patched,
            $arr,
            var_export($patched, true),
            var_export($arr, true)
        );

        $this->fail($arr, $description, $comparisonFailure);
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @throws InvalidArgumentException
     */
    public function toString(): string
    {
        $exporter = $this->exporter();

        return 'has the subset ' . $exporter->export($this->subset);
    }

    /**
     * Returns the description of the failure.
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param mixed $other evaluated value or object
     *
     * @throws InvalidArgumentException
     */
    protected function failureDescription($other): string
    {
        return 'an array ' . $this->toString();
    }

    /**
     * Transform iterables to array.
     *
     * @param ArrayObject|iterable|mixed[]|Traversable $other
     *
     * @return mixed[]
     */
    private function toArray(iterable $other): array
    {
        if (is_array($other)) {
            return $other;
        }

        if ($other instanceof ArrayObject) {
            return $other->getArrayCopy();
        }

        return iterator_to_array($other);
    }
}
