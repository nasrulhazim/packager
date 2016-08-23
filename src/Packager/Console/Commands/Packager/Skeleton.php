<?php

namespace CleaniqueCoders\Packager\Console\Commands\Packager;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class Skeleton extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'packager:skeleton {vendor} {package}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Package Skeleton';

    protected $packages = 'packages/';

    protected $skeleton = 'stub/skel/';

    protected $path;

    protected $package_path;

    protected $package;

    protected $vendor;

    protected $replace;

    protected $replaceWith;

    /**
     * @var File
     */
    private $file;

    public function __construct(Filesystem $file)
    {
        $this->file = $file;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->makeSkeleton();
    }

    private function makeSkeleton()
    {
        $this->info('Creating package...');
        $this->line('');

        // configure path & variables
        $this->makeConfigure();

        // clone stub/skel
        $this->makeClone();

        // update composer
        $this->makeComposer();

        // Rename Service Provider
        $this->makeServiceProvider();

        // create readme.md
        $this->makeReadMe();

        // Done
        $this->line("");
        $this->info('Creating package for ' . $this->argument('vendor') . '/' . $this->argument('package') . ' is done.');
    }

    private function makeDirectory($path)
    {
        if(!$this->file->exists($path)) {
            // create the directory
            $this->file->makeDirectory($path);
        } 
    }

    private function makeReplace($value)
    {
        return str_replace($this->replace, $this->replaceWith, $value);
    }

    private function makeConfigure()
    {
        $this->comment('Configuring package...');

        // create main repository if not exist
        $this->packages = base_path() . '/' . $this->packages;
        $this->makeDirectory($this->packages);
        
        $vendor = str_repo_vendor($this->argument('vendor')); // receive slug name
        $package = str_repo_package($this->argument('package')); // receive slug name

        $vendor_path = $this->packages . $vendor . '/';
        $package_path = $vendor_path . $package . '/';

        $class_vendor = str_class_vendor($this->argument('vendor'));
        $class_package = str_class_vendor($this->argument('package'));

        $namespace_vendor = str_namespace($this->argument('vendor'));
        $namespace_package = str_namespace($this->argument('package'));

        $this->replace = [
            '[[vendor]]',
            '[[package]]',
            '[[namespace_vendor]]',
            '[[namespace_package]]',
            '[[class_vendor]]',
            '[[class_package]]',
        ];

        $this->replaceWith = [
            $vendor,
            $package,
            $namespace_vendor,
            $namespace_package,
            $class_vendor,
            $class_package
        ];
        
        $this->vendor = $vendor;
        $this->package = $package;
        $this->package_path = $package_path;
        
        if($this->file->exists($this->package_path)) {
            $this->error('Package already exist. Please choose another package name.');
            exit();
        }
    }

    private function makeClone()
    {
        $this->comment("Cloning skeleton...");
        $this->file->copyDirectory(__DIR__ . '/stub/skel/', $this->package_path);
    }

    private function makeComposer()
    {
        $this->comment('Updating Composer...');

        $composer_path = $this->package_path . 'composer.json';
        $composer = $this->file->get($composer_path);

        $composer = $this->makeReplace($composer);

        $this->file->put($composer_path, $composer); 
    }

    private function makeServiceProvider()
    {
        $this->comment('Updating Service Provider...');

        // get package provider new name
        $provider_path = $this->package_path . 'src/app/Providers/'.str_class_package($this->package).'ServiceProvider.php';

        // rename the file
        $this->file->move($this->package_path . 'src/app/Providers/PackageServiceProvider.php', $provider_path);
        
        $provider = $this->file->get($provider_path);

        $provider = $this->makeReplace($provider);

        $this->file->put($provider_path, $provider);
    }

    private function makeReadMe()
    {
        $this->comment('Updating README.md...');

        $readme_path = $this->package_path . 'README.md';
        $readme = $this->file->get($readme_path);

        $readme = $this->makeReplace($readme);

        $this->file->put($readme_path, $readme);
    }
}
