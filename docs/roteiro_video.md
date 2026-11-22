# Roteiro do vídeo (5 a 10 minutos)

**0:00 – 0:30 Apresentação (aparecendo na câmera)**
"Olá, meu nome é ___, RA ___, curso de Análise e Desenvolvimento de Sistemas, polo ___, e sou aluno da UNIPAR EAD. Esta é a minha APO de Fundamentos de Programação para Internet: um gerenciador logístico em PHP."

**0:30 – 1:30 Visão geral**
Mostrar o menu, o painel inicial e dizer as tecnologias: PHP, MySQL, HTML, CSS, JavaScript, Bootstrap, Git/GitHub.

**1:30 – 3:30 Demonstração**
1. Cadastrar um produto (mostrar validação e a lista).
2. Cadastrar dois endereços (origem e destino) e um transportador (mostrar as máscaras).
3. Novo frete: escolher transportador/origem/destino, adicionar vários produtos com o botão dinâmico (mostrar totais mudando) e gerar o frete.
4. Abrir o detalhe do frete.
5. Relatório diário: escolher a data, consultar e baixar o CSV (abrir no Excel).

**3:30 – 5:00 Comparar com o Figma/PDF**
Abrir `Etapa1_Telas_Prototipo.pdf` ao lado de cada tela do sistema e comentar semelhanças e o que mudou.

**5:00 – 8:00 Código (por alto)**
- `includes/db.php`: conexão PDO. `includes/helpers.php`: funções úteis (escape de HTML, CSRF, flash).
- `produtos/form.php`: validação e INSERT/UPDATE com consultas preparadas.
- `fretes/form.php`: recebe `produto_id[]` e `quantidade[]`, usa transação e grava em `fretes` + `frete_produtos` (1 para N).
- `assets/js/frete.js`: adiciona/remove linhas e recalcula totais.
- `relatorios/csv.php` + `consulta_dia.php`: consulta por data e geração do CSV em PHP.
- `database/schema.sql`: tabelas e chaves estrangeiras.

**8:00 – 9:00 Git/GitHub**
Mostrar o repositório no GitHub, o histórico de commits e o README.

**Fim:** agradecer e encerrar. Publicar no YouTube como **"Não Listado"**.
