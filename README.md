# EARouter
EARouter is a Simple Request Router, that uses plain Multi-dimensional Arrays of Routes, to handle both Static &amp; Dynamic Routes for PHP based Web Applications &amp; Web Service implementations.

# Sample Routes
#Login REST API Route
'rest-login' => [
		'route_value' => '/rest/login',
		'auth_check_requirements' => 'pre-login',
		'page_filename' => 'rest-login.php',
		'redirect_to' => '',
		'route_type' => 'rest-web-service',
		'allowed_request_method' => 'POST'
	],

#My Profile REST API Route
'rest-my-profile' => [
	'route_value' => '/rest/my-profile',
	'auth_check_requirements' => 'post-login',
	'page_filename' => 'rest-my-profile.php',
	'redirect_to' => '',
	'route_type' => 'rest-web-service',
	'allowed_request_method' => 'POST'
],
	