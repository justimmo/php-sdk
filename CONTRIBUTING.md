# Contributing

## The SDK and the api's contract

The api describes itself in `data/sdk-supported-parameters.json`. The json is generated from the
api's specification, and the api team sends a new one when the api changes. It lists:

- every endpoint, with the filters and parameters it accepts;
- the endpoints and the filter forms the api deprecates;
- the undocumented endpoints, which the SDK must not send (`undocumented_endpoints`).

The SDK's side is written by hand:

- `JustimmoApi::ENDPOINTS` says which SDK method sends which endpoint. It is the only place that
  says so;
- every filter, sort and basic-data parameter has a real method, like `filterByPrice()`. A real
  method can be marked `@deprecated` and can emit a notice; a magic one cannot.

`make supported-parameters` turns the json into `src/Justimmo/Model/Mapper/V1/SupportedParameters.php`,
keyed by the SDK methods of `JustimmoApi::ENDPOINTS`. The SDK never reads the json at runtime.

Tests compare the two sides: `ContractTest`, `QueryMethodsTest` and `SupportedParametersTest`. Each
failure says what is wrong, what to do, and which section below explains it.

## Refreshing the json

1. Copy the new json to `data/sdk-supported-parameters.json`.
2. Run `make supported-parameters`. It warns about every endpoint no SDK method sends, and writes
   the file anyway.
3. Run `make test`. Every failure names a section below.
4. Commit the json and `SupportedParameters.php` together.

## The api adds an endpoint

1. Add a method to `JustimmoApiInterface`, `JustimmoApi` and `JustimmoNullApi`. In `JustimmoApi` it
   sends its request with `$this->callEndpoint(__FUNCTION__, $params)`.
2. Add the method and its path to `JustimmoApi::ENDPOINTS`, written as the json writes it, like
   `/objekt/list`.
3. Give the query a method that calls it, like a `find…()` method on `BasicDataQuery`.
4. Run `make supported-parameters`, then `make snapshots` (see "The snapshots").

If the SDK must not offer the endpoint, ask the api team to list it in `undocumented_endpoints`.

## The snapshots

`tests/Fixtures/` holds three snapshots: every public signature, every request the SDK sends, and
every value a getter returns. Never edit them by hand. After a change to the code, run
`make snapshots`, which rewrites all three, and review their diff before you commit it: every
changed line is something an integrator gets differently. The normal test run never rewrites them,
so a change nobody intended fails there.

## The api drops an endpoint

The api deprecates an endpoint first, and keeps it in the json, marked `"deprecated": true`, until
the SDK's next major version. So the SDK method stays, and sites that call it keep working:

1. In a 1.x release, deprecate the method and the query method that calls it, as in "The api
   deprecates an endpoint".
2. In the next major version, remove them and their entry in `JustimmoApi::ENDPOINTS`. From then on,
   the api may drop the endpoint from the json.

If the json lacks an endpoint which `JustimmoApi::ENDPOINTS` still has, the generator stops and
`ContractTest` fails. Do not remove the method to make them pass: ask the api team to put the
endpoint back, marked deprecated.

If the endpoint was only renamed, change its path in `JustimmoApi::ENDPOINTS`.

## The api deprecates an endpoint

The json marks it `"deprecated": true`, and `SupportedParameters::DEPRECATED_CALLS` lists its SDK
method. Deprecate the method in `JustimmoApiInterface` and `JustimmoApi`, and the query method that
calls it:

1. Tag each `@deprecated`, with the replacement.
2. Call `$this->triggerDeprecation('method', 'method() is deprecated and is removed in 2.0.0. Use …', 'x.y.z')`
   with the version of the release. The notice is emitted once per class and method per request.
   In `JustimmoApi`, call `$this->deprecateUnlessTheFinderCalls()` instead: a site which calls the
   query method then gets one notice, not two.
3. Add a "Deprecated" line to the CHANGELOG.

## The api adds a filter or a parameter

1. Add a real method to the query: `filterBy…()` calls `$this->applyFilter()`, `orderBy…()` calls
   `$this->setOrder()`, a basic-data method calls `$this->setParameter()`.
2. Add the name to the query's mapper.
3. A parameter the SDK sends by itself, like the culture, needs no method.

## The api drops a filter or a parameter

The method stays until the next major version. In a 1.x release, tag it `@deprecated` and call
`$this->triggerDeprecation()`, as in "The api deprecates an endpoint". The next major version
removes it.

## The api deprecates a filter

The json marks the deprecated forms of the filter, like `"deprecated": ["range"]`. A form is
`plain` (`filter[x]`), `range` (`filter[x_von]` and `filter[x_bis]`) or `list` (`filter[x][]`).
`["list", "plain"]` deprecates the whole filter.

Deprecate the method as in "The api deprecates an endpoint". If only some forms are deprecated, emit
the notice only when one of them is used. `QueryMethodsTest` calls the method once per form and
checks it.

## The api deprecates a parameter

The json marks the deprecated forms of the parameter: `["plain"]` for the parameter itself, `["list"]`
for its array notation only. Deprecate the method which sets it as in "The api deprecates an
endpoint", and emit the notice only when a deprecated form is used. `ContractTest` checks it for
basic data.
