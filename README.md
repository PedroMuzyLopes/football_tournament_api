# **API para gestão de torneios de futebol**

Desenvolvida utilizando o framework Laravel, a api permite que você cadastre e gerencie seu campeonato de futebol, podendo ainda gerenciar times, jogadores, partidas e também verificar a tabela de classificação geral do campeonato. A seguir a documentação de suas respectivas rotas:

<br>

## **Primeiros passos**

Primeiramente verifique se você possui as seguintes dependências instaladas e devidamente configuradas em seu ambiente:

-   <a href="https://www.mysql.com/downloads/" title="MySQL">MySQL</a>
-   <a href="https://www.php.net/downloads" title="PHP">PHP</a>
-   <a href="https://getcomposer.org/download/" title="Composer">Composer</a>

<br>

O próximo passo é clonar o presente repositório no local desejado, para isso use o comando:

```
git clone https://github.com/PedroMuzyLopes/football_tournament_api.git
```

<br>

Após o clone, configure as variáveis de ambiente acessando a pasta raiz do projeto e duplicando o arquivo .env.example renomeando-o para .env

```
cp .env.example .env
```

<br>

Abra o arquivo duplicado, localize e altere as seguintes variáveis:

```
DB_CONNECTION=mysql
DB_HOST=<HOST-MYSQL>
DB_PORT=<PORTA-MYSQL>
DB_DATABASE=<NOME-BANCO-DADOS>
DB_USERNAME=<USUARIO-BANCO-DADOS>
DB_PASSWORD=<SENHA-USUARIO-BANCO-DADOS>
```

<br>

Agora inicie a aplicação e execute as migrations para criar as tabelas no banco de dados, para isso, use os seguintes comandos:

    php artisan migrate

    php artisan serve

<br>

Caso tenha configurado as variáveis de ambiente corretamente,receberá um retorno com a seguinte mensagem contendo a url base da aplicação:

    [INFO] Server running on [<URL-BASE>]

Guarde essa url base pois a mesma será utilizada para as requisições a seguir.

<br><br>

## **Requisições**

> Todas as rotas da api estão protegidas com autenticação com exceção das rotas de autenticação em si (cadastro e login).

<br>

### **Autenticação**

Antes de começar a utilizar a api e realizar requisições, é necessário criar uma conta para posteriormente autenticar-se.

<br>

-   **Criar um novo usuário:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[POST]** {URL-BASE}/api/user

        -   _Ex.: http://localhost:8000/api/user_

        **Corpo da requisição:**

        ```
        {
          name: String | Obrigatório,
          email: String | Obrigatório | Único,
          password: String | Obrigatório,
          password_confirmation: String | Obrigatório
        }
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
        {
          "name": "Fulano",
          "email": "fulano@ciclano.com,
          password: "123mudar",
          password_confirmation: "123mudar"
        }
        ```

        **Exemplo de resposta:**

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

-   **Login de usuário previamente cadastrado:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[POST]** {URL-BASE}/api/login

        -   _Ex.: http://localhost:8000/api/login_

        **Corpo da requisição:**

        ```
        {
          email: String | Obrigatório,
          password: String | Obrigatório,
        }
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
        {
          "email": "fulano@ciclano.com,
          password: "123mudar",
        }
        ```

        **Exemplo de resposta:**

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

-   **Adicionar um novo time:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[POST]** {URL-BASE}/api/teams

        -   _Ex.: http://localhost:8000/api/teams_

        <br>

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

        **Exemplo de requisição com dados fictícios:**

        ```
        {
          "name": "Esperança Futebol Clube",
        }
        ```

        **Exemplo de resposta:**

        ```
        {
          "message": "Team Esperança Futebol Clube was successfully created"
        }
        ```

        _Temos como retorno uma string (message) contendo a a mensagem de confirmação ou erro_

-   **Listar todos os times cadastrados:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[GET]** {URL-BASE}/api/teams

        -   _Ex.: http://localhost:8000/api/teams_

        <br>

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        **Corpo da requisição:**

        ```
        vazio
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
         vazio
        ```

        **Exemplo de resposta:**

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

-   **Listar um time em específico:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[GET]** {url-base}/api/teams/{id}

        -   _{id} = id do time a ser listado_

        -   _Ex.: http://localhost:8000/api/teams/1_

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        **Corpo da requisição:**

        ```
        vazio
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
         vazio
        ```

        **Exemplo de resposta:**

        ```
          {
              "id": 1,
              "name": "Esperança Futebol Clube",
              "created_at": "2022-09-28T01:36:56.000000Z",
              "updated_at": "2022-09-28T01:36:56.000000Z",
              "players": [
                  {
                      "id": 1,
                      "name": "Fulano Ciclano",
                      "tshirt_number": 2,
                      "created_at": "2022-09-28T01:39:55.000000Z",
                      "updated_at": "2022-09-28T01:41:28.000000Z"
                  }
              ]
          }
        ```

        _Temos como retorno um os campos com as informações do time e também um array de objetos(players), onde cada um desses objetos representa um jogador que faz parte do time listado._

-   **Atualizar informações de um time:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[PUT] ou [PATCH]** {url-base}/api/teams/{id}

        -   _{id} = id do time a ser editado_

        -   _Ex.: http://localhost:8000/api/teams/1_

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        Corpo da requisição:\*\*

        ```
        {
          "name": String | Obrigatória | Única
        }
        ```

        \*\*Exemplo de requisição com dados fictícios:

        ```
        {
          "name": "Estrela feliz"
        }
        ```

        **Exemplo de resposta:**

        ```
          {
              "message": "Team Estrela Feliz edited!",
              "team": {
                  "id": 1,
                  "name": "Estrela Feliz",
                  "created_at": "2022-09-28T01:36:56.000000Z",
                  "updated_at": "2022-09-28T07:25:45.000000Z"
              }
          }
        ```

        _Temos como retorno uma string(message) que informa se a requisição foi concluída com sucesso ou teve algum erro e também um objeto(team) contendo os dados do time atualizado._

-   **Excluir um time:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[DELETE]** {url-base}/api/teams/{id}

        -   _{id} = id do time a ser deletado_

        -   _Ex.: http://localhost:8000/api/teams/1_

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        **Corpo da requisição:**

        ```
        vazio
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
         vazio
        ```

        **Exemplo de resposta:**

        ```
          {
              "message": "Team Estrela Feliz was deleted!"
          }
        ```

        _Temos como retorno uma string(message) informando o status da requisição._

-   **Vincular jogador ao time:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[POST]** {url-base}/api/teams/{id}/players

        -   _{id} = id do time_

        -   _Ex.: http://localhost:8000/api/teams/1/players_

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        **Corpo da requisição:**

        ```
        {
          player: int (id do jogador)
        }
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
        {
          "player": 3
        }
        ```

        **Exemplo de resposta:**

        ```
          {
              "message": "Player Fulano added to Team Estrela Feliz!"
          }
        ```

        _Temos como retorno uma string(message) informando o status da requisição._

-   **Remover jogador do time:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[DELETE]** {url-base}/api/teams/{id}/players

        -   _{id} = id do time_

        -   _Ex.: http://localhost:8000/api/teams/1/players_

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        **Corpo da requisição:**

        ```
        {
          player: int (id do jogador)
        }
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
        {
          "player": 3
        }
        ```

        **Exemplo de resposta:**

        ```
          {
              "message": "Player Fulano removed from Team Estrela Feliz!"
          }
        ```

        _Temos como retorno uma string(message) informando o status da requisição._

-   Listar todos os jogadores de um time:
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[GET]** {url-base}/api/teams/{id}/players

        -   _{id} = id do time_

        -   _Ex.: http://localhost:8000/api/teams/1/players_

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        **Corpo da requisição:**

        ```
        vazio
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
        vazio
        ```

        **Exemplo de resposta:**

        ```
          {
              "id": 2,
              "name": "Estrela Feliz",
              "created_at": "2022-09-28T01:40:44.000000Z",
              "updated_at": "2022-09-28T01:40:44.000000Z",
              "players": [
                  {
                      "id": 2,
                      "name": "Carlos",
                      "tshirt_number": 3,
                      "created_at": "2022-09-28T01:40:50.000000Z",
                      "updated_at": "2022-09-28T01:40:50.000000Z"
                  }
              ]
          }
        ```

        _Temos como retorno um array de objetos(players)com as informações dos jogadores do time infomado._

<br><br><br>

### **Jogadores**

<br>

-   **Adicionar um novo jogador:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[POST]** {URL-BASE}/api/players

        -   _Ex.: http://localhost:8000/api/players_

        <br>

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        **Corpo da requisição:**

        ```
        {
          name: String | Obrigatório,
          tshirt_number: Int | Obrigatório,
          team: Int: Opcional (id do time)
        }
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
        {
          "name": "Fulano",
          "tshirt_number": 11,
          "team": 1
        }
        ```

        **Exemplo de resposta:**

        ```
        {
          "message": "Player Fulano was successfully created"
        }
        ```

        _Temos como retorno uma string (message) contendo a a mensagem de confirmação ou erro_

-   **Listar todos os jogadores cadastrados:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[GET]** {URL-BASE}/api/players

        -   _Ex.: http://localhost:8000/api/players_

        <br>

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        **Corpo da requisição:**

        ```
        vazio
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
         vazio
        ```

        **Exemplo de resposta:**

        ```
          [
            {
                "id": 1,
                "name": "Fulano",
                "tshirt_number": 11,
                "team": 1,
                "created_at": "2022-09-28T01:39:55.000000Z",
                "updated_at": "2022-09-28T01:41:28.000000Z"
            },
            {
                "id": 2,
                "name": "Ciclano",
                "tshirt_number": 3,
                "team": 2,
                "created_at": "2022-09-28T01:40:50.000000Z",
                "updated_at": "2022-09-28T01:40:50.000000Z"
            },
          ]
        ```

        _Temos como retorno um array de objetos onde cada objeto representa um jogador._

-   **Listar um jogador em específico:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[GET]** {url-base}/api/players/{id}

        -   _{id} = id do jogador a ser listado_

        -   _Ex.: http://localhost:8000/api/players/1_

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        **Corpo da requisição:**

        ```
        vazio
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
         vazio
        ```

        **Exemplo de resposta:**

        ```
          {
              "id": 1,
              "name": "Ciclano",
              "tshirt_number": 11,
              "team": 1,
              "created_at": "2022-09-28T01:39:55.000000Z",
              "updated_at": "2022-09-28T01:41:28.000000Z"
          }
        ```

        _Temos como retorno os campos com as informações do jogador listado._

-   **Atualizar dados de um jogador:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[PUT] ou [PATCH]** {url-base}/api/players/{id}

        -   _{id} = id do jogador a ser editado_

        -   _Ex.: http://localhost:8000/api/players/1_

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        <br>

        **Corpo da requisição:**

        _Ao usar PATCH, pelo menos um dos campos a seguir devem ser enviados, no caso de PUT, todos os campos são necessários_

        ```
        {
          name: String,
          tshirt_number: Int,
          team: Int: Opcional (id do time)
        }
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
        {
          "name": "Sr Anonimo",
          tshirt_number: 11,
          team: 1
        }
        ```

        **Exemplo de resposta:**

        ```
          {
              "message": "Player Sr Anonimo edited!",
              "player": {
                  "id": 1,
                  "name": "Sr Anonimo",
                  "tshirt_number": 11,
                  "team": 1,
                  "created_at": "2022-09-28T01:36:56.000000Z",
                  "updated_at": "2022-09-28T07:25:45.000000Z"
              }
          }
        ```

        _Temos como retorno uma string(message) que informa se a requisição foi concluída com sucesso ou teve algum erro e também um objeto(player) contendo os dados do jogador atualizado._

        <br>

-   **Excluir um jogador:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[DELETE]** {url-base}/api/players/{id}

        -   _{id} = id do jogador a ser deletado_

        -   _Ex.: http://localhost:8000/api/players/1_

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        **Corpo da requisição:**

        ```
        vazio
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
         vazio
        ```

        **Exemplo de resposta:**

        ```
          {
              "message": "Player Fulano deleted!"
          }
        ```

        _Temos como retorno uma string(message) informando o status da requisição._

<br><br><br>

### **Partidas**

<br>

-   **Adicionar uma nova partida:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[POST]** {URL-BASE}/api/matches

        -   _Ex.: http://localhost:8000/api/matches_

        <br>

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        **Corpo da requisição:**

        ```
        {
          "date": Date | Obrigatório,
          "start_time": Time | Obrigatório,
          "end_time": Time | Obrigatório,
          "team1": Int | Obrigatório (id do time 1),
          "team1_score": Int | Obrigatório (total de gols do time 1),
          "team2": Int | Obrigatório (id do time 2),
          "team2_score": Int | Obrigatório (total de gols do time 2)
        }
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
        {
          "date": "2022/09/27",
          "start_time": "2022/09/15 10:00",
          "end_time": "2022/09/15 11:00",
          "team1": 1,
          "team1_score": 6,
          "team2": 2,
          "team2_score": 0
        }
        ```

        **Exemplo de resposta:**

        ```
        {
          "message": "Game successfully created"
        }
        ```

        _Temos como retorno uma string (message) contendo a a mensagem de confirmação ou erro_

-   **Listar todas as partidas cadastradas:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[GET]** {URL-BASE}/api/matches

        -   _Ex.: http://localhost:8000/api/matches_

        <br>

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        **Corpo da requisição:**

        ```
        vazio
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
         vazio
        ```

        **Exemplo de resposta:**

        ```
          [
            {
                "id": 1,
                "date": "2022-09-27",
                "start_time": "12:09",
                "end_time": "03:09",
                "team1": 1,
                "team1_score": 0,
                "team2": 2,
                "team2_score": 1,
                "created_at": "2022-09-28T02:42:43.000000Z",
                "updated_at": "2022-09-28T02:42:43.000000Z"
            },
            {
                "id": 2,
                "date": "2022-09-27",
                "start_time": "12:09",
                "end_time": "03:09",
                "team1": 2,
                "team1_score": 4,
                "team2": 1,
                "team2_score": 0,
                "created_at": "2022-09-28T02:44:35.000000Z",
                "updated_at": "2022-09-28T02:44:35.000000Z"
            }
          ]
        ```

        _Temos como retorno um array de objetos onde cada objeto representa uma partida._

        <br>

-   **Listar uma partida em específica:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[GET]** {url-base}/api/matches/{id}

        -   _{id} = id da partida a ser listada_

        -   _Ex.: http://localhost:8000/api/matches/1_

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        **Corpo da requisição:**

        ```
        vazio
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
         vazio
        ```

        **Exemplo de resposta:**

        ```
        {
            "id": 1,
            "date": "2022-09-27",
            "start_time": "12:09",
            "end_time": "03:09",
            "team1": 1,
            "team1_score": 0,
            "team2": 2,
            "team2_score": 1,
            "created_at": "2022-09-28T02:42:43.000000Z",
            "updated_at": "2022-09-28T02:42:43.000000Z"
        },
        ```

        _Temos como retorno os campos com as informações da partida listada._

  <br>

-   **Atualizar dados de uma partida:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[PUT] ou [PATCH]** {url-base}/api/matches/{id}

        -   _{id} = id da partida a ser editada_

        -   _Ex.: http://localhost:8000/api/matches/1_

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        <br>

        **Corpo da requisição:**

        _Ao usar PATCH, pelo menos um dos campos a seguir devem ser enviados, no caso de PUT, todos os campos são necessários_

        ```
        {
          "date": Date,
          "start_time": Time,
          "end_time": Time,
          "team1": Int (id do time 1),
          "team1_score": Int (total de gols do time 1),
          "team2": Int (id do time 2),
          "team2_score": Int (total de gols do time 2)
        }
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
        {
          "id": 1,
          "date": "2022-10-27",
          "start_time": "12:09",
          "end_time": "03:09",
          "team1": 1,
          "team1_score": 0,
          "team2": 2,
          "team2_score": 1,
        }
        ```

        **Exemplo de resposta:**

        ```
        {
            "message": "Match edited!",
            "match": {
                "id": 1,
                "date": "2022-10-27",
                "start_time": "12:09",
                "end_time": "03:09",
                "team1": 1,
                "team1_score": 0,
                "team2": 2,
                "team2_score": 1,
                "created_at": "2022-09-28T02:42:43.000000Z",
                "updated_at": "2022-09-28T08:22:44.000000Z"
            }
        }
        ```

        _Temos como retorno uma string(message) que informa se a requisição foi concluída com sucesso ou teve algum erro e também um objeto(match) contendo os dados da partida atualizada._

        <br>

-   **Excluir uma partida:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[DELETE]** {url-base}/api/matches/{id}

        -   _{id} = id da partida a ser excluida_

        -   _Ex.: http://localhost:8000/api/matches/1_

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        **Corpo da requisição:**

        ```
        vazio
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
         vazio
        ```

        **Exemplo de resposta:**

        ```
          {
              "message": "Match deleted!"
          }
        ```

        _Temos como retorno uma string(message) informando o status da requisição._

<br><br><br>

### **Tabela de Classificação / Ranking**

<br>

-   **Listar tabela de classificação do campeonato:**
-   -   _Para esse exemplo, 'URL-BASE' = http://localhost:8000 (obtida através da etapa anterior)_

        **[GET]** {URL-BASE}/api/ranking

        -   _Ex.: http://localhost:8000/api/ranking_

        <br>

        **Cabeçalho / Header:**

        **Autorização:**

        -   Bearer Token: {TOKEN} _(Obtido no retorno da rota de login)_

        **Accept:**

        -   application/json

        <br>

        **Corpo da requisição:**

        ```
        vazio
        ```

        **Exemplo de requisição com dados fictícios:**

        ```
         vazio
        ```

        **Exemplo de resposta:**

        ```
        [
            {
                "goals": 24,
                "matches_played": 7,
                "matches_won": 4,
                "matches_lost": 2,
                "matches_draw": 1,
                "points": 12,
                "team": "Estrela Feliz",
                "position": 1
            },
            {
                "goals": 5,
                "matches_played": 7,
                "matches_won": 2,
                "matches_lost": 4,
                "matches_draw": 1,
                "points": 6,
                "team": "Time Alegre",
                "position": 2
            }
        ]
        ```

        _Temos como retorno um array de objetos ordenado de forma decrescente (do maior para o menor) com base na pontuação dos times no campeonato. A pontuação se da da seguinte maneira: 1 vitória = 3 pontos, derrota ou empate = 0 pontos. As informações são calculadas com base no resultado das partidas cadastradas._

        <br>
