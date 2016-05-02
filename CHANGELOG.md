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
