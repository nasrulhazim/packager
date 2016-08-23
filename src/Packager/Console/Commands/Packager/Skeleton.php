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
        // update the packages directory path
        $this->makeSkeleton();
    }

    private function makeDirectory($path)
    {
        if(!$this->file->exists($path)) {
            // create the directory
            $this->file->makeDirectory($path);
        } 
    }

    private function makeSkeleton()
    {
        $this->packages = base_path() . '/' . $this->packages;
        $this->makeDirectory($this->packages);
        
        $vendor = studly_case($this->argument('vendor'));
        $package = studly_case($this->argument('package'));

        $vendor_path = $this->packages . $this->argument('vendor') . '/';
        $package_path = $vendor_path . $this->argument('package') . '/';

        if($this->file->exists($package_path)) {
            $this->error('Package already exist. Please choose another package name.');
        }

        $this->file->copyDirectory(__DIR__ . '/stub/skel/', $package_path);
        $this->info('Creating package...');
        $this->line("");

        // update composer
        $composer_path = $package_path . 'composer.json';
        $composer = $this->file->get($composer_path);

        $composer = str_replace(
            [
                '[vendor]/[package]',
                '[Package]',
                'nsv','nsp'
            ],[
                $this->argument('vendor') . '/' . $this->argument('package'),
                $package,
                $this->argument('vendor'), $this->argument('package')
            ],$composer);

        $this->file->put($composer_path, $composer);
        $this->comment('Updating Composer...');

        // Rename Service Provider
        $provider_path = $package_path . 'src/Providers/'.studly_case($package).'ServiceProvider.php';
        $this->file->move($package_path . 'src/Providers/PackageServiceProvider.php', $provider_path);
        $this->comment('Updating Service Provider...');

        $provider = $this->file->get($provider_path);

        $provider = str_replace([
            'Vendor',
            'Package',
            'package'
            ],
            [
                studly_case($vendor),
                studly_case($package),
                strtolower($package) 
            ],$provider);
        $this->file->put($provider_path, $provider);

        $readme_path = $package_path . 'README.md';
        $readme = $this->file->get($readme_path);

        $readme = str_replace(
            [
                'Vendor',
                'Package'
            ],[
                $vendor, 
                $package,
            ],$readme);

        $this->file->put($readme_path, $readme);

        $this->comment('Updating README.md...');
        $this->line("");
        $this->info('Creating package for ' . $vendor . '/' . $package . ' is done.');
    }
}
