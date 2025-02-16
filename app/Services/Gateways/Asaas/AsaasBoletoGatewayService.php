<?php

namespace App\Services\Gateways\Asaas;

use App\Contracts\PaymentGatewayInterface;
use Carbon\Carbon;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;

class AsaasBoletoGatewayService extends AsaasHttpGatewayService implements PaymentGatewayInterface
{

    public function process($data): array
    {
        try {
            $customerData = [];
            $customerData['userId'] = auth()->user()->id;
            $customerData['cpfCnpj'] = auth()->user()->cpf_cnpj;
            $customerData['name'] = auth()->user()->name;
            $customerData['customer'] = auth()->user()->external_reference_id;
            $data['customer'] = auth()->user()->external_reference_id;

            $idCustomerGateway = $this->setCustomer($customerData);
            if ($idCustomerGateway === false) {
                return [
                    'status' => false,
                    'data' => [],
                    'errors' => $this->getResponse()->json()['errors']
                ];
            }
            $data['customer'] = $idCustomerGateway;

            $carbon = Carbon::now();
            $date = $carbon->addDays(15);
            $data['dueDate'] = $date->toDateString();

            $this->setEndpoint('payments');
            $this->sendRequest($data, self::HTTP_POST);

            $response = $this->getResponse();

            if (is_null($response) === true) {
                return [
                    'status' => false,
                    'data' => [],
                    'errors' => [
                        ['code' => 'internal_error', 'description' => 'Ocorreu um erro interno']
                    ]
                ];
            }

            return [
                'status' => $response->getStatusCode() == 200,
                'data' => $response->getStatusCode() == 200 ? $response->json() : [],
                'errors' => $response->getStatusCode() == 200 ? [] : $response->json()['errors']
            ];

        } catch (RequestException $e) {
            return [
                'status' => false,
                'data' => [],
                'errors' => [
                    ['code' => 'internal_error', 'description' => 'Ocorreu um erro interno']
                ]
            ];
        }
    }
}
