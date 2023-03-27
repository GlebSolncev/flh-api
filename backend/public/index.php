<?php
declare(strict_types=1);

header("Access-Control-Allow-Origin: http://localhost:8080");


use App\Controllers\SkillController;
use Illuminate\Database\Capsule\Manager;
use System\Container\Container;
use System\Router\Route;
use System\Router\Router;
use App\Controllers\ProjectController;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../vendor/autoload.php';



// load Dot env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();



//DB
$capsule = new Manager();
$capsule->addConnection([
    'driver'   => env('DB_CONNECTION'),
    'host'     => env('DB_HOST'),
    'database' => env('DB_DATABASE'),
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

try {

    // Routing
    $router = new Router([
        new Route('projects', '/projects', [ProjectController::class, 'index']),
        new Route('skills', '/skills', [SkillController::class, 'index']),
    ]);

    $request = Request::createFromGlobals();
    $route = $router->matchFromPath($request->getPathInfo(), $request->getMethod());

    $parameters = $route->getParameters();
    $arguments = $route->getVars();


    $controllerName = $parameters[0];
    $methodName = $parameters[1] ?? null;

    // Reflection
    $container = new Container();
    $class = $container->resolveClass($controllerName);
    $result = $container->callMethod($class, $methodName, $arguments);

    echo $result->getContent();
} catch (\Exception $exception) {
    var_dump("ERROR", $exception->getMessage());exit();
    header("HTTP/1.0 404 Not Found");
}

exit(0);