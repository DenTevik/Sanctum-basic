```
прописать конфигурацию БД .env
composer install
php artisan migrate
php artisan db:seed
```
---
Получить токен по login & password:
```
POST /api/login (Accept: application/json)
```
---
Получить текущего пользователя по токену:
```
GET /api/user (Accept: application/json, Authorization: Bearer {token})
```
---
Получить текущего пользователя по логину и паролю:
```
GET /api/user (Accept: application/json, Authorization: Basic {login:password})
```
