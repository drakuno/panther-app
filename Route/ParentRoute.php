<?php

namespace PantherApp\Route;

use PantherApp\Request;
use PantherApp\Route;
use PantherApp\Exception\AppException;

abstract class ParentRoute extends Route
{
	static public function handleWithRoutes(Request $request,array $routes)
	{
		$validRoute;
		while (empty($validRoute)&&($routeOrRouteClassName=current($routes))) {
			if (is_string($routeOrRouteClassName))
				$route	= new $routeOrRouteClassName;
			else if (is_a($routeOrRouteClassName,"PantherApp\\Core\\Route"))
				$route	= $routeOrRouteClassName;
			else
				throw new AppException("wrong-type","Array of routes can only include route instances or route class names.");

			if ($route->handles($request))
				$validRoute	= $route;
			next($routes);
		}

		if (!empty($validRoute)) {
			return $validRoute->handle($request);
		}
		else
			throw new AppException("no-matching-route","No registered route handles such request.");
	}

	public function getChildren(){ return array(); } // Return child routes.

	public function handle(Request $request)
	{
		$routes	= $this->getChildren();
		return self::handleWithRoutes($request,$routes);
	}
}