# API de Captação de Clientes com Integração Huggy

Este projeto é uma API construída com o framework Laravel, destinada à captação de clientes e integração com a plataforma Huggy.

## Requisitos

- PHP >= 8.0.1
- Composer
- Servidor de banco de dados (MySQL, PostgreSQL, SQLite)
- Credenciais de acesso à API da Huggy

## Instalação

Siga os passos abaixo para configurar o projeto em seu ambiente local.

1. **Clone o Repositório**

   Execute o comando abaixo no terminal para clonar o repositório:
```
   git clone https://github.com/natooliv/captacao_huggy_clientes.git
```
2. **Instalação das Dependências**

Navegue até o diretório do projeto na pasta backend e execute o seguinte comando para instalar as dependências necessárias:
```
cd backend
```
```
composer install
```
3. **Configuração do Ambiente**

Copie o arquivo `.env.example` para `.env`:


Em seguida, abra o arquivo `.env` e configure as variáveis de ambiente, incluindo as conexões com o banco de dados, credenciais da API da Huggy, e outros serviços necessários.

4. **Geração da Chave da Aplicação**

Gere a chave da aplicação Laravel com o comando:
```
php artisan key:generate
```

5. **Migrações do Banco de Dados**

Execute as migrações para criar as tabelas no banco de dados:
```
php artisan migrate
```

6. **Inicie o Servidor de Desenvolvimento**

Execute o servidor de desenvolvimento do Laravel:
```
php artisan serve
```
Isso iniciará o servidor, e você poderá acessar a aplicação geralmente em `http://localhost:8000`.


 ### Ngrok

Para expor a API localmente, você pode usar o Ngrok. Baixe e configure o Ngrok conforme a documentação oficial em [ngrok.com](https://ngrok.com/). Depois de configurado, execute o Ngrok para expor sua aplicação Laravel e compartilhe a URL gerada com sua equipe ou clientes para acessar sua API temporariamente.

## Documentação da API

A documentação da API, incluindo os endpoints específicos para a integração com a Huggy, é gerada automaticamente pelo L5-Swagger e está disponível em:
```
http://localhost:8000/api/documentation
```

# Front-end da Aplicação Huggy
 Em outro terminal entre na pasta de frontend
 ```
cd frontend
```

### Instalação de Dependências

Execute o comando abaixo para instalar as dependências necessárias do projeto:
```
npm install
```

### Após instalação execute o servidor da aplicação 
```
npm run serve
``` 


Certifique-se de substituir `localhost:8000` pela URL e porta onde sua aplicação está rodando.

## Licença

Este projeto está licenciado sob a Licença MIT.

