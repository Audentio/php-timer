# audentio/timer

Utility class for timing actions, and limiting the time actions run for.

## Installation

This library should be installed through Composer:

```shell
$ composer require audentio/timer
```

## Usage

### Limiting actions on a single request to a set period of time.

You may want to limit batched actions that are performed on a single request to prevent timeouts. For example, if you'd like to limit it to 15 seconds you can do the following:

```php
require './vendor/autoload.php';

use Audentio\Timer\Timer;

$timer = new Timer(15);

while (true) {
    // Perform some action...
    if ($timer->hasExceededLimit()) {
        break;
    }
}
```

### Timing actions

If you want to see how long actions are running for without any sort of limit you can do the following:

```php
require './vendor/autoload.php';

use Audentio\Timer\Timer;

$timer = new Timer();

sleep(15);

$duration = $timer->end();

echo 'Action time in milliseconds: ' . number_format($duration->getMilliseconds());
```