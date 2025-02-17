Foi criado um simulador de carrinho de compras com produtos. 

Instale os pacotes de dependências:

`composer install`

Crie o .env:
`cp .env.example .env`

Para subir o ambiente foi utilizado o Sail.

`./vendor/bin/sail up -d`

Vá ao seu `.env` e altere o bloco de código de conexão com banco de dados:

````
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
````

E adicione as seguintes linhas ao `.env`:

````
ASAAS_TOKEN="API_TOKEN_DO_ASAAS"
ASAAS_URL="https://api-sandbox.asaas.com/v3/"
````

Gere a chave do Laravel:
`./vendor/bin/sail artisan key:generate`

Após subir o ambiente execute as migrations:

`./vendor/bin/sail artisan migrate`

Gere um produto ou mais para o carrinho:

`./vendor/bin/sail artisan db:seed ProductSeeder`

Gere o link da pasta storage para que as imagens geradas do QRcode do PIX possam ser visiveis publicamente:

`./vendor/bin/sail artisan storage:link`

Execute o seguinte comando para disponibilizar o CSS:

```
npm install
npm run dev
```

Acesse a URL `http://localhost`, crie um novo usuário.

Após login, será redirecionado a tela para completar seu cadastro com os dados necessários.

E poderá calcular o carrinho e finalizar o pagamento com o meio de pagamento que desejar (Boleto, Pix ou cartão de crédito).

Podendo realizar a visualização dos pedidos no menu "Pedidos" persistido em banco de dados Mysql.
