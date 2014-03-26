Attachments
===========

File Urls
----------
```php
//gets a url to an attachment size, but only if the api returned that size
$attachment->getUrl('big');

//gets a url to an attachment size, even if the api does not return that size. BEWARE: this method cannot ensure that the url is a valid ressource
$attachment->calculateUrl('big');
```

Available Picture Sizes
-----------------------
#### unbranded
<dl>
    <dt>small</dt>
    <dd>100x300</dd>

    <dt>s220x155</dt>
    <dd>220x155</dd>

    <dt>s312x208</dt>
    <dd>312x208</dd>

    <dt>medium_unbranded</dt>
    <dd>630x480</dd>

    <dt>big_unbranded</dt>
    <dd>800x600</dd>

    <dt>big2_unbranded</dt>
    <dd>950x713</dd>
</dl>

#### branded

<dl>
    <dt>medium</dt>
    <dd>630x480</dd>

    <dt>big</dt>
    <dd>800x600</dd>

    <dt>big2</dt>
    <dd>950x713</dd>
</dl>

