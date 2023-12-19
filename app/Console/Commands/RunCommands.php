<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:commands';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run multiple commands sequentially';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running key:generate...');
        $this->call('key:generate');

        $this->info('Running migrate...');
        $this->call('migrate');

        $this->info('Running jwt:secret...');
        $this->call('jwt:secret');

        $this->info('Running db:seed...');
        $this->call('db:seed');

        $this->info('Running export:postman...');
        $this->call('export:postman');

        $this->info('All commands completed successfully!');
    }
}
