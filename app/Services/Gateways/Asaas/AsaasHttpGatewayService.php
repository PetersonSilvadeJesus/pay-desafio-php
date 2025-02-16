<?php

namespace App\Services\Gateways\Asaas;


use App\Models\User;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\ClientException;

class AsaasHttpGatewayService
{
    private string $token;
    private string $url_base;
    private string $endpoint = '';
    private string $customer;
    private $http;
    /**
     * @var Response
     */
    private $response;

    const HTTP_POST = 'POST';
    const HTTP_GET = 'GET';

    public function __construct() {
        $this->url_base = env('ASAAS_URL') ?? '';
        $this->http = Http::class;
        $this->token = env('ASAAS_TOKEN') ?? '';
    }

    /**
     * @param string $endpoint
     */
    public function setEndpoint(string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @return string
     */
    public function getCustomer(): string
    {
        return $this->customer;
    }

    public function setCustomerSession()
    {

    }

    /**
     * @param array $data
     * @return string|bool
     */
    public function setCustomer(array $data): string|bool
    {
        $this->customer = (string) isset($data['customer']) ? $data['customer'] : '';

        if (empty($this->customer) === true) {
            // create customer asaas
            try {
                $this->setEndpoint('customers');
                $this->sendRequest($data, self::HTTP_POST);
                $response = $this->getResponse();
//                var_dump($response); die;
//                var_dump($response->getStatusCode()); die;

                if ($response->getStatusCode() === 200) {
                    $responseBody = $response->json();

                    $this->customer = (string) $responseBody['id'];

                    $user = User::find($data['userId']);
                    $user->external_reference_id = $this->customer;
                    $user->save();

                } else {
                    return false;
//                    throw new RequestException($response);
                }
            } catch (RequestException $e) {
                return false;
//                throw new \Exception($response);
            }
        }
        return $this->customer;
    }

    public function sendRequest(array $data, $method = self::HTTP_GET)
    {
        $headers = [
            'accept' => 'application/json',
            'Content-Type' => 'application/json',
            'access_token' => $this->token,
        ];

        try {
            if ($method === self::HTTP_POST) {
                $this->response = $this->http::withHeaders($headers)
                    ->post(
                    "{$this->url_base}{$this->endpoint}",
                    $data
                );
            }

            if ($method === self::HTTP_GET) {
                $this->response = $this->http::withHeaders($headers)
                    ->get(
                        "{$this->url_base}{$this->endpoint}"
                    );
            }

        } catch (\Exception $exception) {
            $this->response = $this->response;
        }
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function msgInternalError()
    {
        return [
            'status' => false,
            'data' => [],
            'errors' => [
                ['code' => 'internal_error', 'description' => 'Ocorreu um erro interno']
            ]
        ];
    }
}
