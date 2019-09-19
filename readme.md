# teste-guarida-email

## Proposta

A área financeira da XPTO paga prestadores de serviço diariamente. Geralmente os prestadores de serviços enviam notas fiscais para pagamento anexadas em email. Os colaboradores do financeiro precisam abrir email por email, ver as informações e lançar no sistema para que o pagamento seja efetuado.

Você deve desenvolver um processo que acesse uma caixa de email, leias informações do prestador de serviço e faça o download do anexo. Depois de interpretar as informações, elas devem ser enviadas para uma API REST, para serem registradas no sistema.

### Template do email para teste: Todo o email deve conter um anexo, simbolizando a nota fiscal

Bom dia,

Segue meus dados de contato e informações para pagamento

Nome: Guarida Imóveis
Endereço: Protásio alves, 1309
Valor: R\$1.300,50
Vencimento:12/19

Att.

A api que as informações devem ser disparadas não tem relevância para esse exercício,
apenas o processo deve finalizar enviando os dados para uma API REST.

Aspectos importantes que vamos observar:

- Clean Code
- Separation of concerns
- SOLID Principles
- Tests
- Design Patterns
- Simplicity
- Conventions and PSRs

Esse teste pode ser enviado por email ou disponibilizado em um controle de versão, como
Github. Gitlab, Bitbucket, etc.

## Dependências

- Docker

## Instruções para rodar

Você pode optar rodar de duas formas

### 1) Execute o arquivo `run.sh` da pasta raiz, podendo ser via terminal com por exemplo:

`sh ./run.sh`

Este comando ira executar uma série de passos que você poderá acompanhar via terminal, referente a:

1. Build dos containers PHP e Nginx
2. Instalação das dependências do framework Laravel
3. O ambiente pode ser acessado no http://localhost:9001

### 2) Execute os seguintes passos separadamente no seu terminal dentro da pasta do projeto:

`docker-compose up --build -d`

`docker exec -it php bash -c "cd /var/www/html && php composer.phar install"`

`docker ps -a`

O ambiente pode ser acessado no http://localhost:9001

### Variaveis de Ambiente

Assim que o ambiente estiver rodando, o arquivo com as variaveis de ambiente será criado em `laravel/.env`

Abaixo uma explicação sobre variaveis de ambiente que devem ser modificadas

- IMAP_HOST: É o host do e-mail que você vai receber as Notas Fiscais
- IMAP_PORT: É a porta de acesso IMAP ao seu e-mail, apesar de haver um padrão, verifique com seu provedor possíveis especificidades
- IMAP_USERNAME: Seu usuário de e-mail, como faabiopontes@gmail.com
- IMAP_PASSWORD: A senha, ou chave de aplicação como no caso do Gmail, do seu E-mail, verifique com seu provedor possíveis especificidades em caso de problemas
- API_ENDPOINT: É o endpoint que serão enviados as Notas Fiscais e seus Anexos. Caso você execute o método de verificação e todas as mensagens apontem falha, é bem possível que o endereço não esteja preenchido corretamente

### Importante

- Sempre fique atento que não exista outro processo rodando na porta 9001 pois é a porta que o Nginx fará o espelhamento ao executar o docker
- Serão buscadas mensagens enviadas nos últimos 2 dias no formato indicado, pois aumentando o número de dias aumentava consideravelmente o tempo de busca das mensagens
- São filtradas mensagens que estão no formato e não estejam lidas, assim que as mensagens são enviadas para a API estas são marcadas como lidas e movidas para uma pasta "Notas Fiscais Enviadas", que se não existir será criada em seu e-mail

### Todo

- Testar endpoint e métodos dos serviços afim de aumentar code coverage
- Melhorar a resposta ao usuário em caso de erros ao longo do processo de verificação e envio
