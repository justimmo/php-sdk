<?php

namespace Justimmo\Tests\Mapper\V1;

use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Model\Mapper\V1\SupportedParameters;
use Justimmo\Tests\TestCase;

/**
 * Holds the SDK to the user-defined simple fields the api sends
 *
 * A user-defined simple field is a field Justimmo adds to the OpenImmo standard, sent with its name.
 * The api's generator lists every name it sends, and SupportedParameters::USER_DEFINED_SIMPLE_FIELDS
 * holds that list. Each name must be read into a getter, or be on NOT_EXPOSED_SIMPLE_FIELDS with the
 * reason the SDK leaves it out. So a name the api adds later fails this test until it is decided,
 * instead of being lost unseen.
 */
class UserDefinedSimpleFieldsTest extends TestCase
{
    /**
     * The user-defined simple fields the api sends which the SDK deliberately does not read
     */
    private const NOT_EXPOSED_SIMPLE_FIELDS = [
        // the SDK already returns the value
        'ji_objektstatus',                      // getStatus()
        'ji_objektstatus_id',                   // getStatusId()
        'miete_ust',                            // getTotalRentVat()
        'ji_stand_vom',                         // getUpdatedAt()
        'monatliche_reparaturruecklage_netto',  // the maintenance funds in getAdditionalCosts()
        'monatliche_reparaturruecklage_ust',    // the maintenance funds in getAdditionalCosts()
        'monatliche_reparaturruecklage_brutto', // the maintenance funds in getAdditionalCosts()
        'geokoordinaten_breitengrad',           // getLatitude(), or getLatitudePrecise() when the full address is shown
        'geokoordinaten_laengengrad',           // getLongitude(), or getLongitudePrecise() when the full address is shown
        'ji_projekt_status',                    // only on a project, where Project::getProjectState() returns it
        'ji_sale_start',                        // only on a project, where Project::getSaleStart() returns it
        // no use on a website
        'ji_kundennummer',                      // the tenant id of the account which asks
        'ji_acquisition_state',                 // only on acquisitions, which are not marketed
        'moeblierung',                          // only in the feed of a single customer
        // a getter follows in this release
        'erstauftraggeberprinzip',
        'belagsfertig',
        'schluesselfertig',
        'url',
        'ji_completion_date',
        'projekt_url',
        'immocard_id',
        'immocard_firma_id',
    ];

    /**
     * Names RealtyWrapper reads by hand, outside the mapping: the two together become a
     * PoliticalDistrict
     */
    private const READ_BY_THE_WRAPPER = ['politischer_bezirk', 'politischer_bezirk_id'];

    public function testEveryNameTheApiSendsIsReadOrDeliberatelyLeftOut(): void
    {
        $decided = [...array_keys(self::mapping()), ...self::NOT_EXPOSED_SIMPLE_FIELDS, ...self::READ_BY_THE_WRAPPER];

        $this->assertSame([], array_values(array_diff(SupportedParameters::USER_DEFINED_SIMPLE_FIELDS, $decided)));
    }

    public function testTheMappingHoldsOnlyNamesTheApiSends(): void
    {
        $this->assertSame([], array_values(array_diff(array_keys(self::mapping()), SupportedParameters::USER_DEFINED_SIMPLE_FIELDS)));
    }

    public function testTheNotExposedNamesAreSentAndNotRead(): void
    {
        $this->assertSame([], array_values(array_diff(self::NOT_EXPOSED_SIMPLE_FIELDS, SupportedParameters::USER_DEFINED_SIMPLE_FIELDS)));
        $this->assertSame([], array_values(array_intersect(self::NOT_EXPOSED_SIMPLE_FIELDS, array_keys(self::mapping()))));
        $this->assertSame([], array_values(array_intersect(self::NOT_EXPOSED_SIMPLE_FIELDS, self::READ_BY_THE_WRAPPER)));
    }

    /**
     * A name which arrives both as a standard field and as a user-defined simple field fills the
     * same property either way
     */
    public function testANameInBothListsHasTheSameEntry(): void
    {
        $standard = self::constant('STANDARD_FIELD_MAPPING');

        foreach (array_intersect_key(self::mapping(), $standard) as $name => $entry) {
            $this->assertSame($standard[$name], $entry, $name);
        }
    }

    /**
     * @return array<string, array<string, string>>
     */
    private static function mapping(): array
    {
        return self::constant('USER_DEFINED_SIMPLE_FIELD_MAPPING');
    }

    /**
     * A private constant of RealtyMapper
     *
     * @return array<string, array<string, string>>
     */
    private static function constant(string $name): array
    {
        return (new \ReflectionClassConstant(RealtyMapper::class, $name))->getValue();
    }
}
