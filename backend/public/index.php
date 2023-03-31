<?php
declare(strict_types=1);

//header("Access-Control-Allow-Origin: http://localhost:3000");

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}


use App\Controllers\HomeController;
use App\Controllers\SkillController;
use Illuminate\Database\Capsule\Manager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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

$response = new JsonResponse(
    'Content',
    Response::HTTP_OK,
    ['content-type' => 'application/json']
);
try {
    // Routing
    $router = new Router([
        new Route('home', '/', [HomeController::class, 'index']),
        new Route('projects', '/projects', [ProjectController::class, 'index'], ['POST']),
        new Route('budgets', '/projects/budgets', [ProjectController::class, 'budgets'], ['POST']),
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

    $response->setContent(json_encode([
        'status' => true,
        'payload' => $result
    ]));
    $response->send();

} catch (\Exception $exception) {

    $response = new JsonResponse(
        'Content',
        Response::HTTP_NOT_FOUND,
        ['content-type' => 'application/json']
    );
    $response->setContent(json_encode(['status' => false, 'payload' => $exception->getMessage()]));
    $response->send();
}

exit(1);