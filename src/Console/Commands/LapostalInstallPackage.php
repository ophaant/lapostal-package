<?php

namespace Ophaant\Lapostal\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LapostalInstallPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lapostal:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Lapostal Package';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('.____          __________               __         .__
|    |   _____ \______   \____  _______/  |______  |  |
|    |   \__  \ |     ___/  _ \/  ___/\   __\__  \ |  |
|    |___ / __ \|    |  (  <_> )___ \  |  |  / __ \|  |__
|_______ (____  /____|   \____/____  > |__| (____  /____/
        \/    \/                   \/            \/      ');
        $this->info('========================================================');
        $this->info('||             Laravel Seeder Postal Code             ||');
        $this->info('||                     by <fg=yellow>Ophaant</>🇮🇩                 ||');
        $this->info('========================================================');

        $this->outputComponents()->info('🚀 PROGRESSING...');
//dd($this->configExists('create_provinces_table.php'));
        if (! $this->configExists('create_provinces_table.php') and ! $this->configExists('create_cities_table.php')
        and ! $this->configExists('create_subdistrict_table.php') and ! $this->configExists('create_villages_table.php')) {
            $this->publishConfiguration();
            $this->info('Published configuration');
        }
        else {
            if ($this->shouldOverwriteConfig()) {
                $this->info('Overwriting configuration file...');
                $this->publishConfiguration($force = true);
            } else {
                $this->info('Existing configuration was not overwritten');
            }
        }

        $this->outputComponents()->info('🏁 FINISHED 🏁');
        $this->info('========================================================');
        $this->info('||                   🎉 Thank You 🎉                  ||');
        $this->info('========================================================');
    }

    private function configExists($fileName)
    {
        return File::exists(database_path('migrations/'.$fileName));
    }

    private function shouldOverwriteConfig()
    {
        return $this->confirm(
            'Config file already exists. Do you want to overwrite it?',
            false
        );
    }

    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => "Ophaant\Lapostal\LapostalServiceProvider"
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
        $this->call('db:seed',['--class'=>'PostalCodeSeeder']);

    }
}
