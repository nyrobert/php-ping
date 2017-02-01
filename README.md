# PHP ping

PHP native implementation of ICMP ping utility. No external 
applications are called to ping the remote host; everything is 
implemented using PHP's socket functions.

## Requirements

* [PHP](http://php.net)

## Usage

Access to raw sockets on UNIX like systems requires root access.

 ```shell
  sudo php ping.php
  ```

## License

This project is licensed under the terms of the [MIT License (MIT)](LICENSE).
