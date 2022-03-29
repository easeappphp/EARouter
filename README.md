# EARouter
> EARouter is a Simple Request Router, that uses plain Multi-dimensional Arrays of Routes, to handle both Static &amp; Dynamic Routes for PHP based Web Applications &amp; Web Service implementations.


### Getting started
With Composer, run

```sh
composer require easeappphp/ea-router:^1.0.7
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
	'allowed_request_methods' => ['POST'],
			'controller_type' => 'procedural',
			'controller_class_name' => \EaseAppPHP\EABlueprint\App\Http\Controllers\ProceduralController::class,
			'method_name' => 'webHtmlOutput',
			'with_middleware' => '',
			'without_middleware' => ''
],

```



#My Profile REST API Route
```php
'rest-get-all-user-details' => [
	'route_value' => '/rest/all-user-details/get',
	'auth_check_requirements' => 'none',
	'page_filename' => 'rest-all-user-details-get.php',
	'redirect_to' => '',
	'route_type' => 'rest-web-service',
	'allowed_request_methods' => ['POST'],
			'controller_type' => 'oop-mapped',
			'controller_class_name' => \EaseAppPHP\EABlueprint\App\Http\Controllers\AllUserDetails\GetController::class,
			'method_name' => 'index',
			'with_middleware' => '',
			'without_middleware' => ''
],

```
	

## License
This software is distributed under the [MIT](https://opensource.org/licenses/MIT) license. Please read [LICENSE](https://github.com/easeappphp/PDOLight/blob/main/LICENSE) for information on the software availability and distribution.
	