<?php

namespace Justimmo\Api\Request;

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

    /**
     * @var array
     */
    protected $formParams = [];

    /**
     * InquiryRequest constructor.
     *
     * @param string $email
     */
    public function __construct($email)
    {
        $this->formParams['email'] = $email;
    }

    /**
     * @inheritDoc
     */
    public function getPath()
    {
        return '/inquiry';
    }

    /**
     * @inheritDoc
     */
    public function getQuery()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getMethod()
    {
        return 'POST';
    }

    /**
     * @inheritDoc
     */
    public function getGuzzleOptions()
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
     *
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function setFormParam($key, $value)
    {
        $this->formParams[$key] = $value;

        return $this;
    }

    /**
     * Fills in form params if field is available
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return $this
     */
    public function __call($method, array $arguments = [])
    {
        $field = lcfirst(substr($method, 3));
        if (strpos($method, 'set') === 0
            && in_array($field, self::AVAILABLE_FIELDS)
            && count($arguments) === 1
        ) {
            return $this->setFormParam($field, $arguments[0]);
        }

        throw new \BadMethodCallException('Method ' . $method . ' does not exist.');
    }
}
