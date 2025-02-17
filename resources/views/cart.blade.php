<?php
    $value_total = 0;
?>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Carrinho') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto p-6">

            <div class=" dark:bg-gray-800 shadow-md rounded-lg p-6">
                <table class="w-full text-gray-900 dark:text-gray-100">
                    <thead>
                    <tr class="border-b border-gray-300 dark:border-gray-600">
                        <th class="text-left p-2">Produto</th>
                        <th class="text-center p-2">Quantidade</th>
                        <th class="text-center p-2">Preço</th>
                        <th class="text-right p-2">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <?php
                            $value_total += $product->price
                        ?>
                        <tr class="border-b border-gray-300 dark:border-gray-600">
                            <td class="p-2">{{$product->name}}</td>
                            <td class="text-center p-2">
                                <input type="number" value="1" class="qtd-product p-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-500" data-id="{{$product->id}}" id="qtd-product-{{$product->id}}">
                                <input type="hidden" value="{{$product->price}}" id="value-product-{{$product->id}}">
                            </td>
                            <td class="text-center p-2">R$ {{number_format($product->price, 2, ',', '.')}}</td>
                            <td class="text-right p-2">R$ {{number_format($product->price, 2, ',', '.')}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="mt-4 flex justify-between text-gray-900 dark:text-gray-100">
                    <span class="text-lg font-semibold">Total:</span>
                    <span class="text-lg font-semibold" id="span-total-value">R$ {{number_format($value_total, 2, ',', '.')}}</span>
                    <button class="bg-blue-600 dark:bg-blue-400 text-white font-bold py-2 px-4 rounded-lg shadow hover:bg-blue-700 dark:hover:bg-blue-500" onclick="recalcular()">Recalcular</button>
                </div>
            </div>

            <div class="mt-6 bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Forma de Pagamento</h3>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-500 text-white rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="border-b border-gray-300 dark:border-gray-600 flex">
                    <button id="tab-boleto" class="py-2 px-4 focus:outline-none text-gray-900 dark:text-gray-100 border-b-2 border-transparent border-blue-600 dark:border-blue-400 bg-gray-200 dark:bg-gray-700" onclick="selectTab('boleto')">Boleto</button>
                    <button id="tab-pix" class="py-2 px-4 focus:outline-none text-gray-900 dark:text-gray-100 border-b-2 border-transparent" onclick="selectTab('pix')">Pix</button>
                    <button id="tab-credit_card" class="py-2 px-4 focus:outline-none text-gray-900 dark:text-gray-100 border-b-2 border-transparent" onclick="selectTab('credit_card')">Cartão de Crédito</button>
                </div>

                <form action="{{route('payment.checkout')}}" method="post">
                    @csrf

                    <input type="hidden" id="billingType" name="billingType" value="boleto"/>
                    <input type="hidden" id="value" name="value" value="{{$value_total}}"/>

                    <div id="boleto" class="payment-content mt-4">
                        <p class="text-gray-900 dark:text-gray-100">Ao escolher boleto, um código será gerado após a finalização da compra.</p>
                    </div>

                    <div id="pix" class="payment-content mt-4 hidden">
                        <p class="text-gray-900 dark:text-gray-100">Ao escolher Pix, um QR Code será gerado para pagamento.</p>
                    </div>

                    <div id="credit_card" class="payment-content mt-4 hidden">

                        <label class="block text-gray-900 dark:text-gray-100">Número do Cartão</label>
                        <input type="text" name="creditCardNumber" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md">

                        <label class="block mt-2 text-gray-900 dark:text-gray-100">Nome no Cartão</label>
                        <input type="text" name="holderName" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md">

                        <div class="flex mt-2">
                            <div class="w-1/2 mr-2">
                                <label class="block text-gray-900 dark:text-gray-100">Mês Validade</label>
                                <input type="text" name="expiryMonth" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md">
                            </div>
                            <div class="w-1/2 mr-2">
                                <label class="block text-gray-900 dark:text-gray-100">Ano Validade</label>
                                <input type="text" name="expiryYear" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md">
                            </div>
                            <div class="w-1/2 ml-2">
                                <label class="block text-gray-900 dark:text-gray-100">CCV</label>
                                <input type="text" name="ccv" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="mt-4 w-full bg-blue-600 dark:bg-blue-400 text-white font-bold py-2 px-4 rounded-lg shadow hover:bg-blue-700 dark:hover:bg-blue-500">Finalizar Pagamento</button>
                </form>
            </div>

            <script>
                function selectTab(paymentMethod) {
                    document.querySelectorAll('.payment-content').forEach(el => el.classList.add('hidden'));
                    document.getElementById(paymentMethod).classList.remove('hidden');

                    document.querySelectorAll('.border-blue-600, .border-blue-400').forEach(el => {
                        el.classList.remove('border-blue-600', 'dark:border-blue-400', 'bg-gray-200', 'dark:bg-gray-700');
                    });


                    document.getElementById('billingType').value = paymentMethod;
                    document.getElementById(`tab-${paymentMethod}`).classList.add('border-blue-600', 'dark:border-blue-400', 'bg-gray-200', 'dark:bg-gray-700');
                }

                function recalcular() {
                    let sum = 0.0;
                    document.querySelectorAll('.qtd-product').forEach(function(el){
                        let qtd = el.value;
                        let value_unit = document.getElementById(`value-product-`+el.getAttribute('data-id').valueOf()).value;
                        sum += qtd * value_unit;
                    });
                    console.log(sum);
                    document.getElementById('span-total-value').innerHTML = `R$ ${sum}`;
                    document.getElementById('value').value = sum;
                }
            </script>

        </div>
    </div>
</x-app-layout>
