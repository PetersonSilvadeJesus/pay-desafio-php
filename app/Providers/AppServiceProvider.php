<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayInterface;
use App\Services\Gateways\Asaas\AsaasBoletoGatewayService;
use App\Services\Gateways\Asaas\AsaasCreditCardGatewayService;
use App\Services\Gateways\Asaas\AsaasPixGatewayService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentGatewayInterface::class, function($app, $params){
            $billingType = empty($params['billingType']) ? null : $params['billingType'];

            if (is_null($billingType) === false) {
                $gateways = [
                    'boleto' => AsaasBoletoGatewayService::class,
                    'pix' => AsaasPixGatewayService::class,
                    'credit_card' => AsaasCreditCardGatewayService::class,
                ];
                return new $gateways[$billingType]();
            } else {
                throw new \Exception('Erro ao chamar service.');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
