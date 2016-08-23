<?php

namespace CleaniqueCoders\Packager\Console\Commands\Packager;

use Illuminate\Console\Command;

class Migrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'packager:migrate {vendor} {package} {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Package Migration Script';

    protected $packages = 'packages/';

    public function __construct()
    {
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

        $vendor = studly_case($this->argument('vendor'));
        $package = studly_case($this->argument('package'));

        $vendor_path = $this->packages . $this->argument('vendor') . '/';
        $package_path = $vendor_path . $this->argument('package') . '/src/';

        $migration_path = $package_path  . 'Migrations';

        $options = [
            '--path' => $migration_path
        ];

        if ($this->option('seed')) {
            $options['--seed'] = true;
        }
        
        $this->call('migrate', $options);
    }
}
