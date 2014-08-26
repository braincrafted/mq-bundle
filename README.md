BraincraftedMqBundle
==========

BraincraftedMqBundle wraps [BcMq](https://github.com/braincrafted/mq) into a nice bundle for Symfony2 to provide you with a PHP implementation of a Message Queue Sever.

By [Florian Eckerstorfer](http://florianeckerstorfer.com).


Installation
------------

The recommended way of installing BraincraftedMqBundle is through Composer.

```json
#composer.json
{
    "require": {
        "braincrafted/mq-bundle": "dev-master"
    }
}
```

You also need to add it to your `AppKernel.php`.

```php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Braincrafted\Bundle\MqBundle\BraincraftedMqBundle()
    );

    // ...

    return $bundles;
}
```

Usage
-----

BcMqBundle uses services (instead of callbacks) to consume messages. You can configure those consumers in your `config.yml`.

# app/config/config.yml

```yaml
bc_mq:
    consumers:
        write_file: acme_demo.consumer.write_file
```

Now you need to define the service `acme_demo.consumer.write_file` in your bundles service configuration. You have to write those consumers by yourself. An example is given below.

```php
<?php
// src/Bc/Bundle/MqDemoBundle/Consumer/WriteFileConsumer.php

namespace Bc\Bundle\MqDemoBundle\Consumer;

class WriteFileConsumer
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function consume($message)
    {
        file_put_contents($this->filename, $message."\n", FILE_APPEND);
    }
}
```

*Please note, that in this case `$message` is a string but you can also send more complex messages, for example, arrays. Everything that can be encoded as JSON can be sent.*

If your consumers are in place you can start the message queue server and send messages.

```bash
$ php app/console bc:mq:server -p 4000
```

It is also possible to start the server in verbose mode by using the `--verbose` or `-v` option. If the verbose mode is activated, every bit of incoming data is echoed in the console.

The server will redirect every message that is sent to port `4000` to the consumers. Each message must be a JSON object and must contain exactly two values: `type` and `message`. Type is name name of the consumer (`write_file` in the example above) and message is a string or an array.

```json
{
    "type": "write_file",
    "message": "Hello World!"
}
```

A complex message:

```json
{
    "type": "write_file",
    "message": { "text": "Hello World!", "date": "2013-05-29" }
}
```

If you want to send the messages from your Symfony app you can use the producer provided by the bundle.

```php
$producer = $container->get('bc_mq.producer');
$producer->produce('write_file', 'Hello World!');
```

The message can also be an array:

```php
$producer = $container->get('bc_mq.producer');
$producer->produce('write_file', array('text' => 'Hello World!', 'time' => time());
```

Changelog
---------

### Version 0.4 (2013-11-16)

- Changed namespace to `Braincrafted`
- Fixed bug with empty callback in server

### Version 0.3 (2013-07-05)

- Updated `braincrafted/mq` to version 0.3
- Incoming data is echoed in verbose mode

### Version 0.2 (2013-06-04)

- Updated `braincrafted/mq` to version 0.2 (compatible with Symfony 2.3)

### Version 0.1.1 (2013-06-04)

- Fixed dependencies

### Version 0.1

- Initial release


License
-------

```
The MIT License (MIT)

Copyright (c) 2013 Florian Eckerstorfer

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
```


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/braincrafted/mq-bundle/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

