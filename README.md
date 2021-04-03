# EARouter
> EARouter is a Simple Request Router, that uses plain Multi-dimensional Arrays of Routes, to handle both Static &amp; Dynamic Routes for PHP based Web Applications &amp; Web Service implementations.


### Getting started
With Composer, run

```sh
composer require easeappphp/ea-router:^1.0.2
```

# Sample Routes
#Login REST API Route
```php
'rest-login' => [
	'route_value' => '/rest/login',
	'auth_check_requirements' => 'pre-login',
	'page_filename' => 'rest-login.php',
	'redirect_to' => '',
	'route_type' => 'rest-web-service',
	'allowed_request_method' => 'POST'
],

```



#My Profile REST API Route
```php
'rest-my-profile' => [
	'route_value' => '/rest/my-profile',
	'auth_check_requirements' => 'post-login',
	'page_filename' => 'rest-my-profile.php',
	'redirect_to' => '',
	'route_type' => 'rest-web-service',
	'allowed_request_method' => 'POST'
],

```
	

## License
This software is distributed under the [MIT](https://opensource.org/licenses/MIT) license. Please read [LICENSE](https://github.com/easeappphp/PDOLight/blob/main/LICENSE) for information on the software availability and distribution.
	