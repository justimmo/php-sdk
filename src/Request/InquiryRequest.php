<?php

namespace Justimmo\Api\Request;

use BadMethodCallException;
use Justimmo\Api\Entity\Inquiry;

/**
 * @method $this setSalutation($value) Must be an id of Salutation entity.
 * @method $this setTitle($value)
 * @method $this setFirstName($value)
 * @method $this setLastName($value)
 * @method $this setPhone($value)
 * @method $this setStreet($value)
 * @method $this setZipCode($value)
 * @method $this setCity($value)
 * @method $this setCountry($value) Must be an id of Country entity (ISO 2 Country Code).
 * @method $this setMessage($value)
 * @method $this setRealty($value) Must be an id of Realty entity.
 * @method $this setEmployee($value) Must be an id of Employee entity.
 * @method $this setCategory($value) Must be an id of Contact\Category entity.
 * @method $this setPropertyOwner($value)
 */
class InquiryRequest implements EntityRequest
{
    const AVAILABLE_FIELDS = [
        'salutation',
        'title',
        'firstName',
        'lastName',
        'phone',
        'street',
        'zipCode',
        'city',
        'country',
        'message',
        'realty',
        'employee',
        'category',
        'propertyOwner',
    ];

    protected array $formParams = [];

    /**
     * InquiryRequest constructor.
     */
    public function __construct(string $email, ?string $alternativeRecipientAddress = null)
    {
        $this->formParams['email'] = $email;

        if ($alternativeRecipientAddress !== null) {
            $this->formParams['alternativeRecipientAddress'] = $alternativeRecipientAddress;
        }
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return '/inquiry';
    }

    /**
     * @inheritDoc
     */
    public function getQuery(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return 'POST';
    }

    /**
     * @inheritDoc
     */
    public function getGuzzleOptions(): array
    {
        return [
            'form_params' => $this->formParams,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return Inquiry::class;
    }

    /**
     * Sets a parameter to be pushed as url encoded post body to the api
     * @psalm-suppress MissingParamType
     */
    public function setFormParam(string $key, $value): self
    {
        $this->formParams[$key] = $value;

        return $this;
    }

    /**
     * Fills in form params if field is available
     */
    public function __call(string $method, array $arguments = []): self
    {
        $field = lcfirst(substr($method, 3));
        if (str_starts_with($method, 'set')
            && in_array($field, self::AVAILABLE_FIELDS)
            && count($arguments) === 1
        ) {
            return $this->setFormParam($field, (string) $arguments[0]);
        }

        throw new BadMethodCallException('Method ' . $method . ' does not exist.');
    }
}
