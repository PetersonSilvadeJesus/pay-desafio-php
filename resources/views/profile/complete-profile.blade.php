<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Complete Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto p-6">
            <h5 class="font-bold mb-4 text-gray-900 dark:text-gray-100">Por favor, complete seu perfil para prosseguir com a compra.</h5>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-500 text-white rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                 </div>
             @endif

            <form action="{{route('profile.update-cpf_cnpj')}}" method="post">
                @csrf

                <div class="w-1/2 mr-2">
                    <label class="block text-gray-900 dark:text-gray-100">CPF/CNPJ</label>
                    <input type="text" name="cpf_cnpj" value="{{$user->cpf_cnpj ?? ''}}" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md">
                </div>

                <div class="w-1/2 mr-2">
                    <label class="block text-gray-900 dark:text-gray-100">CEP Postal</label>
                    <input type="text" name="postalCode" value="{{$user->address->postal_code ?? ''}}" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md">
                </div>

                <div class="w-1/2 mr-2">
                    <label class="block text-gray-900 dark:text-gray-100">Numero Residencial</label>
                    <input type="text" name="addressNumber" value="{{$user->address->address_number ?? ''}}" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md">
                </div>

                <div class="w-1/2 mr-2">
                    <label class="block text-gray-900 dark:text-gray-100">Complemento</label>
                    <input type="text" name="addressComplement" value="{{$user->address->address_complement ?? ''}}" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md">
                </div>

                <div class="w-1/2 mr-2">
                    <label class="block text-gray-900 dark:text-gray-100">Telefone</label>
                    <input type="text" name="phone" value="{{$user->address->phone ?? ''}}" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md">
                </div>


                <button type="submit" class="mt-4 bg-blue-600 dark:bg-blue-400 text-white font-bold py-2 px-4 rounded-lg shadow hover:bg-blue-700 dark:hover:bg-blue-500">Salvar</button>
            </form>
        </div>
    </div>
</x-app-layout>
