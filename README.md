# EpicLog

Advanced Monolog Configuration Management for Laravel projects.

## Overview
If you prefer your logs to be separated into many different files based on log-level, then this package is for you.

The log facade `Log::` by default, sends all log messages into a single file in the `storage/logs` directory.

After installing this package, the method `Log::info()` will now write those log messages to `storage/logs/info.log` and likewise `Log::error()` would write to `storage/logs/error.log`.

We create a log file in `storage/logs` for each log level as specified by [RFC 5424](http://tools.ietf.org/html/rfc5424).

### Other cool features

**Custom Log Files** - 
You can create custom log files and easily write to them, so you can log very specific messages and not have them mixed in with the generic messages logged by the `Log` facade. 

**Send logs to stderr, stdout** -
There are simple configuration flags which will allow you to send all error level and above logs to stderr, and send all log levels to stdout.

## Installation

Installation differs slightly, depending on your framework (Laravel or Lumen). Please note the differences in each step.
Begin by adding this to your `composer.json`

```
"johnfischelli/epiclog": "dev-master"
```
Run `composer update`

#### Add the Service Provider

##### Laravel

Open up your `config/app.php` file and add `EpicLog\EpicLogServiceProvider::class` to your `providers` array.

##### Lumen

Open up your `bootstrap/app.php` file and add the following in your Providers section:
``` 
$app->register(EpicLog\EpicLogServiceProvider::class);
```


#### Add the Facade Alias

##### Laravel

Add `'EpicLog' => EpicLog\EpicLogFacade::class` to your `aliases` array, also in `config/app.php`.

##### Lumen

Also in `bootstrap/app.php` add the following anywhere before "loading the application routes":
```
class_alias(EpicLog\EpicLogFacade::class, 'EpicLog');
```

#### Publish the Configuration

##### Laravel
`php artisan vendor:publish`

##### Lumen
Manually copy the EpicLog config file `epiclog.php` from the vendor source to your Lumen config directory.

Additionally, because Lumen uses `.env` file for storing configuration, you will need to manually load the EpicLog config.
Add the following to `bootstrap/app.php` anywhere after `$app` is created.  For example:
```
$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->configure('epiclog');

```

##### Finally...
In either case, open `config/epiclog.php` and set whatever configuration options you like.

## Configuration

####`separate_logs_by_level`

If this flag is true, we alter the Laravel default Monolog config so that each log level is written to its own file. If it is false, the default Laravel Monolog config is untouched.

---

####`rotate_log_by_level`

If this flag is true, we'll setup a RotatingFileHandler instead of a default StreamHandler for each of our logs.

**NOTE:** This only applies if the separate_logs_by_level parameter is also true. This also, only applies to the logs separated by level.

___

####`rotate_log_by_level_num_days`

Specify the number of days of logs to keep for the log level files.

**NOTE:** If `rotate_log_by_level` is false, it doesn't matter what you put here - all logs will be kept indefinitely.

---

####`push_errors_to_stderr`

Set this flag to true, if you'd like to push error level and above logs
to stderr. This can be especially useful if you run a docker environment.

---

####`push_logs_to_stdout`

Set this flag to true, if you'd like to push all logs to stdout. This is especially useful if you run a docker environment.

--

####`logs`

You can configure custom log files here. We're expecting an array of arrays. Here is an example:

```
	'logs' => [
		[
			'name' => 'myCustomLog',
			'location' => storage_path("logs/myCustomLog.log"),			'level' => 'error',
			'rotate' => true,
			'num_days' => 3,
			'bubbles' => true
		],
		[...]
	]
```

The name parameter is really the only required field in this custom log array. All the other values will be filled in with defaults if not provided. Here are the defaults:

```
	[
		'location' => storage_path("logs/epiclog.log"),
		'level' => 'debug',
     	'rotate' => false, 
		'num_days' => 5,
		'bubbles' => false
	]
```

The `'bubbles'` parameter determines whether or not records handled by this logger will propogate to other handlers.


## Writing to Custom Logs

In order to write to that custom Log file, we can't use the `Log` facade, so we provide an `Epiclog` facade. Since you gave this log the name of 'myCustomLog' it can be accessed like so: `Epiclog::myCustomLog('data to log');`
