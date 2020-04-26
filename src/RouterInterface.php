<?php

declare(strict_types=1);

namespace EARouter;

/**
 * RouterInterface
 *
 */

interface RouterInterface
{
    public function getAsArray(array $routeArray);
	public function getFromSingleFile(string $filePath);
	public function getFromSingleFolder(string $folderPath);
	public function getFromFilepathsArray(array $filepathsArray);
	public function matchRoute(array $routesArray, string $uriPath, array $queryStringArray, string $receivedRequestMethod, string $configuredMaxRouteLength);
	public function getUriPathParams(string $uriPath);
}

?>