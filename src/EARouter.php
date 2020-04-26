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
						
						if (isset($routeArray['allowed_request_method'])) {
							if ($routeArray['allowed_request_method'] == "ANY") {
								//This means, there is no restriction about the METHOD that is used for this http / https request (GET / POST / PUT / DELETE all works), if the VALUE is ANY.
								return [
	
									'matched_page_filename' => $key,
									'received_request_method' => $receivedRequestMethod,
									'original_route_rel_request_method' => $routeArray['allowed_request_method']
									
								];
							} elseif (($routeArray['allowed_request_method'] == "GET") && ($receivedRequestMethod === "GET")) {
								//This means, only requests that is initiated using GET METHOD are allowed, if the VALUE is GET.
								return [
	
									'matched_page_filename' => $key,
									'received_request_method' => $receivedRequestMethod,
									'original_route_rel_request_method' => $routeArray['allowed_request_method']
									
								];								
							} elseif (($routeArray['allowed_request_method'] == "POST") && ($receivedRequestMethod === "POST")) {
								//This means, only requests that is initiated using POST METHOD are allowed, if the VALUE is POST.
								return [
	
									'matched_page_filename' => $key,
									'received_request_method' => $receivedRequestMethod,
									'original_route_rel_request_method' => $routeArray['allowed_request_method']
									
								];
							} elseif (($routeArray['allowed_request_method'] == "PUT") && ($receivedRequestMethod === "PUT")) {
								//This means, only requests that is initiated using PUT METHOD are allowed, if the VALUE is PUT.
								return [
	
									'matched_page_filename' => $key,
									'received_request_method' => $receivedRequestMethod,
									'original_route_rel_request_method' => $routeArray['allowed_request_method']
									
								];
							} elseif (($routeArray['allowed_request_method'] == "DELETE") && ($receivedRequestMethod === "DELETE")) {
								//This means, only requests that is initiated using DELETE METHOD are allowed, if the VALUE is DELETE.
								return [
	
									'matched_page_filename' => $key,
									'received_request_method' => $receivedRequestMethod,
									'original_route_rel_request_method' => $routeArray['allowed_request_method']
									
								];
							} else {
								//The value of allowed_request_method of $routeArray['allowed_request_method'] is invalid, so, return, bad request in headers response only scenario.
								//$_SESSION["allowed_http_method_request"] = $routeArray['allowed_request_method'];
								return [
	
									'matched_page_filename' => "header-response-only-405-method-not-allowed",
									'received_request_method' => $receivedRequestMethod,
									'original_route_rel_request_method' => $routeArray['allowed_request_method']
									
								];
							}	
							
						} else {
							//The value of allowed_request_method of $routeArray['allowed_request_method'] is invalid, so, return, bad request in headers response only scenario.
							//$_SESSION["allowed_http_method_request"] = $routeArray['allowed_request_method'];
							return [

								'matched_page_filename' => "header-response-only-405-method-not-allowed",
								'received_request_method' => $receivedRequestMethod,
								'original_route_rel_request_method' => $routeArray['allowed_request_method']
								
							];
						}
						
					}
				}
				
			}
			
		}
		
		return [

			'matched_page_filename' => "header-response-only-404-not-found",
			'received_request_method' => $receivedRequestMethod,
			'original_route_rel_request_method' => $routeArray['allowed_request_method']
			
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