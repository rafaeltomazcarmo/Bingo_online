# Bingo Online

Projeto em PHP para controlar o sorteio de números de bingo, com acompanhamento visual dos números já sorteados e reset do jogo.

## Visão geral

Este sistema foi desenvolvido para funcionar em um ambiente web simples, usando PHP + MySQL. Ele sorteia números entre 1 e 75, organiza por faixa de letras do bingo (`B`, `I`, `N`, `G`, `O`) e permite acompanhar os números já marcados em uma tela de acompanhamento.

A lógica principal baseia-se em uma tabela chamada `numeros_sorteados`, onde cada número sorteado é salvo no banco para impedir duplicações.

---

## Tecnologias utilizadas

- PHP
- MySQL / PDO
- HTML
- CSS
- JavaScript (para atualização assíncrona em tela)

---

## Estrutura dos arquivos

- `db.php` — conexão com o banco de dados MySQL.
- `bingo.php` — tela principal com grade do bingo e atualização dos números sorteados em tempo real.
- `sorteio.php` — página de sorteio de número.
- `sorteio2.php` — versão alternativa da tela de sorteio.
- `acompanhamento.php` — página de acompanhamento visual dos números sorteados.
- `acompanhamento2.php` — versão alternativa de acompanhamento.
- `atualizar_numeros.php` — endpoint em JSON para retornar os números já sorteados.
- `reset.php` — limpa a tabela e reinicia o jogo.
- `style.css` — CSS antigo/legado.
- `style2.css` — CSS principal usado nas páginas de jogo.
- `bkp.php` — arquivo de backup/placeholder padrão de Hostinger, não parece fazer parte da aplicação principal.

---

## Como o sistema funciona

### 1. Conexão com o banco
O arquivo `db.php` é responsável por estabelecer a conexão com o banco de dados via PDO. Em uma versão mais segura, essa configuração deve ser movida para variáveis de ambiente ou para um arquivo de configuração separado, fora do repositório.

### 2. Sorteio
Os arquivos `sorteio.php` e `sorteio2.php` verificam quais números já foram salvos na tabela `numeros_sorteados` e escolhem um número ainda não sorteado.

A lógica também determina a letra correspondente do bingo:

- 1 a 15 = B
- 16 a 30 = I
- 31 a 45 = N
- 46 a 60 = G
- 61 a 75 = O

### 3. Persistência
Cada número sorteado é inserido com algo como:

```sql
INSERT INTO numeros_sorteados (numero) VALUES (:numero)
```

E, ao resetar:

```sql
DELETE FROM numeros_sorteados
```

### 4. Acompanhamento visual
As páginas `bingo.php` e `acompanhamento.php` montam uma grade com cinco colunas (`B`, `I`, `N`, `G`, `O`) e exibem os números com destaque quando já foram sorteados.

A atualização é feita por JavaScript com `fetch()` para `atualizar_numeros.php`, que responde em JSON com a lista de números sorteados.

---

## Estrutura esperada do banco

O projeto usa uma tabela com o seguinte comportamento mínimo:

```sql
CREATE TABLE numeros_sorteados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero INT NOT NULL UNIQUE
);
```

Essa tabela garante que um mesmo número não seja sorteado duas vezes.

---

## Fluxo de uso

1. Configurar o banco MySQL e criar a tabela `numeros_sorteados`.
2. Iniciar o servidor PHP/Apache.
3. Acessar a página principal, normalmente:
   - `http://localhost/bingo.php`
   - ou `http://localhost/sorteio.php`
4. Clicar em "Sortear Número".
5. Acompanhar os números na tela de bingo e no acompanhamento.
6. Usar `reset.php` para reiniciar o jogo.

---

## Situação atual do projeto

### Estado funcional

O projeto está em um estado funcional básico para uso local:

- sorteio de números funciona;
- gravação no banco funciona;
- acompanhamento visual existe;
- reset do jogo existe;
- páginas com aparência simples e funcionalidade mínima estão prontas.

### Problemas e pontos de melhoria

Mesmo funcionando, o projeto ainda está em uma fase inicial/desorganizada:

- existem várias versões duplicadas (`sorteio.php`, `sorteio2.php`, `acompanhamento.php`, `acompanhamento2.php`);
- há arquivos legados e redundantes (`style.css`, `bkp.php`);
- a lógica está espalhada em várias telas ao invés de estar centralizada;
- as credenciais do banco estão diretamente no código em `db.php`, o que é um risco de segurança;
- não há arquivo de configuração por ambiente (`.env`), variáveis de ambiente ou arquivo de setup formal;
- não há testes automatizados;
- não há documentação de instalação em produção;
- o projeto parece ser uma base experimental/estudo, sem padronização final.

### Conclusão da situação atual

O projeto está em um estado de desenvolvimento inicial, com funcionalidade básica pronta e operante localmente, mas ainda precisa de organização, refatoração e melhorias de segurança antes de ser considerado um sistema mais robusto ou pronto para produção.

---

## Alterações recomendadas por segurança

As melhorias prioritárias para deixar o projeto mais seguro são:

- mover as credenciais do banco para variáveis de ambiente ou arquivo de configuração não versionado;
- remover mensagens de erro detalhadas em produção para evitar vazamento de informações internas;
- evitar expor dados sensíveis em arquivos de documentação, logs, backups e commit de código;
- adicionar `.gitignore` para impedir envio de arquivos locais e configuração de ambiente;
- validar e normalizar entradas do usuário antes de manipular o banco;
- revisar permissões e acesso do banco para limitar o que cada usuário pode executar;
- padronizar os arquivos de layout e manter apenas uma versão ativa do fluxo principal.

---

## Observações importantes

- O arquivo `bkp.php` parece ser apenas um placeholder padrão da Hostinger e não faz parte do fluxo do bingo.
- As páginas de estilo e de sorteio têm versões duplicadas, o que pode gerar confusão na manutenção.
- O projeto precisa de uma revisão de segurança antes de ser expandido para uso em produção.

---

## Sugestões futuras

- unificar as telas de sorteio e acompanhamento em uma estrutura única;
- remover arquivos duplicados e antigos;
- separar HTML, CSS e PHP em arquivos e layouts mais organizados;
- centralizar configuração de ambiente e segredos em um local seguro;
- adicionar validações e tratamento de erros mais rigorosos;
- criar migrações e documentação de instalação/uso;
- revisar o fluxo do reset e acesso administrativo para evitar ações indevidas.

---

## Resumo rápido

Este é um projeto PHP simples de bingo online com sorteio, persistência em MySQL e acompanhamento visual. Ele já funciona como base, mas ainda está em fase inicial, com várias versões duplicadas e pontos que precisam de refatoração e segurança.

