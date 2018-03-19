<?php

namespace Justimmo\Api\Request;

use Justimmo\Api\Entity\Employee\Employee;

/**
 * @method $this filterByWithRealties($value)
 * @method $this sortByName($direction = BaseApiRequest::ASC)
 * @method $this sortByNumber($direction = BaseApiRequest::ASC)
 * @method $this sortByFirstName($direction = BaseApiRequest::ASC)
 * @method $this sortByLastName($direction = BaseApiRequest::ASC)
 * @method $this withName()
 * @method $this withNumber()
 * @method $this withFirstName()
 * @method $this withLastName()
 * @method $this withSuffix()
 * @method $this withPosition()
 * @method $this withBiography()
 * @method $this withMobile()
 * @method $this withPhone()
 * @method $this withFax()
 * @method $this withEmail()
 * @method $this withAddress()
 * @method $this withWebsite()
 * @method $this withProfilePicture()
 * @method $this withPictures()
 * @method $this withLinks()
 * @method $this withRealtyIds()
 * @method $this withEmployeeCategories()
 */
class EmployeeRequest extends BaseApiRequest
{
    const FIELDS = [
        'name',
        'number',
        'firstName',
        'lastName',
        'suffix',
        'position',
        'biography',
        'mobile',
        'phone',
        'fax',
        'email',
        'address',
        'website',
        'profilePicture',
        'pictures',
        'links',
        'realtyIds',
        'employeeCategories',
    ];

    const SORTS = [
        'name',
        'number',
        'firstName',
        'lastName',
    ];

    const FILTERS = [
        'withRealties',
    ];

    /**
     * @inheritDoc
     */
    public function getPathPrefix()
    {
        return '/employees';
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass()
    {
        return Employee::class;
    }
}
