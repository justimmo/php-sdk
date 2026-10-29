## 1.6.0
* Every failed call throws an exception which implements `JustimmoException`, so `catch (JustimmoException $e)` catches them all
    * A response body which is not XML, for example an empty body or one which was cut off, throws the new `Justimmo\Exception\InvalidResponseException`. It threw a plain `\Exception` before, and a broken body also raised PHP warnings. The warnings are gone. The new class extends `\Exception`, so an existing `catch (\Exception $e)` still catches it
    * [BC] `findIds()` throws `InvalidResponseException` when the body is not a JSON list. It returned `null` for a body which is not JSON, as if the api had found nothing, and an object or `null` for JSON which is not a list
    * A call which gets no answer, for example after a timeout, throws the new `Justimmo\Exception\ConnectionException`. It extends `\InvalidArgumentException`, which was thrown before, so an existing catch still catches it
* `JustimmoNullApi::callRealtyIds()`, `callProjectIds()` and `callEmployeeIds()` return `'[]'`, so `findIds()` returns an empty array. The realty call returned `'{}'`, which `findIds()` turned into an empty object, and the other two an XML declaration, which is not what an ids call answers

## 1.5.0
* Upgrading from 1.4 needs no change. Every change below is additive or fixes a call which did not do what it said
    * `orderBy*()` calls which the api answered with a 400 now sort, and `orderBy('UpdatedAt')` sorts by the same column as `orderByUpdatedAt()`
    * An ids call no longer adds `culture`, and a realty detail call no longer sends what `allProjectRealties()` set. The SDK caches a response under its url, and these urls change. So after the upgrade, each such call is fetched from the api once, and cached again
* Preparing for 2.0.0 needs changes. Strict mode is new in 1.5.0, and you switch it on yourself. In 2.0.0 it is always on, and the switch is removed. Lines marked [Strict] below take effect only while strict mode is switched on in 1.5.0, and so do the lines under them. In 2.0.0 they are always on
    * Switch strict mode on with `$api->setStrict(true)` and fix what it rejects. What strict mode rejects in 1.5.0, 2.0.0 will reject too
    * Pass a range to the six methods which only have one. `filterByRooms(3)` becomes `filterByRooms(['min' => 3, 'max' => 3])`. The six are listed under strict mode below
    * Replace the two deprecated methods, `filterByRealtyCategory()` and `RealtyQuery::filterByCreatedAt()`. Both are removed in 2.0.0
* Two interfaces are added
    * `Justimmo\Pager\PagerInterface` — the public surface of a list result, implemented by `ListPager`
    * `Justimmo\Model\ModelInterface` — marks an entity the api returns, implemented by `Realty`, `Project` and `Employee`. It has no methods
* `Justimmo\Model\Mapper\SupportedParametersInterface` is added. A mapper lists the filters and parameters the api supports for each query call
    * `getSupportedFilters()` and `getSupportedParameters()` return the lists, and `getSpecVersion()` returns the version of the api specification they come from. All three work with strict mode off
    * The lists inform, they do not restrict. With strict mode off, every filter is sent as in 1.4.2, whether it is on the list or not
    * [Strict] In strict mode, a filter, a value or a picture size which the list does not allow is refused with an exception instead of being sent
* Twenty-six setters on `Realty`, `Project`, `Employee`, `Attachment` and `RealtyInquiryRequest` return `$this`, as the other setters of those classes already did
    * A call which returned `null` now returns the object, so it can be chained
* Public methods document what they take and what they return
    * The `@param` and `@return` tags show the types 2.0.0 will declare natively
* `RealtyQuery::orderByRooms()` and `RealtyQuery::orderByPublishedAt()` are added
* `orderBy('UpdatedAt')` sorts by `updated_at`, the same as `orderByUpdatedAt()`
    * It sent `aktualisiert_am` before, which the api answers with a 400. `filterByUpdatedAt()` is unchanged
* Two methods are deprecated. Both still work and are removed in 2.0.0
    * `filterByRealtyCategory()` on both queries. Use `filterByTag()`, which builds the same filter
    * `RealtyQuery::filterByCreatedAt()`. The api has no `created_at` filter, so the search has always come back unfiltered. Strict mode rejects it
* `JustimmoApi::getSdkVersion()` and `appendSdkAgent()` are `protected` rather than `private`
    * A subclass which replaces `createRequest()` with its own transport can call them, and keeps the SDK's User-Agent token and version header
* Builders are added for filters the api documents
    * `RealtyQuery` gains `filterByCountryId()` and `filterByOwnerId()`
    * `ProjectQuery` gains `filterByBuy()`, `filterByRent()`, `filterByPrice()`, `filterByRooms()`, `filterByZipCode()`, `filterByPropertyNumber()`, `filterByRealtyTypeId()`, `filterBySubRealtyTypeId()`, `filterByStatusId()`, `filterByOwnerId()`, `filterByArea()`, `filterByLivingArea()`, `filterByFloorArea()`, `filterBySurfaceArea()` and `filterByProjectPropertyNumber()`. Except the last one, each has the same name and meaning as on `RealtyQuery`
* Sorting has a mapping of its own
    * `Justimmo\Model\Mapper\OrderMappingInterface` carries the sort column names. A mapper written outside the SDK does not need it, and keeps sorting by its filter names
    * `ProjectQuery::orderByCreatedAt()` and `orderByUpdatedAt()` now sort. Before, they sent a name the api does not sort by
* Parameters are no longer sent to endpoints which do not accept them
    * An ids call no longer adds `culture`. A culture you set yourself is still sent
    * A realty detail call no longer sends what `allProjectRealties()` set. The picture size is the only setting it takes from the query
    * The SDK caches a response under its url, and these urls change. So after the upgrade, each such call is fetched from the api once, and cached again
* The strict mode switch is added. It is off by default. 2.0.0 removes it because strict mode is always on there
    * `$api->setStrict(true)` switches it on for every query built on that api, `$query->strict()` for one query, and `$query->strict(false)` switches one query back off
    * `Justimmo\Api\StrictModeInterface` carries the flag. An api class written outside the SDK does not need it and is never strict
    * It makes no difference whether `strict()` is called before or after the filters
    * Two shapes are only caught while strict mode is already on: a key other than `min` and `max` in a range, and an object with no `__toString()`
* [Strict] Strict mode rejects a request the api would silently ignore, instead of sending it
    * `filter()`, `setPicturesize()`, `find()`, `findOne()`, `paginate()`, `findPk()` and `findIds()` declare the exceptions they throw in strict mode. In 2.0.0 they throw them whenever a rule is broken
    * A filter the api does not have throws `MethodNotFoundException`. Before, the api ignored it and answered with an unfiltered list, so a typo looked like a working search
    * The message names `filterByFoo()` when that method was called, or the parameter given to `filter()`
    * The wire names of a range, `filter[preis_von]` and `filter[preis_bis]`, are accepted next to the `['min' => …, 'max' => …]` form
    * `filterByCreatedAt()` throws, because the api has no `created_at` filter
    * `AbstractQuery::assertFilterIsSupported()` is `protected`, so a query subclass can check a filter name the same way
    * `orderBy*()` is not checked, and an endpoint without filters rejects nothing
    * A value a filter cannot take throws `InvalidFilterValueException`, which is new. It extends `InvalidArgumentException` and implements `JustimmoException`. The message names what the api accepts, and `getFilter()` and `getValue()` return what was rejected
    * A plain value is rejected on a filter which only has a range. `filterByPrice(500000)` came back unfiltered. Six methods only have a range: `filterByPrice()`, `filterByRooms()`, `filterByArea()`, `filterByLivingArea()`, `filterByFloorArea()` and `filterBySurfaceArea()`. All six take `['min' => …, 'max' => …]`
    * A list is rejected on a filter which takes a single value, and so are a list inside a list, a min or a max on a filter which has no range, and a list as either end of a range
    * A key other than `min` and `max` in a range is rejected. `['min' => 1, 'von' => 2]` searched on the `min` alone
    * An object with no `__toString()` is rejected. `filterByCompletionDate(new DateTime(…))` came back unfiltered. `null`, an empty string and an empty array are still dropped silently, as an omitted value
    * A value outside the values a filter allows is rejected. `true`, `1` and `"1"` count as the same value
    * A picture size the endpoint does not have throws `InvalidFilterValueException`. Before, the api replaced it with a default size without saying so. A picture size is not checked on an `EmployeeQuery`
    * `all()` together with a realty-level filter throws `InvalidFilterValueException`. The api drops the realty-level filters when `all()` is set, so the search returned every project. `all()` alone, or with a project-level filter such as `filterByProjectTag()`, is not affected

## 1.4.2
* Bring the code to current PHP conventions. No behaviour changes
    * `array()` becomes `[]` across src/ and tests/, and the one `list()` becomes a short destructuring
    * `isset($x) ? $x : $y` becomes `$x ?? $y` in Attachment and the wrappers. The two forms are the same operation
    * SimpleXMLElement and DateTime are imported rather than written inline, with their docblocks adjusted
    * setMethods() becomes onlyMethods() in the test suite, deprecated in phpunit 9 and removed in 10
* Add a php-cs-fixer config plus `make cs` and `make cs-fix`, so the style is enforced rather than relitigated

## 1.4.1
* [Website API] Stop sending what the api rejects or ignores
  * filter() no longer sends an empty value, and drops an empty element of an array value. `0`, `'0'` and `false` are values and are kept. A search form which submitted one of three empty inputs sent filter[plz][]=1010&filter[plz][]=, which the api answers with a 422
  * filter() no longer lets an object be serialised by its public properties. One which implements __toString is sent as its string, any other is dropped
  * findIds() no longer sends limit and offset, which the ids endpoints do not support. A setLimit() before findIds() was silently ignored and every id was returned
  * setPicturesize() no longer appends "medium" next to a size it does not recognise, so the caller no longer receives a picture size they did not ask for. A call which leaves no usable size still sends medium
  * RealtyQuery.filterByEquipment() wraps a single value in an array, which the api requires. It answered a scalar with a 422
  * filterByTag() and filterByRealtyCategory() build the same filter[tag_name], so they now add to it instead of the second discarding the first. A single value is still sent as a scalar
  * setLimit() and setOffset() clamp a negative argument, so the negative offset fixed in paginate() in 1.3.2 can no longer be produced through the setters either. Nothing else is clamped: the documented range of limit is the api's to enforce, so 0 and a value above the documented maximum are passed on, as is a non numeric value, which the api answers with a 422 the caller can see
  * orderBy*() passes the sort direction on as written instead of rewriting anything unknown to "asc". The api matches asc and desc case insensitively, so "DESC" reversed the sort the caller asked for
  * generateUrl() no longer appends a bare "?" for parameters which serialise to nothing. This changes the cache key of such a call once
* [BC] AbstractQuery.$pictureSizes is deprecated and no longer consulted. The api validates the picture size itself. Removed in 2.x

## 1.4.0
* [Website API] Handle status code 422 of the migrated objekt and projekt endpoints
  * New ValidationException, thrown instead of the general InvalidRequestException
  * ValidationException.getViolations() returns the single validation failures with their property path
  * The message of the api is read from the error body, in xml and in json
* [Website API] Keep the message of the sdk for 401 and 404, whose bodies carry no details
* Add CurlRequestInterface, implemented by CurlRequest and returned by JustimmoApi.createRequest().
  In 2.x the method declares the interface as its native return type, so subclasses which override it
  should already return an implementation of it
* Replace deprecated `(double)` casts with `(float)` — every parsed response raised E_DEPRECATED on php 8.5 - thx @hinnerk-a
* Trim whitespace from text nodes, so attachment urls from pretty printed responses are valid urls - thx @hinnerk-a
* `Attachment.getUrl` returns null for a size the api did not return without emitting a php warning. Add `Attachment.getUrlOrFail`, which throws `AttachmentSizeNotFoundException` instead - thx @hinnerk-a
* Map the contact person's `firma` and `land` to `Employee.company` and `Employee.country` - thx @hinnerk-a
* [BC] InvalidRequestException.setResponse() has a second, optional $contentType argument. Subclasses which override it have to adopt the signature
* [BC] Deprecate ProjectQuery.filterByCountryIso2(). The api has no filter[land_iso2], the method will be removed in 2.x

## 1.3.4
* The User-Agent of the sdk is appended to a User-Agent set by the integrator instead of yielding to
  it, so a request stays attributable even when the caller sets an agent of their own. A User-Agent
  is a list of product tokens, so the agent of the integrator stays first and intact. Both ways of
  setting it are covered, CURLOPT_USERAGENT and a User-Agent entry in CURLOPT_HTTPHEADER, since curl
  lets the header win over the option. A User-Agent header without a value removes the header in
  curl and is left untouched

## 1.3.3
* Identify the requests of the sdk with the header X-Justimmo-PHP-SDK-Version and with a User-Agent.
  The version is read from the composer installation of the package

## 1.3.2
* Fix paginate() sending a negative offset for page numbers below 1
* Fix findPoliticalDistricts() calling the regions endpoint. It parsed a regions response for politischer_bezirk elements, which never exist there, so it always returned an empty array.

## 1.3.1
* Extend energy pass entity with eeb value and class.

## 1.3.0
* Add support for political districts
  * BasicDataQuery.findPoliticalDistricts
  * RealtyQuery.filterByPolticialDistrictId
  * Realty.getPoliticalDistrict
* Deprecate regions in favor of political districts
* Drop support for php 7.x and 8.0
* Bump phpunit to 9.5
* [CI] Add jobs to run tests with php 8.4 and 8.5
* [CI] Add static analysis with phpstan

## 1.2.23
* Add suffix to RealtyInquiry

## 1.2.22
* [Picture Sizes] Added uhd_unbranded, uhd_downscale and uhd_unbranded_downscale

## 1.2.21
* [Picture Sizes] Renamed 4K to UHD

## 1.2.20
* Picture Sizes] Added 4K
 
## 1.2.19
* Add missing attachment descriptions to list and project calls.

## 1.2.18
* Extending Attachment Entity with description.

## 1.2.16
* Extend realty entity with rent_with_purchase_option.

## 1.2.15
* Add salutation to Employee by @ZuzanaMuellner-JustImmo

## 1.2.14
* Add support for webp images - thx @ManuelSzecsenyi
* Fix type of surface area - thx @infabo
* Allow filtering by project id - thx @infabo
* Support "objektIds" parameter - thx @infabo
* CI - Fix composer using wrong php version to install dependencies
* CI - Add test runners for php 8.1, 8.2 and 8.3
* Add support for psr/log 2.x and 3.x

## 1.2.13
* Add funding Inquiry ("funding_inquiry") to RealtyInquiryRequest
 
## 1.2.12
* Add company ("firma") to RealtyInquiryRequest

## 1.2.11
* Bugfix
  * Fix type of parameter "numeric_prefix" of http_build_query function

## 1.2.10
 * Bugfix
   * fixes BasicDataWrapper::transformZipCodes will always use auto incremented array key ID instead of the JI related zip code ID.

## 1.2.9
 * Extends RealtyInquiryRequest with
   * IsRealtyOwner (Anfragesteller ist Eigentümer einer Immobilie)
   * RealtyOwnerConsultationRequest (Anfragesteller ist Eigentümer und wünscht Beratung)

## 1.2.8
 * Bugfix
   * Fixes a bug that picture size "orig" in Attachment::getData() would be overwritten by another size under certain circumstances

## 1.2.7
 * Extends findPk method in abstract query for project queries
   * it needs to accept the parameter `alleProjektObjekte` to be able to list sub real-estates of a given project with status STATUS_PROCURED (8) and STATUS_EXTERNAL_PROCURED (10)

## 1.2.6
 * Extends Realty object with
   * SalesArea (Verkaufsfläche)
   * HallHeight (Hallenhöhe)
   * ShortTermArea (Befristete Fläche)
   * WeightedArea (Gewichtete Fläche)
   * UndevelopedAtticArea (Rohdachbodenfläche)
 * Bugfix
   * Fixing RentDurationType to support unlimited (unbefristet) renting period

## 1.2.5
 * Add support for picturesize parameter in employee calls

## 1.2.4
 * Add support for newsletter registration
 * Fix multiple phpdoc types - thx @infabo

## 1.2.0
 * Add support for new attachment urls

## 1.1.27
 * Add support for ownership types

## 1.1.26
 * Add freetext3 and freetext4 fields to project
 * Added getVideoPosterUrl() helper to Attachment

## 1.1.25
 * Add properties for fuzzy coordinates (getLatitudeFuzzy, getLongitudeFuzzy, getRadiusFuzzy)

## 1.1.24
 * Added freetext4 field

## 1.1.22
 * Handles the group element of the general realty and residential project links

## 1.1.20
 * Add residential project aggregations

## 1.1.18
 * Add support for realty calls
   * filterByCellarCount()
   * filterByGarageCount()
   * filterByParkingCount()
   * filterByToiletRoomCount()
   * filterByBathRoomCount()
   * filterByStoreRoomCount()

## 1.1.17
 * Add support for preciseAreaSearch setting

## 1.1.16
 * Fix the value of the stair for projects
 * Add support for realty calls
   * filterByCondition() 
   * filterByGardenCount()
   * filterByBalconyCount()
   * filterByLoggiaCount()
   * filterByTerraceCount()
   * filterByEquipment()
   * filterByDisabilityAccess()

## 1.1.15
 * Add support for realties and projects: getGarages() (Garages)
 * Available Functions of the model Garage
   * getType() returns outdoor_court, garage, undergroud_car_park, carport, duplex, car_park or others
   * getName()
   * getQuantity()
   * getMarketingType() returns rent or buy
   * getNet()
   * getGross()
   * getVat()
   * getVatType() returns numeric or percent
   * getVatValue() returns the monetary value of the VAT

## 1.1.14
 * Inquiry: Assign existing contact category

## 1.1.13
 * Add support for projects
   * categories / tags
   * filter parameter project_tag_name

## 1.1.12
 * Add support for getCeilingHeight in realty calls

## 1.1.11
 * Add support for realties
   * getMonthlyCosts() (Monatliche Kosten)
   * getFinancialContribution() (Finanzierungsbeitrag)
   * getInfrastructureProvision() (Aufschliessungskosten)

## 1.1.10
 * Add support for projects
   * getProjectNumber() (Projektnummer)
   * getCountry() (Land)
   * getFederalState() (Bundesland)
   * getProximity() (Nähe)
   * getTierCount() (Anzahl Etagen)
   * getAtticCount() (Anzahl Dachgeschoße)
   * getStyleOfBuildingId() (Bauart)
   * getYearOfConstruction() (Baujahr)
   * getNoiseLevel() (Lärmpegel)
   * getAvailability() (Beziehbar)
   * getEpcValidUntil() (Energieausweis gültig bis)
   * getEpcHeatingDemand() (HWB)
   * getEpcHeatingDemandClass() (HWB Klasse)
   * getEpcEnergyEfficiencyFactor() (fGEE)
   * getEpcEnergyEfficiencyFactorClass() (fGEE Klasse)
   * getCondition() (Zustand)
   * getHouseCondition() (Haus Zustand)
   * getAreaAssessment() (Lagebewertung)
   * getPropertyAssessment() (Zustandsbewertung)
   * getOccupancy() (Nutzungsart)
   * getFreetext2() (Freitext 2)
   * getCreatedAt() (Erstellt am)

## 1.1.9 
 * Add street, zip, city and url to Employee model
 * Add orientation to Realty model

## 1.1.8 
 * Fix orderByArea passing wrong area parameter to api
 * Use connection timeout instead of timeout in curl requests to allow long data transfers
 * Fix team / project ids call swap (thx @sb-relaxt-at)

## 1.1.6
 * Add support for isReference in realty calls (thx @sb-relaxt-at)

## 1.1.3
 * Add support for onlyRealtyIds in project calls
 * Add support for project state, url, sale start, completion date and is reference in project calls
 * Add support for plain rent fields without additional costs (rent net, rent gross, rent vat type, rent vat value, rent vat input and rent vat) in realty calls
 * Add support for allProjectRealties setting
 * Add support for filterByUpdatedAt in realty calls
 * Add support for employee ids and project ids call
 * Add multi attachment and link support for employees

## 1.1.2
 * Fixed a bug on servers with a different arg_separator.output setting
 * Added support for other information field to realties

## 1.1.0
 * changed versioning to semver
 * added support for commercial realties (realty_system_type, parent_id, rent_per_sqm_from, floor_area_from, operating_costs_per_sqm_from)
 * add grundriss support to projects (thx @sb-relaxt-at)

## 1.0.31
 * support for salutation and title in realtie inquiries
 * add JustimmoException interface to catch all sdk related exceptions
 * add InvalidRequestException for response error code 400 to 499 with error message parsing from API

## 1.0.30
 * filterByStatusId

## 1.0.29
 * add properties for precise coordinats (getLatitudePrecise, getLongitudePrecise)
 
## 1.0.28
 * added purchase_price_per_sqm (getPurchasePricePerSqm), rent_per_sqm (getRentPerSqm) and operating_costs_per_sqm (getOperatingCostsPerSqm) 

## 1.0.27
 * added surety_text (getSuretyText)

## 1.0.26
 * added garage_area (getGarageArea), parking_count (getParkingCount) and parking_area (getParkingArea) to realty

## 1.0.25
 * add stair to realty

## 1.0.24
 * add title to project attachments
 * add freetext1, miscellaneous, under construction and locality to project

## 1.0.23
 * fix energy pass valid until

## 1.0.22
 * fix realty categories position

## 1.0.21
 * bugfix for checking whether or not open_basedir is set ( thx @sb-relaxt-at )

## 1.0.20
 * fix datetime fields using current date if an element is empty
 * add new detail param as default for list call

## 1.0.19
 * add created_at and updated_at information for realties
 * add shortcut methods for sorting on queries

## 1.0.18
* add suffix and biography for employees
* add procuredAt for realties

## 1.0.17
* added officeArea in list mapping
* added storageArea in list mapping

## 1.0.16
* remote unused property contactEstablishmentCosts
* fix properties contractEstablishmentCosts, landRegistration, transferTax

## 1.0.15
* fix default curl options overriding custom options

## 1.0.14
* https is now the default protocol
* support for half-rooms
* support for rent duration
* support for buildable area
* additional information about realty types
* support for sub realty types and filter
* support for style of building and filter

## 1.0.13
* add support for realty status id
* update callExpose to support different expose types
* add possibility to configure curl requests made by the sdk

## 1.0.12
* add filterByArea
* add availableFrom

## 1.0.11
* add support for realty categories

## 1.0.10
* add vatValue, vatInput, vatType and optional to AdditionalCosts
* add commission to realty
* add locality to realty

## 1.0.9
* fix openbase_dir restriction warning
* add address data to RealtyInquiry

## 1.0.8
* fix boolean values in api xml

## 1.0.7
* fix engery energy efficency and themal requirement values

## 1.0.6
* fix pager maxPerPage value when created from query
* add support for api id call
* add quickfilters for rent and buy

## 1.0.5
* add new properties for realty list call

## 1.0.4
* add support for attachment groups
* add typehints for project query
* add own autoloader file

## 1.0.3
* add calculateUrl method for Attachments

## 1.0.2
* fix for filterByTag
* fix for filterByRealtyCategory
* add compensation realty property

## 1.0.1
* Add Project details
* Add missing internationalizations in Realty
* Rework api logs
* Add contractEstablishmentsCosts
* Fix ListPager division by zero
* Fix associative key/value pairs in equipment

## 1.0.0
* Initial release
