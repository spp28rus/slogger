<?php

namespace App\Console\Commands\Octane\Roadrunner;

use Illuminate\Console\Command;

class OctaneRoadrunnerStartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'octane:roadrunner:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the octane roadrunner server';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->call('octane:roadrunner', [
            '--host'              => config('octane.servers.roadrunner.host'),
            '--port'              => config('octane.servers.roadrunner.port'),
            '--rpc-host'          => config('octane.servers.roadrunner.rpc-host'),
            '--rpc-port'          => config('octane.servers.roadrunner.rpc-port'),
            '--grpc-host'         => config('octane.servers.roadrunner.grpc-host'),
            '--grpc-port'         => config('octane.servers.roadrunner.grpc-port'),
            '--grpc-workers'      => config('octane.servers.roadrunner.grpc-workers'),
            '--grpc-max-requests' => config('octane.servers.roadrunner.grpc-max-requests'),
            '--workers'           => config('octane.servers.roadrunner.workers'),
            '--max-requests'      => config('octane.servers.roadrunner.max-requests'),
            '--rr-config'         => config('octane.servers.roadrunner.rr-config'),
            '--watch'             => config('octane.servers.roadrunner.watch'),
            '--poll'              => config('octane.servers.roadrunner.poll'),
            '--log-level'         => config('octane.servers.roadrunner.log-level'),
        ]);
    }
}
