# **API para gestão de torneios de futebol**

Desenvolvida utilizando o framework Laravel, a api permite que você cadastre e gerencie seu campeonato de futebol, podendo ainda gerenciar times, jogadores, partidas e também verificar a tabela de classificação geral do campeonato. A seguir a documentação de suas respectivas rotas:

## **Iniciando**

Primeiramente verifique se você possui as seguintes dependências instaladas e devidamente configuradas em seu ambiente:

-   <a href="https://www.mysql.com/downloads/" title="MySQL">MySQL</a>
-   <a href="https://www.php.net/downloads" title="PHP">PHP</a>
-   <a href="https://getcomposer.org/download/" title="Composer">Composer</a>

O próximo passo é clonar o presente repositório no local desejado, para isso use o comando:

```
git clone https://github.com/PedroMuzyLopes/football_tournament_api.git
```

Após o clone, configure as variáveis de ambiente acessando a pasta raiz do projeto e duplicando o arquivo .env.example renomeando-o para .env

```
cp .env.example .env
```

Abra o arquivo duplicado, localize e altere as seguintes variáveis:

```
DB_CONNECTION=mysql
DB_HOST=<HOST-MYSQL>
DB_PORT=<PORTA-MYSQL>
DB_DATABASE=<NOME-BANCO-DADOS>
DB_USERNAME=<USUARIO-BANCO-DADOS>
DB_PASSWORD=<SENHA-USUARIO-BANCO-DADOS>
```

Agora inicie a aplicação e execute as migrations para criar as tabelas no banco de dados, para isso, use os seguintes comandos:

    php artisan migrate

    php artisan serve

Caso tenha configurado as variáveis de ambiente corretamente, não encontraram nenhum erro na etapa acima e receberão um retorno com a seguinte mensagem contendo a url base da aplicação:

    [INFO] Server running on [<URL-BASE>]

Guarde essa url base pois a mesma será utilizada para as requisições a seguir.

## **Requisições**

> Todas as rotas da api estão protegidas com autenticação com exceção da rota das rotas de autenticação em si (cadastro e login).

### **Autenticação**

Antes de começar a utilizar a api e realizar requisições, é necessário criar uma conta para posteriormente autenticar-se.

-   Criar um novo usuário:
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[POST]** {URL-BASE}/api/user

        **Corpo da requisição:**

        ```
        {
          name: String | Obrigatório,
          email: String | Obrigatório | Único,
          password: String | Obrigatório,
          password_confirmation: String | Obrigatório
        }
        ```

        Exemplo de requisição com dados fictícios:

        ```
        {
          "name": "Fulano",
          "email": "fulano@ciclano.com,
          password: "123mudar",
          password_confirmation: "123mudar"
        }
        ```

        Exemplo de resposta:

        ```
        {
          "user": {
            "name": "Fulano",
            "email": "fulano@ciclano.com",
            "updated_at": "2022-09-28T06:29:45.000000Z",
            "created_at": "2022-09-28T06:29:45.000000Z",
            "id": 1
          },
          "token": "3|dpMq0fsUwHw5yFKwl6MygqypO3WJ5dgrKLRnGbsw"
        }
        ```

        _Temos como retorno um objeto (user) contendo os dados do usuário e uma string (token) utilizada posteriormente para autenticação._

        Feito isso, podemos seguir para o processo de login.

-   Login de usuário previamente cadastrado:
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[POST]** {URL-BASE}/api/login

        **Corpo da requisição:**

        ```
        {
          email: String | Obrigatório,
          password: String | Obrigatório,
        }
        ```

        Exemplo de requisição com dados fictícios:

        ```
        {
          "email": "fulano@ciclano.com,
          password: "123mudar",
        }
        ```

        Exemplo de resposta:

        ```
        {
          "user": {
            "name": "Fulano",
            "email": "fulano@ciclano.com",
            "updated_at": "2022-09-28T06:29:45.000000Z",
            "created_at": "2022-09-28T06:29:45.000000Z",
            "id": 1
          },
          "token": "3|dpMq0fsUwHw5yFKwl6MygqypO3WJ5dgrKLRnGbsw"
        }
        ```

        _É necessário copiar o token gerado no retorno dessa requisição pois o mesmo será atribuído no header(cabeçalho) das próximas requisições_

### **Times**

-   Adicionar um novo time:
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[POST]** {URL-BASE}/api/teams

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        **Corpo da requisição:**

        ```
        {
          name: String | Obrigatório | Único,
        }
        ```

        Exemplo de requisição com dados fictícios:

        ```
        {
          "name": "Esperança Futebol Clube",
        }
        ```

        Exemplo de resposta:

        ```
        {
          "message": "Team Esperança Futebol Clube was successfully created"
        }
        ```

        _Temos como retorno uma string (message) contendo a a mensagem de confirmação ou erro_

-   Listar todos os times cadastrados:
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[GET]** {URL-BASE}/api/teams

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        **Corpo da requisição:**

        ```
        vazio
        ```

        Exemplo de requisição com dados fictícios:

        ```
         vazio
        ```

        Exemplo de resposta:

        ```
          [
            {
                "id": 1,
                "name": "Esperança Futebol Clube",
                "created_at": "2022-09-28T01:36:56.000000Z",
                "updated_at": "2022-09-28T01:36:56.000000Z"
            },
            {
                "id": 2,
                "name": "Paz Futebol Clube",
                "created_at": "2022-09-28T01:56:56.000000Z",
                "updated_at": "2022-09-28T01:56:56.000000Z"
            }
          ]
        ```

        _Temos como retorno um array de objetos onde cada objeto representa um time._
