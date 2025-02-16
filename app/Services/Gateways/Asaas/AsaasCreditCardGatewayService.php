<?php

namespace App\Services\Gateways\Asaas;

use App\Contracts\PaymentGatewayInterface;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;

class AsaasCreditCardGatewayService extends AsaasHttpGatewayService implements PaymentGatewayInterface
{
    protected const BILLING_UNDEFINED_TYPE = "UNDEFINED";
    protected const BILLING_CREDIT_CARD_TYPE = "CREDIT_CARD";

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
            $data['billingType'] = self::BILLING_UNDEFINED_TYPE;

            $this->setEndpoint('payments');
            $this->sendRequest($data, self::HTTP_POST);
            $response = $this->getResponse();
            if (is_null($response) === true) {
                return $this->msgInternalError();
            }

            if ($response->getStatusCode() === 200) {
                // payment created
                $payment_id = $response->json()['id'];
                $data['id'] = $payment_id;
                $data['billingType'] = self::BILLING_CREDIT_CARD_TYPE;
                $data['creditCard'] = [
                    'holderName' => $data['holderName'],
                    'number' => $data['creditCardNumber'],
                    'expiryMonth' => $data['expiryMonth'],
                    'expiryYear' => $data['expiryYear'],
                    'ccv' => $data['ccv']
                ];
                $data['creditCardHolderInfo'] = [
                    'name' => $customerData['name'],
                    'cpfCnpj' => $customerData['cpfCnpj'],
                    'email' => $customerData['email'],
                    'postalCode' => auth()->user()->address->postal_code,
                    'addressNumber' => auth()->user()->address->address_number,
                    'addressComplement' => auth()->user()->address->address_complement,
                    'phone' => auth()->user()->address->phone
                ];

                $this->setEndpoint("payments/{$payment_id}/payWithCreditCard");
                $this->sendRequest($data, self::HTTP_POST);
                $response = $this->getResponse();
            }

            if (is_null($response) === true) {
                return $this->msgInternalError();
            }

            return [
                'status' => $response->getStatusCode() == 200,
                'data' => $response->getStatusCode() == 200 ? $response->json() : [],
                'errors' => $response->getStatusCode() == 200 ? [] : $response->json()['errors']
            ];

        } catch (RequestException $e) {
            if (is_null($response) === true) {
                return $this->msgInternalError();
            }
        }
    }
}
