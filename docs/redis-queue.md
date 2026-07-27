# Redis Queue Setup

This project is configured to use Redis for cache and queued jobs:

```env
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Do not enable these values until Redis is installed and running. If Redis is not running, Laravel will show:

```text
No connection could be made because the target machine actively refused it [tcp://127.0.0.1:6379]
```

Use these safe values while Redis is not running:

```env
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

Start Redis first, then clear Laravel config cache:

```bash
php artisan optimize:clear
```

Run one queue worker:

```bash
php artisan queue:work redis --queue=default --sleep=3 --tries=3 --timeout=120
```

On Windows/XAMPP, you can double-click `start-queue-worker.bat` or run it from Command Prompt. Keep the worker window open while the site is running.

For production, run the same worker command under Supervisor, systemd, or your hosting panel's process manager so it restarts automatically.
