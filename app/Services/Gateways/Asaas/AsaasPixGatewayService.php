<?php

namespace App\Services\Gateways\Asaas;

use App\Contracts\PaymentGatewayInterface;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;

class AsaasPixGatewayService extends AsaasHttpGatewayService implements PaymentGatewayInterface
{
    protected const BILLING_UNDEFINED_TYPE = "UNDEFINED";
    protected const BILLING_PIX_TYPE = "PIX";

    public function process($data): array
    {
        try {
            $customerData = [];
            $customerData['userId'] = auth()->user()->id;
            $customerData['cpfCnpj'] = auth()->user()->cpf_cnpj;
            $customerData['name'] = auth()->user()->name;
            $customerData['email'] = auth()->user()->email;
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
            $data['billingType'] = self::BILLING_PIX_TYPE;

            $this->setEndpoint('payments');
            $this->sendRequest($data, self::HTTP_POST);
            $response = $this->getResponse();

            if (is_null($response) === true) {
                return $this->msgInternalError();
            }

            if ($response->getStatusCode() === 200) {
                // payment created
                $payment_id = $response->json()['id'];
                $this->setEndpoint("payments/{$payment_id}/pixQrCode");
                $this->sendRequest([]);
                $response = $this->getResponse();
                $dataReturn = array_merge($response->json(), [
                    'id' => $payment_id,
                    'status' => 'PENDING',
                    'billingType' => self::BILLING_PIX_TYPE,
                    'value' => $data['value']
                ]);
            }

            if (is_null($response) === true) {
                return $this->msgInternalError();
            }

            return [
                'status' => $response->getStatusCode() == 200,
                'data' => $response->getStatusCode() == 200 ? $dataReturn : [],
                'errors' => $response->getStatusCode() == 200 ? [] : $response->json()['errors']
            ];

        } catch (RequestException $e) {
            return $this->msgInternalError();
        }
    }
}
