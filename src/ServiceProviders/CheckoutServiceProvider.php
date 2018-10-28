<?php

namespace Oadtz\Checkout\ServiceProviders;

use Illuminate\Support\ServiceProvider;
use Oadtz\Checkout\Interfaces\CheckoutInterface;
use Oadtz\Checkout\Facades\CheckoutFacadeAccessor;
use Oadtz\Checkout\{Checkout, CreditCardPayment, AdyenClient, BraintreeClient, Config};

class CheckoutServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the package.
     */
    public function boot()
    {
        /*
        |--------------------------------------------------------------------------
        | Publish the Config file from the Package to the App directory
        |--------------------------------------------------------------------------
        */
        $this->configPublisher();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        /*
        |--------------------------------------------------------------------------
        | Implementation Bindings
        |--------------------------------------------------------------------------
        */
        $this->implementationBindings();

        /*
        |--------------------------------------------------------------------------
        | Facade Bindings
        |--------------------------------------------------------------------------
        */
        $this->facadeBindings();

        /*
        |--------------------------------------------------------------------------
        | Registering Service Providers
        |--------------------------------------------------------------------------
        */
        $this->serviceProviders();
    }

    /**
     * Implementation Bindings
     */
    private function implementationBindings()
    {
        $this->app->bind(CheckoutInterface::class, function ($app) {
            $adyenClient = new AdyenClient(new Config());
            $braintreeClient = new BraintreeClient (new Config());
            $payment = new CreditCardPayment ($adyenClient, $braintreeClient);

            return new Checkout($payment);
        });
    }

    /**
     * Publish the Config file from the Package to the App directory
     */
    private function configPublisher()
    {
        // When users execute Laravel's vendor:publish command, the config file will be copied to the specified location
        $this->publishes([
            __DIR__ . '/../Config/checkout.php' => config_path('checkout.php'),
        ]);
    }

    /**
     * Facades Binding
     */
    private function facadeBindings()
    {
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * Registering Other Custom Service Providers (if you have)
     */
    private function serviceProviders()
    {
        // $this->app->register('...\...\...');
    }

}
