# 🏗️ Estrutura do Banco de Dados

Este projeto utiliza um único banco de dados **PostgreSQL** para gerenciar todas as informações da aplicação. A arquitetura foi projetada para ser normalizada e eficiente, centralizando a autenticação e separando os dados de perfil dos colaboradores.

A filosofia principal é usar a tabela `users` como a única fonte de verdade para identidade e controle de acesso, enquanto outras tabelas adicionam informações específicas a esses usuários.

As três tabelas fundamentais que sustentam o sistema de usuários, perfis e convites são: `users`, `collaborator_profiles`, e `invitations`.

### Tabela `users`

Esta é a tabela central do sistema. Ela serve como a única fonte para **autenticação e autorização**. Todos que acessam o sistema, independentemente do seu papel, possuem um registro aqui.

| Coluna | Tipo | Descrição |
| :--- | :--- | :--- |
| `id` | `BIGINT` | Chave Primária, auto-incremento. |
| `name` | `VARCHAR(255)`| Nome completo do usuário. |
| `email` | `VARCHAR(255)`| E-mail de login, deve ser único. |
| `password` | `VARCHAR(255)`| Senha criptografada (hashed). |
| **`role`** | `VARCHAR(255)`| **(Importante)** Define o perfil de acesso do usuário. Valores padrão: `administrador`, `gente_e_cultura`, `colaborador`. |
| `created_at` | `TIMESTAMP` | Data e hora de criação do registro. |
| `updated_at` | `TIMESTAMP` | Data e hora da última atualização. |

### Tabela `collaborator_profiles`

Esta tabela armazena os dados cadastrais e pessoais adicionais, específicos dos usuários que são colaboradores. Ela possui uma relação de **um-para-um** com la tabela `users`.

| Coluna | Tipo | Descrição |
| :--- | :--- | :--- |
| `id` | `BIGINT` | Chave Primária. |
| **`user_id`** | `BIGINT` | **Chave Estrangeira.** Referencia `users(id)`. É o elo que conecta o perfil ao usuário. |
| `cpf` | `VARCHAR(14)` | CPF do colaborador, deve ser único. |
| `celular` | `VARCHAR(20)` | Telefone celular (opcional). |
| `cep` | `VARCHAR(9)` | CEP do endereço (opcional). |
| `uf` | `VARCHAR(2)` | Unidade Federativa (preenchido via API). |
| `localidade`| `VARCHAR(30)` | Cidade (preenchido via API). |
| `bairro` | `VARCHAR(40)` | Bairro (preenchido via API). |
| `logradouro`| `VARCHAR(100)`| Rua/Avenida (preenchido via API). |
| `created_at` | `TIMESTAMP` | Data e hora de criação do registro. |
| `updated_at` | `TIMESTAMP` | Data e hora da última atualização. |

### Tabela `invitations`

Responsável por gerenciar o fluxo de convites para novos colaboradores, garantindo que apenas e-mails convidados possam se cadastrar através de um link seguro.

| Coluna | Tipo | Descrição |
| :--- | :--- | :--- |
| `id` | `BIGINT` | Chave Primária. |
| `email` | `VARCHAR(255)`| O e-mail para onde o convite foi enviado. Deve ser único enquanto o convite estiver ativo. |
| `token` | `VARCHAR(32)` | Um token único e seguro gerado para o link de cadastro. |
| `registered_at`| `TIMESTAMP` | Armazena a data e hora em que o convite foi utilizado. Inicialmente nulo. |
| `created_at` | `TIMESTAMP` | Data e hora de criação do registro. |
| `updated_at` | `TIMESTAMP` | Data e hora da última atualização. |

### Diagrama de Relacionamento (ERD)

O diagrama abaixo ilustra a relação principal entre `users` e `collaborator_profiles`.

```mermaid
erDiagram
    users {
        bigint id PK
        varchar name
        varchar email UK
        varchar role "administrador, gente_e_cultura, colaborador"
    }
    collaborator_profiles {
        bigint id PK
        bigint user_id FK
        varchar cpf UK
        varchar celular
        varchar cep
    }

    users ||--o{ collaborator_profiles : "possui um"