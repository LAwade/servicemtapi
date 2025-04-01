# API REST PARA Servidores Da SEPLAG

## Visão Geral
Sistema desenvolvido em PHP 8.1 para o processo seletivo da SEPLAG, na qual consiste em salvar informações de Servidor Efetivo, Servidor Temporário, Unidade e Lotação. Também foi seguido as tecnologias compostas no conforme o proposto. 

- **Docker** - Containerização da aplicação
- **Laravel** - Framework PHP para desenvolvimento web
- **PostgreSQL** - Banco de dados relacional
- **Min.io** - Armazenamento de arquivos

## Tutorial de inicialização

### 1. Clone
```bash
git clone https://github.com/LAwade/servicemtapi.git
cd servicemtapi
```

### 2. Orquestração de Containers com Docker Compose
```bash
docker-compose up -d
```

### 3. Acesse o container ServiceMTAPI
```bash
docker exec -it ServiceMTAPI /bin/bash 
```

### 4. Executes os seguintes comandos (Caso necessário)
```bash
php artisan migrate 
php artisan serve --host=0.0.0.0 --port=8181
```

## Algumas Rotas da API

### Autenticação
| Método | Rota | Descrição |
|---------|------|-------------|
| `POST` | `/login` | Autenticação para criação do token |
| `POST` | `/logout` | Logoff e expira o token |
| `POST` | `/refresh` | Refresh o token para um novo |

### Cidades
| Método | Rota | Descrição |
|---------|------|-------------|
| `GET` | `/cidade` | Apresenta as cidades |
| `GET` | `/show-cidade/{cid_id}` | Identifica uma cidade com ID |
| `POST` | `/store-cidade` | Cadastra nova cidade |
| `PUT` | `/update-cidade/{cid_id}` | Updade de uma cidade com ID |

### Endereços
| Método | Rota | Descrição |
|---------|------|-------------|
| `GET` | `/endereco` | Apresenta os endereços |
| `GET` | `/show-endereco/{end_id}` | Identifica um endereço com ID |
| `POST` | `/store-endereco` | Cadastra novo endereço |
| `PUT` | `/update-endereco/{end_id}` | Update um endereço com ID |
| `DELETE` | `/delete-endereco/{end_id}` | Deleta o endereço com ID|

### Arquivos de Imagem de Pessoas
| Método | Rota | Descrição |
|---------|------|-------------|
| `GET` | `/foto-pessoa` | Apresenta as fotos |
| `GET` | `/show-foto-pessoa/{pes_id}` | Identifica uma foto com ID |
| `POST` | `/store-foto-pessoa/{pes_id}` | Cadastra nova foto |
| `PUT` | `/update-foto-pessoa/{pes_id}` | Update uma foto com ID |
| `DELETE` | `/delete-foto-pessoa/{pes_id}` | Deleta a foto com ID |

### Servidores Efetivos e Temporários
| Método | Rota | Descrição |
|---------|------|-------------|
| `GET` | `/servidor-efetivo` | Apresenta os servidores efetivos |
| `GET` | `/show-servidor-efetivo/{pes_id}` | Identifica um servidor efetivo com ID |
| `POST` | `/store-servidor-efetivo` | Cadastra novo servidor efetivo |
| `PUT` | `/update-servidor-efetivo/{pes_id}` | Update um servidor efetivo com ID |
| `DELETE` | `/delete-servidor-efetivo/{pes_id}` | Deleta um servidor efetivo com ID |
| `GET` | `/servidor-temporario` | Apresenta todos os servidores temporários |
| `GET` | `/show-servidor-temporario/{pes_id}` | Exibe um servidor temporário com ID |
| `POST` | `/store-servidor-temporario` | Cadastra um novo servidor temporário |
| `PUT` | `/update-servidor-temporario/{pes_id}` | Update um servidor temporário com ID |
| `DELETE` | `/delete-servidor-temporario/{pes_id}` | Deleta um servidor temporário com ID |

### Unidades e UnidadeEndereço
| Método | Rota | Descrição |
|---------|------|-------------|
| `GET` | `/unidade` | Apresenta as unidades |
| `GET` | `/show-unidade/{unidade_id}` | Identifica uma unidade com ID |
| `POST` | `/store-unidade` | Cadastra nova unidade |
| `PUT` | `/update-unidade/{unidade_id}` | Update uma unidade com ID |
| `DELETE` | `/delete-unidade/{unidade_id}` | Deleta uma unidade com ID |
| `GET` | `/unidade-endereco` | Apresenta todas as unidade-endereço |
| `GET` | `/show-unidade-endereco/{unid_id}` | Mostra o vínculo com unidade e endereço |
| `POST` | `/store-unidade-endereco` | Cadastra novo vínculo com unidade e endereço |
| `PUT` | `/update-unidade-endereco/{unid_id}` | Update os vínculos com ID |
| `DELETE` | `/delete-unidade-endereco/{unid_id}` | Deleta o vínculo com ID |

## Importante 
- Foram apresentadas algumas das rotas disponíveis na API para verificar todas as rotas acesse o arquivo `routers/api.php`
- Depois de todos os container rodando acesso o navegador com a URL: `http://localhost:8181`
- Todas as autenticações feitas tem o tempo de 5 minutos de duração, após esse tempo é necessário realizar o refresh.