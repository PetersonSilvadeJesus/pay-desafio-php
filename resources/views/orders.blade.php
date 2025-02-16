<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pedidos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto p-6">

            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <table class="w-full text-gray-900 dark:text-gray-100">
                    <thead>
                    <tr class="border-b border-gray-300 dark:border-gray-600">
                        <th class="text-left p-2">#</th>
                        <th class="text-center p-2">Status</th>
                        <th class="text-center p-2">Meio de Pagamento</th>
                        <th class="text-right p-2">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $payment)
                        <?php
                            $status_badge_class = "";

                            if ($payment->status == "PENDING") {
                                $status_badge_class = "bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-yellow-300 border border-yellow-300";
                            } else if ($payment->status == "CONFIRMED") {
                                $status_badge_class = "bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-green-400 border border-green-400";
                            } else {
                                $status_badge_class = "bg-green-600 dark:bg-green-400 text-white font-bold py-2 px-4 rounded-lg shadow hover:bg-green-700 dark:hover:bg-green-500";
                            }
                        ?>
                        <tr class="border-b border-gray-300 dark:border-gray-600">
                            <td class="p-2">{{$payment->gateway_order_id}}</td>
                            <td class="text-center p-2">
                                <span class="{{$status_badge_class}}">
                                    {{$payment->status}}
                                </span>
                            </td>
                            <td class="text-center p-2">{{$payment->billing_type}}</td>
                            <td class="text-right p-2">
                                @if($payment->billing_type == "BOLETO")
                                    <a target="_blank" href="{{$payment->bank_slip_url}}" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">Ver Boleto</a>
                                @endif
                                @if($payment->billing_type == "PIX")
                                    <a target="_blank" href="{{route('thanks', ['id' => $payment->id])}}" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">Ver QRCode</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
