# Redis Queue Setup

This project is configured to use Redis for cache, sessions, queued jobs,
and backend event broadcasting:

```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
SESSION_CONNECTION=sessions
QUEUE_CONNECTION=redis
BROADCAST_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
REDIS_CACHE_DB=1
REDIS_SESSION_DB=2
REDIS_QUEUE_DB=3
```

Each workload uses a separate Redis database to prevent cache flushes from
removing sessions or queued jobs.

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

On macOS with Homebrew:

```bash
brew services start redis
redis-cli ping
```

The health check should return `PONG`.

Run one queue worker:

```bash
php artisan queue:work redis --queue=default --sleep=3 --tries=3 --timeout=120
```

Redis broadcasting provides the server-side Pub/Sub transport. Browser
WebSocket delivery still requires a compatible WebSocket server and client
such as Laravel Reverb or Pusher.

On Windows/XAMPP, you can double-click `start-queue-worker.bat` or run it from Command Prompt. Keep the worker window open while the site is running.

On this macOS development machine, the included LaunchAgent keeps the worker
running and starts it again after login:

```bash
launchctl bootstrap gui/$(id -u) ops/com.kneayerng.queue-worker.plist
launchctl kickstart -k gui/$(id -u)/com.kneayerng.queue-worker
```

Worker output is written to `storage/logs/queue-worker.log` and errors to
`storage/logs/queue-worker-error.log`.

For production, run the same worker command under Supervisor, systemd, or your hosting panel's process manager so it restarts automatically.
