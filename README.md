## О проекте

Небольшой сервис для приёма и обработки заказов по звонкам. Реализованы две роли:
оператор (создание заказов) и руководитель (просмотр списка, фильтры, поиск и статистика).
Фронтенд на Vue + Bootstrap, бэкенд на Laravel 11 с разделением по DDD-доменам и CQRS.

## Стек технологий

- **Язык/Framework**: PHP 8.3, Laravel 11
- **База данных**: MySQL 8 (Docker, через Sail)
- **Аутентификация/Авторизация**: Laravel Passport, Spatie Laravel Permission
- **Архитектура**: DDD (Service Providers), CQRS (отдельные шины команд/запросов)
- **Фронтенд**: Vue.js (vite), TypeScript (vue-tsc), Bootstrap, Sass (SCSS)
- **Инфраструктура**: Docker + Laravel Sail, Vite (Node 20)

## Развёртывание на Ubuntu с нуля (dev/test)

Ниже — пошаговая инструкция начиная с установки Composer. Предпочтительный способ запуска — через Laravel Sail (Docker), локальные PHP-расширения не требуются.

### 1) Установить зависимости системы

```bash
sudo apt update
sudo apt install -y curl ca-certificates git
# Docker + Docker Compose plugin (если не установлены)
sudo apt install -y docker.io docker-compose-plugin
sudo systemctl enable --now docker
sudo usermod -aG docker "$USER"  # после этого перелогиньтесь
```

### 2) Установить Composer (вариант A — через apt)

```bash
sudo apt install -y composer
composer --version
```

Если предпочитаете не ставить Composer/PHP на хост, используйте вариант B — Docker-композер из репозитория.

### 2B) Альтернатива: Composer внутри Docker

```bash
chmod +x scripts/composer-docker.sh
scripts/composer-docker.sh --version
```

### 3) Клонировать проект

```bash
git clone https://example.com/your/lk-manager.git
cd lk-manager
```

### 4) Настроить окружение

```bash
cp .env.example .env
# Отредактируйте .env при необходимости, ключевые переменные:
# APP_NAME, APP_URL=http://localhost
# DB_CONNECTION=mysql
# DB_HOST=mysql
# DB_PORT=3306
# DB_DATABASE=lk_manager
# DB_USERNAME=lk_manager
# DB_PASSWORD=lk_manager
```

### 5) Установить зависимости Composer

Один из вариантов:

```bash
# Вариант A: локальный Composer
composer install

# Вариант B: Composer в Docker
scripts/composer-docker.sh install
```

### 6) Поднять инфраструктуру (Sail)

```bash
./vendor/bin/sail up -d
```

### 7) Миграции и сиды

```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

Seeder создаст роли, тестовых пользователей и инициализирует Passport клиента.

- Head: email `head@example.com`, пароль `password`
- Manager: email `manager@example.com`, пароль `password`

### 8) Фронтенд (Vite)

Два способа:

```bash
# A) Запуск Vite внутри контейнера
./vendor/bin/sail npm ci
./vendor/bin/sail npm run dev

# B) Отдельный сервис Vite из docker-compose
./vendor/bin/sail up -d vite
```

### 9) Доступ к приложению

- Приложение: `http://localhost`
- Vite dev-server: `http://localhost:5173` (если запускаете вариант B)

## Полезные команды (Sail)

```bash
# Статус/логи
./vendor/bin/sail ps
./vendor/bin/sail logs -f

# Остановка/перезапуск
./vendor/bin/sail stop
./vendor/bin/sail up -d

# Миграции
./vendor/bin/sail artisan migrate:fresh --seed

# NPM
./vendor/bin/sail npm ci
./vendor/bin/sail npm run build
```

## Примечания

- Все команды для разработки и проверки рекомендуется выполнять через Sail.
- Перед проверками собирайте проект заново, но без флага `--no-cache`.
- Изменения схемы БД должны согласовываться отдельно.
