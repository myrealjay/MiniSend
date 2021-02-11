<?php

namespace App\Providers;

use App\Models\CompanyEmail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\ServiceProvider;
use ParagonIE\CipherSweet\Backend\ModernCrypto;
use ParagonIE\CipherSweet\CipherSweet;
use ParagonIE\CipherSweet\KeyProvider\StringProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerCipherSweet();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //update status if job fails
        Queue::failing(function (JobFailed $event) {
            $payload=$event->job->payload();
            $data=(array)unserialize($payload['data']['command']);
            $tracker_id=$data['tracker_id'];
            $tracker=CompanyEmail::where('id', $tracker_id)->first();
            $tracker->status='failed';
            $tracker->save();
        });
    }

    /**
    * Register CipherSweet library.
    *
    * @return void
    */
    private function registerCipherSweet(): void
    {
        $this->app->singleton(CipherSweet::class, function () {
            $key = config('cypher.key');

            if (empty($key)) {
                throw new \RuntimeException(
                    'Encryption key for auth service is not specified.'
                );
            }

            return new CipherSweet(new StringProvider($key), new ModernCrypto());
        });
    }
}
