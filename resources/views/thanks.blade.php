<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Obrigado') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto p-6 text-center">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Obrigado pela sua compra!</h2>
                <p class="mt-4 text-gray-700 dark:text-gray-300">Seu pedido foi recebido com sucesso.</p>
                <p class="mt-2 text-gray-700 dark:text-gray-300">{{$payment->billingType == 'BOLETO' ? 'Clique no botão abaixo para realizar o pagamento do boleto' : ''}}</p>

                @if($payment->billing_type == 'BOLETO')
                <div class="mt-6  items-center w-full max-w-md mx-auto">
                    <!-- Botão para pagamento via boleto -->
                    <a target="_blank" href="{{ $payment->bank_slip_url }}" class="bg-green-600 dark:bg-green-400 text-white font-bold py-2 px-4 rounded-lg shadow hover:bg-green-700 dark:hover:bg-green-500">Pagar Boleto</a>
                </div>
                @endif

                <!-- QR Code para pagamento via Pix -->
                @if($payment->billing_type == 'PIX')
                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow mt-6 mx-auto w-max">
                        <p class="text-gray-800 dark:text-gray-200 font-bold mb-2 text-center">Pague com Pix</p>
                        <img src="{{ asset('storage/'.$payment->encoded_image_pix) }}" alt="QR Code para pagamento via Pix" class="w-100 h-100 mx-auto">
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg shadow mt-3">
                        <p class="text-gray-800 dark:text-gray-200 font-bold mb-2 text-center">Pague com Copia e Cola</p>
                        <textarea class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            {{$payment->payload_code_pix}}
                        </textarea>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
