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
        $router->addRoute('employees', 'Employees:index');
        $router->addRoute('employee', 'Employees:CreateEmployee');
        $router->addRoute('employee/<id>', 'Employees:DeleteEmployee');
        $router->addRoute('employee/update/<id>', 'Employees:UpdateEmployee');
        $router->addRoute('employees/attributes/', 'Employees:GetAttributesConfig');

        return $router;
    }
}
