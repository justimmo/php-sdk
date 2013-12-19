Logging
=======
The SDK follows the <a href="http://www.php-fig.org/psr/psr-3/" target="_blank">PSR-3 Logger Interface</a>. A simple way to implement it, is to use <a href="https://github.com/Seldaek/monolog" target="_blank">Monolog</a>
``` php
<?php
use Justimmo\Cache\NullCache;
use Justimmo\Api\JustimmoApi;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\NativeMailerHandler;

$log = new Logger('JustimmoApi');
$log->pushHandler(new StreamHandler('path/to/api_debug.log', Logger::DEBUG));
$log->pushHandler(new NativeMailerHandler('me@me.com', 'Error occured while using justimmo api', 'no-reply@my-project.com', Logger::ERROR));

$api = new JustimmoApi('username', 'password', $log, new NullCache());
```
