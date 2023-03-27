<?php


use App\Services\ImportProjects\FreelancehuntImport;
use App\Services\ProjectService;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use Phinx\Seed\AbstractSeed;

class ProjectsSeeder extends AbstractSeed
{

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
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



        /** @var ProjectService $service */
        $service = Container::getInstance()->make(ProjectService::class);

        $service->import();
    }
}
