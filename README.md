# Gerenciador Logístico

Aplicação web em **PHP** para gerenciar o transporte de mercadorias: cadastro de **produtos**, **endereços** (origem e destino) e **transportadores**, geração de **fretes** (um transportador pode levar vários produtos — relação 1 para N) e **relatório diário** de fretes com download em CSV.

Projeto da **Atividade Prática Orientada (APO)** da disciplina *Fundamentos de Programação para Internet* — UNIPAR EAD.

## Tecnologias

- **Backend:** PHP 8 (PDO com consultas preparadas)
- **Banco de dados:** MySQL/MariaDB (XAMPP)
- **Frontend:** HTML, CSS, JavaScript e **Bootstrap 5** (incluído localmente em `assets/vendor`, funciona sem internet)
- **Versionamento:** Git/GitHub

## Funcionalidades

- CRUD de produtos, endereços e transportadores (com validação e máscaras de CEP, CNPJ e telefone)
- Geração de frete com **campos dinâmicos em JavaScript** (adicionar/remover produtos e totais de peso e valor em tempo real)
- Um frete relaciona 1 transportador, 1 origem, 1 destino e **N produtos**
- Detalhe do frete com opção de impressão
- **Relatório dos fretes de um dia**, com consulta por data e download em CSV (compatível com Excel)
- Menu de navegação, mensagens de feedback, confirmação antes de excluir e proteção contra CSRF/XSS/SQL Injection

## Como executar (XAMPP)

1. Copie esta pasta para `C:\xampp\htdocs\gerenciador-logistico`.
2. No painel do XAMPP, inicie **Apache** e **MySQL**.
3. Acesse `http://localhost/gerenciador-logistico/instalar.php` para criar o banco `logistica` e as tabelas.
   - Opcional: clique em **Inserir dados de exemplo**.
   - Alternativa: importe `database/schema.sql` pelo phpMyAdmin.
4. Abra `http://localhost/gerenciador-logistico/`.

Se o seu MySQL tiver usuário/senha diferentes de `root` / (vazio), ajuste `config/config.php`.

## Estrutura de diretórios

```
gerenciador-logistico/
├── index.php               # painel inicial
├── instalar.php            # cria banco e tabelas
├── config/config.php       # configuração do banco
├── includes/               # db.php, helpers.php, header.php, footer.php
├── produtos/               # index, form, excluir
├── enderecos/              # index, form, excluir
├── transportadores/        # index, form, excluir
├── fretes/                 # index, form (gerar frete), ver, excluir
├── relatorios/             # index (consulta), csv (download), consulta_dia
├── assets/                 # css, js (frete.js, mascaras.js) e vendor (Bootstrap)
├── database/               # schema.sql, dados_exemplo.sql
└── docs/                   # protótipo das telas (Etapa 1)
```

## Modelo de dados

- `produtos` (id, nome, descricao, peso_kg, valor)
- `enderecos` (id, apelido, cep, logradouro, numero, complemento, bairro, cidade, uf)
- `transportadores` (id, nome, cnpj, telefone, placa_veiculo)
- `fretes` (id, transportador_id, origem_id, destino_id, data_frete, observacao, valor_total, peso_total)
- `frete_produtos` (id, frete_id, produto_id, quantidade) — liga **1 frete a N produtos**

## Protótipo das telas

O esboço das telas (Etapa 1) está em [`docs/Etapa1_Telas_Prototipo.pdf`](docs/Etapa1_Telas_Prototipo.pdf).

## Autor

Antonio Anuto — RA 09052195 — Polo [Umuarama] — Análise e Desenvolvimento de Sistemas, UNIPAR EAD.

