# 📜 Codex ProITEC (`mod_codexproitec`)

O **Codex ProITEC** é um módulo de atividade de gamificação desenvolvido para o Moodle, responsável por gerenciar a coleção de **Gemas do Conhecimento** e a recompensa final do curso autoinstrucional ProITEC no IFRN.

---

## 🌐 Documentação Interativa HTML (`docs/`)

Acesse a documentação técnica rica completa em HTML com imagens do Codex, capas e vetorização das gemas:
👉 [**Documentação Técnica em HTML (`docs/index.html`)**](docs/index.html)

---

## 🚀 Funcionalidades e Mecânica de Jogo

- **O Livro do Codex**: Apresenta ao estudante um livro/interface interativo que reúne o progresso de conquistas no curso.
- **Coleção de Gemas do Conhecimento**: Conforme o estudante avança nas disciplinas da matriz ProITEC, ele desbloqueia gemas simbólicas de conhecimento.
- **Desbloqueio da Chave do Codex**: Ao colecionar todas as gemas de conhecimento das quatro disciplinas, o estudante completa a chave que abre o Codex.
- **Recompensa Final**: Como recompensa pela conclusão do Codex, o sistema libera o **Mapa de Dicas Exclusivo** projetado para auxiliar o estudante a passar no Exame de Seleção do ProITEC.

---

## 🎨 Recursos Visuais (`pix/`)

- `codex_grande.png`: Ilustração em alta resolução do Codex fechado/em progresso.
- `codex_completo.svg`: Gráficos vetoriais do Codex totalmente desbloqueado com suas gemas reluzentes.
- `livro.png`: Ícone e elemento de interface do livro de conquistas.

---

## 📥 Instalação

### Opção 1: Via Interface de Administração do Moodle
1. Baixe o arquivo `.zip` da release do módulo.
2. Acesse **Administração do site → Plugins → Instalar plugins** no Moodle.
3. Faça o upload do arquivo `.zip` e conclua a atualização do banco de dados.

### Opção 2: Instalação Manual
1. Clone o repositório no diretório `mod/codexproitec` da sua instalação Moodle:
   ```bash
   cd /caminho/do/seu/moodle/mod
   git clone git@github.com:proitec-moodle-suite/moodle-mod_codexproitec.git codexproitec
   ```
2. Acesse **Administração do site → Notificações** para concluir a instalação.

---

## 👥 Autores e Contribuidores

- **Kelson da Costa Medeiros** (<kelson.medeiros@ifrn.edu.br> / <kelsoncm@gmail.com>)
- **Matheus Mathias Rocha Lúcio de Moraes** (<mathias.matheus76@gmail.com>)

---

## 📜 Licença

Este plugin é software livre distribuído sob os termos da **GNU General Public License v3.0** (GPL-3.0). Veja o arquivo [LICENSE.md](LICENSE.md) para mais detalhes.
