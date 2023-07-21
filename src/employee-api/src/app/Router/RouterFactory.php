<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
		//$router->addRoute('<presenter>/<action>[/<id>]', 'Home:default');
		$router->addRoute('users', 'Users:index');
		$router->addRoute('user', 'Users:CreateUser');
		$router->addRoute('user/<id>', 'Users:DeleteUser');
		return $router;
	}
}
