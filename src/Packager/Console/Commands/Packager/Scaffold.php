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
            'dummies',
            'Dummies',
            'Dummy',
            'dummy',
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
        $this->packages = base_path() . '/' . $this->packages;
        $scaffold_stub = __DIR__ . $this->skeleton;

        $vendor = studly_case($this->argument('vendor'));
        $package = studly_case($this->argument('package'));

        $vendor_path = $this->packages . $this->argument('vendor') . '/';
        $package_path = $vendor_path . $this->argument('package') . '/src/';
        
        if(!$this->file->exists($package_path)) {
            $this->error('Package is not exist. Please create the package first using php artisan packager:skeleton command');
            exit();
        }

        $timestamp = Carbon::now();
        $name_model = studly_case(str_singular($this->argument('model'))); // studly + singular - User
        $name_controller = studly_case(str_plural($this->argument('model'))); // studly case + plural - UsersController
        $name_variable = strtolower(str_plural($this->argument('model'))); // variable - $users
        $name_variable_single = strtolower($name_model); // variable single - $user
        $name_migration =  $timestamp->format('Y_m_d_his') . '_create_' .snake_case(str_plural($this->argument('model'))) . '_table'; // name for migration script
        $name_views = $name_variable;
        
        $replace = [
            $name_variable,
            $name_controller,
            $name_model,
            $name_variable_single,
            strtolower($package),
            $vendor,
            $package
        ];
        $this->info('Creating scaffold...');
        $this->line("");

        // create controller
        $this->file->copy($scaffold_stub . 'DummyController.php', $package_path . 'Controllers/' . $name_controller . 'Controllers.php');
        $this->updateContent($replace, $package_path . 'Controllers/' . $name_controller . 'Controllers.php');
        $this->comment('Controller created...');

        // create model
        $this->file->copy($scaffold_stub . 'Dummy.php', $package_path . 'Models/' . $name_model . '.php');
        $this->updateContent($replace, $package_path . 'Models/' . $name_model . '.php');
        $this->comment('Model created...');

        // create migration
        $this->file->copy($scaffold_stub . '/migrations/DummyMigration.php', $package_path . 'Migrations/' . $name_migration . '.php');
        $this->updateContent($replace, $package_path . 'Migrations/' . $name_migration . '.php');
        $this->comment('Migration created...');

        // create view 
        $this->file->copyDirectory($scaffold_stub . 'views/dummies',  $package_path . 'Views/' . $name_views );
        $this->updateContent($replace, $package_path . 'Views/' . $name_views . '/index.blade.php');
        $this->updateContent($replace, $package_path . 'Views/' . $name_views . '/edit.blade.php');
        $this->updateContent($replace, $package_path . 'Views/' . $name_views . '/show.blade.php');
        $this->updateContent($replace, $package_path . 'Views/' . $name_views . '/create.blade.php');
        $this->comment('Views created...');

        // create route
        $routeName = "\\" . $vendor . "\\" . $package . "\\Controllers\\" . $name_controller . "Controller";
        $route = PHP_EOL.'Route::resource("' . $name_variable . '" ,"'.$routeName.'");'.PHP_EOL;

        $this->file->append($package_path . 'routes.php',$route);
        $this->comment('Route created...');

        $this->line("");
        $this->info('Scaffold created.');

    }

    private function updateContent($value, $path)
    {
        $content = $this->file->get($path);

        $content = str_replace($this->keys,$value,$content);

        $this->file->put($path, $content);
    }
}
