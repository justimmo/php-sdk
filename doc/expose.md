# Expose
The expose call of the api directly returns the content of the pdf file. The parameter is the primary key of the realty.
``` php

header('Content-type: application/pdf');
echo $api->callExpose(12345);
```