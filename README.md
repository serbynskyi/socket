# Socket Chat

Система обміну повідомленнями в реальному часі на базі Laravel 11, Laravel Reverb, Redis та MySQL.

## Стек

- Laravel 11 + PHP 8.3
- Laravel Reverb (WebSocket)
- Redis (Broadcasting + Queue)
- MySQL 8
- Livewire (frontend)
- Docker

## Запуск проекту

### 1. Клонувати репозиторій
```bash
git clone https://github.com/serbynskyi/socket.git
cd socket
```

### 2. Скопіювати .env файл
```bash
cp .env.example .env
```

### 3. Запустити Docker
```bash
docker compose up -d --build
```

### 4. Виконати міграції
```bash
docker compose exec laravel php artisan migrate
```

### 5. Готово!

Проект доступний за адресою: [http://localhost](http://localhost)
