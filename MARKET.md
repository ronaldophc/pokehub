# PokeHub Market — Documentação da Feature

## Visão geral

Board global de anúncios para jogadores do PXG anunciarem pokémons à venda. Qualquer jogador de qualquer servidor pode ver os anúncios e contatar o vendedor. Pagamento e transferência são combinados diretamente entre as partes — o PokeHub só fornece visibilidade e estrutura que o Discord/Facebook não têm.

**Diferencial:** dados estruturados + filtros + histórico de anúncios por usuário.

---

## Regras de negócio

1. **Expiração automática:** anúncios expiram após 7 dias (configurável em `config/market.php`). Após expirar, `status = 'expired'` e some da listagem pública.
2. **Sem renovação automática:** vendedor precisa criar novo anúncio após expirar.
3. **Marcar como vendido:** só o dono do anúncio pode marcar.
4. **Screenshot opcional:** imagem do look do pokémon no jogo para dar credibilidade.
5. **Contato obrigatório:** nick in-game + servidor. Discord opcional.
6. **Sem verificação de posse:** sistema de honra. Screenshot é o único mecanismo de credibilidade.
7. **Dados independentes da house:** vendedor entra os dados manualmente. O pokémon não precisa estar cadastrado em nenhuma house.
8. **Moeda:** dinheiro do jogo (compartilhado entre todos os mundos PXG).
9. **Visualização pública:** browse sem login. Criar anúncio exige auth.
10. **Limite de anúncios ativos:** máximo 5 anúncios ativos por usuário (evita spam).

---

## Banco de dados

### Tabela `market_listings`

| Coluna | Tipo | Notas |
|---|---|---|
| `id` | bigint PK | |
| `user_id` | FK → users | vendedor |
| `species` | string(100) | espécie do pokémon |
| `is_shiny` | boolean, default false | |
| `tm` | string(100), nullable | |
| `held_x_name` | string(50), nullable | |
| `held_x_tier` | tinyint unsigned, nullable | |
| `held_y_name` | string(50), nullable | |
| `held_y_tier` | tinyint unsigned, nullable | |
| `price` | bigint unsigned | dinheiro in-game |
| `server` | string(50) | nome do mundo PXG (enum de lista predefinida) |
| `contact_nick` | string(100) | nick in-game do vendedor |
| `contact_discord` | string(100), nullable | |
| `notes` | text, nullable | |
| `screenshot_path` | string, nullable | `market-screenshots/{hash}.{ext}` |
| `status` | enum: active/sold/expired | default: active |
| `expires_at` | timestamp | `created_at + 7 dias` |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

**Índices:** `user_id`, `status`, `species`, `server`, `expires_at` (para o comando de expiração).

---

## Rotas

```
GET  /market                  → Market\Index      (público)
GET  /market/create           → Market\Create     (auth)
GET  /market/{listing}        → Market\Show       (público)
GET  /market/my-listings      → Market\MyListings (auth)
```

Ação de "marcar como vendido" e "excluir" é via método Livewire diretamente (sem rota HTTP separada).

---

## Componentes Livewire

### `Market\Index`
- Grid de cards com paginação
- Filtros: espécie (text search), servidor (select), faixa de preço (min/max), shiny (toggle)
- Exibe apenas `status = active` e `expires_at > now()`
- Ordenação padrão: mais recentes primeiro

### `Market\Create`
- Formulário espelhando os campos de `PokemonForm` (species, level, is_shiny, tm, held_x, held_y)
- Campos adicionais: price, server, contact_nick, contact_discord, notes
- Upload de screenshot: imagem, max 2MB, aceita jpg/png/webp
- Validação: limite de 5 anúncios ativos por usuário

### `Market\Show`
- Dados completos do anúncio
- Screenshot expandível (se houver)
- Contato do vendedor (nick + servidor + Discord)
- Sprite via pokemondb CDN (mesma lógica do `Pokemon::spriteUrl()`)
- Botão "Marcar como vendido" (visível só pro dono)

### `Market\MyListings`
- Três abas: Ativos / Vendidos / Expirados
- Ações por anúncio: marcar como vendido, excluir
- Exibe contador de ativos vs. limite (5)

---

## Tarefa agendada

Comando Artisan `market:expire` — roda diariamente via scheduler do Laravel.

```php
// bootstrap/app.php (Laravel 11+)
Schedule::command('market:expire')->daily();
```

Lógica: `UPDATE market_listings SET status = 'expired' WHERE expires_at < NOW() AND status = 'active'`

---

## Storage de screenshots

- Driver configurável via `filesystems.php` (local em dev, s3 em prod — já princípio do projeto)
- Path: `market-screenshots/{uuid}.{ext}` (sem subpasta por listing para simplificar)
- URL pública via `Storage::url()`
- Ao excluir ou marcar como vendido: screenshot **não é deletado** imediatamente (preserva histórico)

---

## Model `MarketListing`

Scopes necessários:
- `scopeActive` — `status = active AND expires_at > now()`
- `scopeByUser` — `user_id = ?`

Constantes:
- `EXPIRY_DAYS = 7`
- `MAX_ACTIVE_PER_USER = 5`

Reutiliza `Pokemon::HELD_X` e `Pokemon::HELD_Y` para os selects de held items.

---

## Enum `PxgServer`

```php
enum PxgServer: string
{
    case Flame    = 'Flame';
    case Soul     = 'Soul';
    case Steel    = 'Steel';
    case Obsidian = 'Obsidian';
    case Rainbow  = 'Rainbow';
    case Gold     = 'Gold';
    case Aurora   = 'Aurora';
    case Omega    = 'Omega';
    case Emerald  = 'Emerald';
    case Cosmic   = 'Cosmic';
    case Wind     = 'Wind';
    case Lunar    = 'Lunar';
    case Ocean    = 'Ocean';
}
```

---

## UI / Design

- Mesma paleta zinc/violet das houses
- Card do anúncio: sprite do pokémon, badge shiny, preço em destaque, tag do servidor, dias restantes
- Badge de status colorido: verde (ativo), cinza (expirado), roxo (vendido)
- Screenshot como thumbnail clicável no detalhe

---

## Fora do escopo (por enquanto)

- Sistema de reputação/avaliação de vendedor
- Chat/mensagens internas
- Histórico de preços por espécie
- Watchlist / alertas de novos anúncios
- Moderação/denúncia de anúncios
- Renovação de anúncio (botão "renovar" que reseta o `expires_at`)

---

## Decisões confirmadas

| Decisão | Valor |
|---|---|
| Browse sem login | sim (público) |
| Expiração | 7 dias |
| Limite de anúncios ativos por usuário | 5 |
| Servidor | lista predefinida (enum PHP + config) |

## Decisões em aberto

Nenhuma. Feature pronta para implementação.
