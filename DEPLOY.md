# Deploy — Hetzner CX23

## 1. Criar o servidor

1. Crie um servidor **CX23** na Hetzner (Ubuntu 24.04)
2. Adicione sua chave SSH no momento da criação
3. Anote o IP público

## 2. Apontar domínio

No seu DNS, crie um registro A:
```
@ → IP_DO_SERVIDOR
```
Aguarde propagar (pode levar até 30min).

## 3. Configurar o servidor

```bash
ssh root@IP_DO_SERVIDOR

# Atualizar sistema
apt update && apt upgrade -y

# Instalar Docker
curl -fsSL https://get.docker.com | sh

# Instalar Certbot
apt install -y certbot

# Criar usuário (opcional, mais seguro)
useradd -m -s /bin/bash pokehub
usermod -aG docker pokehub
```

## 4. Subir o código

```bash
# No servidor
git clone https://github.com/SEU_USUARIO/pokehub.git /srv/pokehub
cd /srv/pokehub
```

## 5. Configurar variáveis de ambiente

```bash
cp .env.prod.example laravel/.env

# Editar com seus valores reais
nano laravel/.env
```

Valores críticos a preencher:
- `APP_KEY` → rodar `php artisan key:generate --show` localmente
- `DB_PASSWORD` e `DB_ROOT_PASSWORD` → senhas fortes
- `REVERB_APP_KEY` e `REVERB_APP_SECRET` → `openssl rand -hex 16` e `openssl rand -hex 32`
- `APP_URL`, `VITE_REVERB_HOST` → seu domínio

## 6. Configurar nginx para o domínio

```bash
# Substituir SEU_DOMINIO no arquivo de config
sed -i 's/SEU_DOMINIO/meudominio.com/g' docker/nginx/prod.conf
```

## 7. Build dos assets

```bash
cd laravel
npm install && npm run build
cd ..
```

## 8. Subir containers (HTTP primeiro, para pegar o certificado)

Suba só nginx + certbot temporariamente com HTTP:

```bash
docker compose -f docker-compose.prod.yml up -d nginx
```

## 9. Gerar certificado SSL

```bash
certbot certonly --webroot \
  -w /var/lib/docker/volumes/pokehub_certbot-www/_data \
  -d meudominio.com \
  --email seu@email.com \
  --agree-tos \
  --non-interactive
```

## 10. Subir tudo

```bash
docker compose -f docker-compose.prod.yml up -d
```

## 11. Rodar migrations e otimizar

```bash
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker compose -f docker-compose.prod.yml exec app php artisan config:cache
docker compose -f docker-compose.prod.yml exec app php artisan route:cache
docker compose -f docker-compose.prod.yml exec app php artisan view:cache
docker compose -f docker-compose.prod.yml exec app php artisan storage:link
```

## 12. Renovação automática do certificado

```bash
crontab -e
# Adicionar:
0 3 * * * certbot renew --quiet && docker compose -f /srv/pokehub/docker-compose.prod.yml restart nginx
```

---

## Comandos úteis pós-deploy

```bash
# Ver logs
docker compose -f docker-compose.prod.yml logs -f

# Reiniciar um serviço
docker compose -f docker-compose.prod.yml restart app

# Nova versão do código
git pull
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker compose -f docker-compose.prod.yml exec app php artisan config:cache
docker compose -f docker-compose.prod.yml restart app queue reverb
```
