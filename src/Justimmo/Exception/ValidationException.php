<?php declare(strict_types=1);

namespace Justimmo\Exception;

/**
 * Class ValidationException
 *
 * thrown when the API rejects the input of a call, status code 422
 *
 * @package Justimmo\Exception
 */
class ValidationException extends InvalidRequestException
{
    /**
     * @var array<int, array{propertyPath: string, message: string}>
     */
    private array $violations = [];

    /**
     * @param array<int, array{propertyPath: string, message: string}> $violations
     */
    protected function handleViolations(array $violations): void
    {
        $this->violations = $violations;
    }

    /**
     * The single validation failures of the call.
     *
     * propertyPath is the property name of the API, not the name of the sent parameter,
     * eg. a rejected filter[plz] is reported as filter.zipCodes[0]
     *
     * @return array<int, array{propertyPath: string, message: string}>
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}
