# PHP based API Client for CodeDx

### PHP HTTP Client
```
http://phphttpclient.com
```

Installation
------------
Install Composer (for PHP HTTP Client)
```shell
$ curl -sS https://getcomposer.org/installer | php
$ sudo mv composer.phar /usr/local/bin/composer
```

1. `git clone` the app and `cd` into it
2. run `composer install`
3. Open `index.php` and supply `[Hostname]` and `API-Key` for CodeDx connections (see [General API Usage](#general-api-usage) for details)
4. Make sure `api-cache.array` and `settings.array` have write permissions by doing `chmod 777 api-cache.array` and `chmod 777 settings.array`. If you wish, you can use `settings.array` to declare other static variables too like hostname, tokens, etc.
5. You can run the application locally using XAMPP or any machine running a LAMP stack (although we don't need MySQL).
6. The application will be available on `localhost:8080` or simply `localhost`.

<a id="general-api-usage"></a>General API Usage
------------
```php
/*---------- Add Hostname and Token/Key ----------*/
$uri = '[Hostname]/codedx';
$template = Request::init()->addHeader('API-Key','[token/key]');
```

### List of Projects

```php
$response = Request::get($uri.'/api/projects')->send();
```

### Grouped Count URL
```
https://[Hostname]/codedx/x/projects/[ProjectID]/findings/grouped-counts
```

### Filter Object (passed as Request Body)
Filter format should match as follows:
```
'{"filter": {"status": ["new","unresolved"]},"countBy": "severity"}'
```
For more details visit [https://codedx.com/Documentation/APIGuide.html#data-filter-object](https://codedx.com/Documentation/APIGuide.html#data-filter-object)


### Response Format - JSON
Following is the response format
```
{
[Hostname]: {
                [Project Name]: {
                Info: 15456,
                Low: 449,
                Medium: 1,
                High: 246
            },
            ...
}
```
