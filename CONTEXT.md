# PokeHub — Contexto do Projeto

## Sobre o projeto

PokeHub é um sistema web para jogadores do **PokeXGames (PXG)** gerenciarem o compartilhamento de pokémons dentro de uma "house" (recurso do jogo onde múltiplos jogadores compartilham pokes).

### Problema que resolve

No PXG, jogadores de uma mesma house compartilham pokémons. Hoje, o controle de quem está usando qual poke é feito com uma **"carta no chão"** dentro do jogo: quando alguém pega um poke emprestado, anota na carta. É manual, propenso a erro e nada prático.

O PokeHub substitui essa carta por um sistema digital com atualização **em tempo real** — se alguém marca que pegou um poke, todos os membros da house com a página aberta veem na hora, sem precisar dar refresh.

### Visão de longo prazo

Começa como ferramenta de checkout de pokes, mas a ideia é evoluir para uma **plataforma de utilitários para jogadores de PXG** (inventário compartilhado, calendário de eventos, mural da house, estatísticas, tradeboard, etc.). O nome e a arquitetura foram pensados pra crescer sem amarrar a uma única feature.

## Stack técnica

- **Backend:** Laravel 12 (monolítico)
- **Frontend:** Blade + Livewire 3 + Alpine.js + Tailwind CSS
- **Auth:** Laravel Breeze (Livewire stack)
- **Tempo real:** Laravel Reverb (WebSocket nativo) + Laravel Echo
- **Banco:** MySQL ou PostgreSQL (a definir)
- **Ambiente:** Docker (já configurado pelo dev)
- **Hospedagem:** a decidir

### Por que essa stack

- O dev (Ronaldo) já manda bem em PHP/Laravel e Vue/Nuxt
- Laravel monolítico com Livewire entrega mais rápido pra esse escopo do que SPA + API separados
- Reverb resolve o tempo real sem custo de Pusher/serviço externo
- Tudo self-hosted, sem dependência de SaaS externo no core

## Domínio / Entidades

### users
Conta do jogador. Auth padrão do Breeze.

### houses
Uma "house" do jogo no PokeHub. Tem um owner (criador) e múltiplos membros.
Campos principais: `id`, `name`, `slug`, `invite_code` (string curta única), `owner_id`, `created_at`.

### house_members
Tabela de junção entre user e house, com papel.
Campos: `id`, `house_id`, `user_id`, `role` (`owner` | `admin` | `member`), `joined_at`.
Restrição: `unique(house_id, user_id)`.

### pokemons
Pokémon cadastrado em uma house. Pertence à house, não ao usuário individual.
Campos: `id`, `house_id`, `name` (apelido), `species`, `level`, `sprite_url`, `notes`, `current_holder_id` (FK users, nullable — quem está com o poke agora), `held_since` (timestamp, nullable).

### pokemon_checkouts
Histórico/log de uso. Cada vez que alguém pega e devolve, gera um registro.
Campos: `id`, `pokemon_id`, `user_id`, `checked_out_at`, `returned_at` (nullable).

**Padrão importante:** o estado atual fica em `pokemons.current_holder_id` (query rápida pra tela principal) e o histórico completo em `pokemon_checkouts` (auditoria).

## Funcionalidades do MVP

1. Auth (cadastro/login) — Breeze
2. Criar house (vira owner automaticamente)
3. Convite por código curto: `/join/{invite_code}`
4. Cadastrar/editar/remover pokes da house (owner/admin)
5. Listar pokes da house com status visual (livre / em uso por X há Y horas)
6. **Pegar (checkout)** poke livre — botão na tela
7. **Devolver (return)** poke que está com você
8. Atualização em tempo real quando outro membro faz checkout/return
9. Gerenciar membros (listar, remover, regerar invite code)

## Eventos de broadcast

Todos no canal privado `house.{houseId}`, autorização em `routes/channels.php` (só membros da house podem escutar).

- `PokemonCheckedOut` — quando alguém pega um poke
- `PokemonReturned` — quando alguém devolve
- `HouseMemberJoined` — quando novo membro aceita convite (opcional no MVP)

## Decisões técnicas importantes

### Concorrência no checkout
Dois jogadores podem clicar "pegar" ao mesmo tempo. Resolver com transaction + `lockForUpdate()`:

```php
DB::transaction(function () use ($pokemon) {
    $fresh = Pokemon::lockForUpdate()->find($pokemon->id);

    if ($fresh->current_holder_id !== null) {
        throw new \Exception('Pokemon já foi pego por outro jogador');
    }

    $fresh->update([
        'current_holder_id' => auth()->id(),
        'held_since' => now(),
    ]);

    PokemonCheckout::create([
        'pokemon_id' => $fresh->id,
        'user_id' => auth()->id(),
        'checked_out_at' => now(),
    ]);

    broadcast(new PokemonCheckedOut($fresh))->toOthers();
});
```

### Authorization
- `HousePolicy` regula acesso à house (só membros veem/agem)
- `PokemonPolicy` regula CRUD de pokes (só owner/admin)
- Ações de checkout/return: qualquer membro pode

### Multi-house
Um user pode estar em várias houses (entra em houses de amigos diferentes). Schema já contempla via `house_members`.

### Invite por código
Gera string curta (6-8 caracteres alfanuméricos) única por house. Compartilhada via WhatsApp/Discord. Rota `/join/{code}` adiciona o user na house. Possibilidade futura de regerar código.

## Setup Docker — pontos de atenção

Como Reverb é um servidor WebSocket separado, o `docker-compose.yml` precisa ter pelo menos:

- **Service `app`** — Laravel/PHP-FPM normal
- **Service `reverb`** — rodando `php artisan reverb:start --host=0.0.0.0 --port=8080`, com porta `8080:8080` exposta
- **Service `queue`** — rodando `php artisan queue:work` (broadcasts vão pela queue em produção)
- **Service `db`** — MySQL ou PostgreSQL
- **Service `vite`** (opcional, dev) — pra hot reload, porta `5173` exposta com `server.host: '0.0.0.0'` no `vite.config.js`

### Variáveis de ambiente críticas

```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=pokehub
REVERB_APP_KEY=<key_aleatoria>
REVERB_APP_SECRET=<secret_aleatorio>
REVERB_HOST=0.0.0.0       # onde o servidor escuta (dentro do container)
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="localhost"   # onde o navegador conecta (de fora do container)
VITE_REVERB_PORT=8080
VITE_REVERB_SCHEME=http
```

Cuidado: `REVERB_HOST` vs `VITE_REVERB_HOST` são diferentes (interno vs externo).

## Roadmap

### Fase 0 — Setup (1-2 dias)
- Docker com services de app, reverb, queue, db, vite
- Instalar Breeze (Livewire) + Reverb
- Validar broadcast "hello world" end-to-end

### Fase 1 — MVP (1-2 semanas)
- Migrations, models, policies
- CRUD de house + invite por código
- CRUD de pokes
- Checkout/return com lock de transação
- Tempo real via Reverb + Echo + Livewire listeners
- UI responsiva (galera vai usar muito no celular)

### Fase 2 — Refinos pós-feedback (1 semana)
A definir com base no uso real. Candidatos:
- Histórico visível na tela
- Aviso de "fulano está com esse poke há X horas"
- Filtros e busca
- Sprites/imagens dos pokes
- Notificações

### Fase 3 — Plataforma (2-4 semanas, depois de validar MVP)
Possíveis features (priorizar 2-3 baseado em demanda):
- Inventário compartilhado (itens, TMs, recursos)
- Calendário de hunts/eventos
- Mural/chat da house
- Estatísticas de uso
- Tradeboard
- Clãs/alianças entre houses

### Fase 4 — SaaS-ificação (se houver tração)
Planos, branding, onboarding, analytics, infra robusta.

## O que NÃO fazer agora

- Não construir multi-tenant elaborado no MVP
- Não montar design system — Tailwind direto resolve
- Não criar API pública — só Livewire por enquanto
- Não pensar em app mobile nativo — PWA depois se precisar
- Não otimizar performance prematuramente

## Princípios de código

- Código pronto pra produção desde o começo (sem "depois eu arrumo")
- Sem hardcode de URLs — sempre `config()` ou rotas nomeadas
- Storage com driver configurável (local em dev, s3 em prod no futuro)
- Dockerfile multi-stage, dependências de prod sem `--dev`
- Testes pelo menos nas regras de negócio críticas (checkout, autorização)

## Sobre o dev

- Nome: Ronaldo
- Stack confortável: PHP, Laravel, Nuxt/Vue, JS/TS
- IDE: VS Code e PHPStorm
- Localização: Brasil (PT-BR nas conversas)