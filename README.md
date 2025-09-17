<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).



## Разработка с Docker и Composer без расширений на хосте
В проект добавлен скрипт для запуска Composer внутри Docker-образа с полнотой PHP-расширений (включая ext-xml и ext-dom). Это помогает избежать ошибок наподобие:
- laravel/pint ... requires ext-xml
- phpunit/phpunit ... requires ext-dom

Использование:
```bash
chmod +x scripts/composer-docker.sh
scripts/composer-docker.sh install
# или установить Sail
scripts/composer-docker.sh require laravel/sail --dev
```

После установки Sail можно пользоваться его оберткой для Node/NPM:
```bash
./vendor/bin/sail up -d
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

Альтернатива: установить недостающие расширения в Ubuntu
```bash
sudo apt update
# Для PHP 8.3, как в сообщении об ошибке:
sudo apt install -y php8.3-xml
# (или универсально)
sudo apt install -y php-xml
```

Временное игнорирование требований (не рекомендуется на постоянной основе):
```bash
composer install --ignore-platform-req=ext-xml --ignore-platform-req=ext-dom
```


## Установка и активация PHP-расширений (Ubuntu)
Если вы запускаете Composer локально и видите ошибки вида «requires ext-xml / ext-dom», установите и активируйте расширения PHP на Ubuntu.

Быстрый способ через скрипт в этом репозитории:
```bash
chmod +x scripts/install-php-extensions-ubuntu.sh
sudo scripts/install-php-extensions-ubuntu.sh
# Проверка, что dom и xml включены:
php -m | grep -E "^xml$|^dom$"
```
Скрипт автоматически определит версию PHP (8.3/8.2) и поставит подходящие пакеты: php-xml (включая dom), а также часто используемые для Laravel: mbstring, zip, curl, gd, intl, bcmath.

Альтернатива — не ставить ничего на хост и использовать Composer в Docker (расширения уже есть):
```bash
chmod +x scripts/composer-docker.sh
scripts/composer-docker.sh install
# или
scripts/composer-docker.sh require laravel/sail --dev
```
После этого вы можете работать через Sail:
```bash
./vendor/bin/sail up -d
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```
