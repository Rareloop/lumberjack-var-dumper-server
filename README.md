# Lumberjack Var Dumper Server

This requires Hatchet, if you haven't already set it up for your theme follow [these instructions](https://github.com/Rareloop/hatchet/blob/master/README.md) first.

## Installation
```
composer require rareloop/lumberjack-var-dumper-server --dev
```

Once installed, register the Service Provider in config/app.php within your theme:

```php
'providers' => [
    ...

    Rareloop\Lumberjack\VarDumperServer\VarDumperServerServiceProvider::class,

    ...
],
```

## Usage
A new command is added to Hatchet, which you can run with:

```
php hatchet dump-server
```

Once running, any calls to `dump()` within you theme code will be echo'd out in the console.
