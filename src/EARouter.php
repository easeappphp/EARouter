<?php

declare(strict_types=1);

namespace EARouter;

use EARouter\RouterInterface;
use function glob;
use function explode;
use function basename;
use function stripos;
use function is_array;
use function count;
use function mb_strlen;
use function filter_var;
use function implode;
use const FILTER_SANITIZE_STRING;

/**
 * EARouter Class
 *
 */
 
class EARouter implements RouterInterface
{
	private $routerInterface;
	private $routes = array();
	private $singleFileNameExploded = array();
	private $singleRouteFileContent;
	private $routingRuleLength = "";
	private $uriPathParamsCollected = array();
	private $uriPathParams = array();
	private $uriPathParamsCount = 0;
	private $specificRouteParams = array();
	private $specificRouteParamsCount = 0;
	private $specificRouteValueConstructed = array();
	private $specificRouteValueImploded = "";
	
	/**
	 * Constructor
	 *
	 */
	/*public function __construct(RouterInterface $routerInterface){		
        $this->routerInterface = $routerInterface;   
    }*/
    
	
	/**
	 * Accepts Extracted Routes Array
	 *
	 * @param array $routeArray
	 * @return array
	 */
	public function getAsArray($routeArray = array())
	{
		$this->routes = $routeArray;
		
		return $this->routes;
	}
	
	/**
	 * Gets the Routes Array from a Single Route File
	 *
	 * @param string $filePath
	 * @return array
	 */
	public function getFromSingleFile($filePath)
	{
		//$routes = require __DIR__.'/routing-engine-rules.php';
		$this->routes = require $filePath;
		return $this->routes;
	}

	/**
	 * Gets the Routes Array from Multiple Route Files, that resides in a Single Route Folder. This method will read only PHP Files with Route info in an array, and specifically does not read those Route files, that have spaces in the filename.
	 *
	 * @param string $folderPath
	 * @return array
	 */
	public function getFromSingleFolder($folderPath)
	{
		foreach (glob($folderPath . "/*.php") as $singleFilePath) {
			
			$this->singleRouteFileContent = "";
			
			$this->singleFileNameExploded = explode(".", basename($singleFilePath));
			
			if (stripos($this->singleFileNameExploded[0], " ") === false) {
				
				$this->singleRouteFileContent = require $singleFilePath;
				
				if (is_array($this->singleRouteFileContent) && count($this->singleRouteFileContent) > 0) {
					
					foreach($this->singleRouteFileContent as $key => $content){
						$this->routes[$key] = $content;
					}
				}
				
			}
			
		}
		
		return $this->routes;
	}

	/**
	 * Gets the Routes Array from Multiple Route Files, which list is provided as a numeric index array. This method will read only PHP Files with Route info in an array, from given paths.
	 *
	 * @param  array  $filepathsArray
	 * @return array
	 */
	public function getFromFilepathsArray($filepathsArray)
	{
		foreach ($filepathsArray as $singleFilePath) {
			
			$this->singleRouteFileContent = "";
			
			$this->singleFileNameExploded = explode(".", basename($singleFilePath));
			
			if (stripos($this->singleFileNameExploded[0], " ") === false) {
				
				$this->singleRouteFileContent = require $singleFilePath;
				
				if (is_array($this->singleRouteFileContent) && count($this->singleRouteFileContent) > 0) {
					
					foreach($this->singleRouteFileContent as $key => $content){
						$this->routes[$key] = $content;
					}
				}
				
			}
			
		}
		
		
		
		return $this->routes;
	}
	
	/**
	 * Match a Route, from Routes Array and based on provided URL Parameters.
	 *
	 * @param  array   $routesArray
	 * @param  string  $uriPath
	 * @param  array   $queryStringArray
	 * @param  string  $receivedRequestMethod
	 * @param  string  $configuredMaxRouteLength
	 * @return array
	 */
	public function matchRoute($routesArray, $uriPath, $queryStringArray, $receivedRequestMethod, $configuredMaxRouteLength)
	{
		if (mb_strlen(filter_var($uriPath, FILTER_SANITIZE_STRING))<$configuredMaxRouteLength) {
			
			$this->uriPathParams = $this->getUriPathParams($uriPath);
			
			$this->uriPathParamsCount = count($this->uriPathParams);
			
			foreach($routesArray as $key => $routeArray) {
				
				if (isset($routeArray['route_value'])) {
					
					$this->specificRouteParams = array();
					
					//Get Route Value example: /resume-name/:routing_eng_var_2
					$this->specificRouteParams = $this->getUriPathParams($routeArray['route_value']);
					
					$this->specificRouteParamsCount = count($this->specificRouteParams);
					
					if (stripos($routeArray['route_value'], ":routing_eng_var_") !== false) {
						
						$this->specificRouteValueConstructed = array();
						
						foreach($this->specificRouteParams as $k => $v) {
							
							if ($v == ":routing_eng_var_" . $k) {
								
								$this->specificRouteValueConstructed[] = $this->uriPathParams[$k];
								
							}  else {
								
								$this->specificRouteValueConstructed[] = $v;
								
							} 
						}
						
						$this->specificRouteValueImploded = implode("/", $this->specificRouteValueConstructed);
						
					} else {
						
						$this->specificRouteValueImploded = $routeArray['route_value'];
						
					}
					
					if (($this->specificRouteValueImploded === $uriPath) && ($this->specificRouteParamsCount == $this->uriPathParamsCount)) {	
						
						if (isset($routeArray['allowed_request_methods'])) {
							
							if (in_array($receivedRequestMethod, $routeArray['allowed_request_methods'], true)) {
								
								if ($receivedRequestMethod === 'ANY') {
									
									return [

										'matched_route_key' => $key,
										'matched_page_filename' => $routeArray['page_filename'],
										'received_request_method' => $receivedRequestMethod,
										'allowed_request_methods' => ['ANY']
										
									];
									
								} else {
									
									return [
	
										'matched_route_key' => $key,
										'matched_page_filename' => $routeArray['page_filename'],
										'received_request_method' => $receivedRequestMethod,
										'allowed_request_methods' => $routeArray['allowed_request_methods']
										
									];
									
								}								
								
							} else {
								
								return [

									'matched_route_key' => "header-response-only-405-method-not-allowed",
									'matched_page_filename' => "header-response-only-405-method-not-allowed.php",
									'received_request_method' => $receivedRequestMethod,
									'allowed_request_methods' => $routeArray['allowed_request_methods']
									
								];
								
							}
							
						}
						
						//The value of allowed_request_methods of $routeArray['allowed_request_methods'] is invalid, so, return, bad request in headers response only scenario.
						//$_SESSION["allowed_http_method_request"] = $routeArray['allowed_request_method'];
						return [

							'matched_route_key' => "header-response-only-405-method-not-allowed",
							'matched_page_filename' => "header-response-only-405-method-not-allowed.php",
							'received_request_method' => $receivedRequestMethod,
							'allowed_request_methods' => $routeArray['allowed_request_methods']
							
						];
						
						
					}
				}
				
			}
			
		}
		
		return [
			
			'matched_route_key' => "header-response-only-404-not-found",
			'matched_page_filename' => "header-response-only-404-not-found.php",
			'received_request_method' => $receivedRequestMethod,
			'allowed_request_methods' => $routeArray['allowed_request_methods']
			
		];
	}
	
	/**
	 * Gets the URI Path Params (path of the url, before query string, in $_SERVER["REQUEST_URI"]), from URI Path input.
	 *
	 * @param  string  $uriPath
	 * @return array
	 */
	public function getUriPathParams($uriPath)
	{
		$this->uriPathParamsCollected = explode('/', $uriPath);
		
		return $this->uriPathParamsCollected;
	}
			
}
?>