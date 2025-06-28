<?php

namespace App\Providers;

use Spatie\Health\Facades\Health;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\CpuLoadHealthCheck\CpuLoadCheck;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\QueueCheck;
use Spatie\Health\Checks\Checks\BackupsCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\ScheduleCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Checks\Checks\DatabaseTableSizeCheck;
use Spatie\SecurityAdvisoriesHealthCheck\SecurityAdvisoriesCheck;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Health::checks([
            PingCheck::new()->url('https://mandraled.com'),
            SecurityAdvisoriesCheck::new(),
            CpuLoadCheck::new()
                ->failWhenLoadIsHigherInTheLast5Minutes(2.0)
                ->failWhenLoadIsHigherInTheLast15Minutes(1.5),
            
            UsedDiskSpaceCheck::new()
                ->warnWhenUsedSpaceIsAbovePercentage(66)
                ->failWhenUsedSpaceIsAbovePercentage(95),
            DatabaseCheck::new(),
            DatabaseTableSizeCheck::new()
                ->table('users', maxSizeInMb: 1_000),
                //->table('another_table_name', maxSizeInMb: 2_000),

           
            EnvironmentCheck::new(),
            DebugModeCheck::new(),
            OptimizedAppCheck::new(),
            CacheCheck::new(),
            
            //QueueCheck::new(),
            //ScheduleCheck::new(),

            
            
            
            
            BackupsCheck::new()
                ->locatedAt('/path/to/backups/*.zip')
                ->oldestBackShouldHaveBeenMadeAfter(now()->subWeeks(1)),
        ]);
    }
}
