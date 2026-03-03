# TODO

## Fix "There are no commands defined in the 'boost' namespace"

- [x] Fix invalid JSON in `composer.json` (remove embedded `php artisan boost:install` text)
- [x] Create `.env` from `.env.example` and set `APP_ENV=local` (root cause: missing `.env` caused production mode, blocking boost commands)
- [x] Run `php artisan key:generate`
- [x] Run `php artisan boost:install` to register boost commands ✅ (interactive prompt now running)

## Create `frontend` and `backend-api` Skills

- [x] Create `.ai/skills/frontend/SKILL.md`
- [x] Create `.ai/skills/backend-api/SKILL.md`
- [x] Run `php artisan boost:update` to propagate skills to agent directories
