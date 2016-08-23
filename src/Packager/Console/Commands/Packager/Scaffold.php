<?php

namespace CleaniqueCoders\Packager\Console\Commands\Packager;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Carbon\Carbon;

class Scaffold extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'packager:scaffold {vendor} {package} {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a controller, model, views and migrations script for given model name';

    protected $packages = 'packages/';

    protected $skeleton = '/stub/scaffold/';

    protected $vendor;

    protected $package;

    protected $namespace_vendor;

    protected $namespace_package;

    protected $class_vendor;

    protected $class_package;

    protected $name_model;

    protected $name_controller;

    protected $name_view;

    protected $name_migration;

    protected $name_seeds;

    protected $var_singular;

    protected $var_plural;

    /**
     * @var File
     */
    private $file;

    private $keys;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $file)
    {
        $this->file = $file;

        $this->keys = [
            '[[var_plural]]',
            'Dummies',
            '[[name_model]]',
            '[[var_singular]]',
            'package',
            'Vendor',
            'Package'
        ];

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Creating scaffold...');
        $this->line("");

        $this->makeInitialize();

        $this->makeConfigure();

        $this->makeRoute();
        
        $this->makeController();

        $this->makeModel();

        $this->makeViews();

        $this->makeMigration();

        $this->makeSeeds();

        $this->line("");
        $this->info('Scaffold created.');

    }

    private function makeContent($path)
    {
        $content = $this->file->get($path);

        $content = str_replace($this->replace,$this->replaceWith, $content);

        $this->file->put($path, $content);
    }

    private function makeInitialize()
    {
        $this->packages = base_path() . '/' . $this->packages;
        $this->skeleton = __DIR__ . $this->skeleton;

        $this->vendor = str_repo_vendor($this->argument('vendor'));
        $this->package = str_repo_package($this->argument('package'));

        $vendor_path = $this->packages . $this->argument('vendor') . '/';
        $this->package_path = $vendor_path . $this->argument('package') . '/src/';
        
        if(!$this->file->exists($this->package_path)) {
            $this->comment('Package not exist, hence the package is auto generate.');
            $this->call('packager:skeleton',[
                'vendor' => $this->argument('vendor'),
                'package' => $this->argument('package')
            ]);
        } else {
            $this->comment('Package exist. Proceed to configure scaffold...');
        }
    }

    private function makeConfigure()
    {
        $this->comment('Configuring package...');

        $this->class_vendor = str_class_vendor($this->argument('vendor'));
        $this->class_package = str_class_vendor($this->argument('package'));

        $this->namespace_vendor = str_namespace($this->argument('vendor'));
        $this->namespace_package = str_namespace($this->argument('package'));

        // model name
        $this->name_model = str_model($this->argument('model'));

        // controller name
        $this->name_controller = str_controller($this->argument('model'));

        // views name
        $this->name_views = str_var_plural($this->name_model);

        // variable name
        $this->var_singular = str_var_singular($this->argument('model')); // singular
        $this->var_plural = $this->name_views; // plural
        
        // migration
        $timestamp = Carbon::now();
        $this->name_migration =  $timestamp->format('Y_m_d_his') . '_create_' . str_migration($this->argument('model')) . '_table'; // name for migration script
        
        $this->name_seeds = str_seeds($this->argument('model'));
        
        $this->replace = [
            '[[vendor]]',
            '[[package]]',
            '[[namespace_vendor]]',
            '[[namespace_package]]',
            '[[class_vendor]]',
            '[[class_package]]',
            '[[name_model]]',
            '[[name_controller]]',
            '[[name_view]]',
            '[[name_migration]]',
            '[[name_seeds]]',
            '[[var_singular]]',
            '[[var_plural]]'
        ];

        $this->replaceWith = [
            $this->vendor,
            $this->package,
            $this->namespace_vendor,
            $this->namespace_package,
            $this->class_vendor,
            $this->class_package,
            $this->name_model,
            $this->name_controller,
            $this->name_view,
            $this->name_migration,
            $this->name_seeds,
            $this->var_singular,
            $this->var_plural
        ];
    }

    private function makeRoute()
    {
        // create route
        $routeName = "\\" . $this->namespace_vendor . "\\" . $this->namespace_package . "\\Controllers\\" . $this->name_controller . "Controller";
        $route = PHP_EOL.'Route::resource("' . $this->var_plural . '" ,"'.$routeName.'");'.PHP_EOL;

        $this->file->append($this->package_path . 'routes/routes.php',$route);
        $this->comment('Route created...');
    }

    private function makeController()
    {
        // create controller
        $controller_path = $this->package_path . 'app/Http/Controllers/' . $this->name_controller . 'Controllers.php';
        $this->file->copy($this->skeleton . 'DummyController.php', $controller_path);
        $this->makeContent($controller_path);
        $this->comment('Controller created...');
    }

    private function makeModel()
    {
        // create model
        $model_path = $this->package_path . 'app/Models/' . $this->name_model . '.php';
        $this->file->copy($this->skeleton . 'Dummy.php', $model_path);
        $this->makeContent($model_path);
        $this->comment('Model created...');
    }

    private function makeMigration()
    {
        // create migration
        $migration_path = $this->package_path . 'database/migrations/' . $this->name_migration . '.php';
        $this->file->copy($this->skeleton . '/DummyMigration.php', $migration_path);
        $this->makeContent($migration_path);
        $this->comment('Migration created...');
    }

    private function makeSeeds()
    {
        $seed_path = $this->package_path . 'database/seeds/' . $this->name_seeds . 'TableSeeder.php';
        $this->file->copy($this->skeleton . '/DummyTableSeeder.php', $seed_path);
        $this->makeContent($seed_path);
        $this->comment('Seeder created...');
    }

    private function makeViews()
    {
        // create view 
        $this->file->copyDirectory($this->skeleton . 'views/dummies',  $this->package_path . 'resources/views/' . $this->var_plural );
        $this->makeContent($this->package_path . 'resources/views/' . $this->var_plural . '/index.blade.php');
        $this->makeContent($this->package_path . 'resources/views/' . $this->var_plural . '/edit.blade.php');
        $this->makeContent($this->package_path . 'resources/views/' . $this->var_plural . '/show.blade.php');
        $this->makeContent($this->package_path . 'resources/views/' . $this->var_plural . '/create.blade.php');
        $this->comment('Views created...');
    }

}
