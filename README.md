# DevTZ portfolio

A single-page team portfolio built as a Laravel 13 application. The UI uses Blade, plain CSS, and a small amount of framework-free JavaScript.

## What is included

- Responsive portfolio page with work, services, team structure, process, and contact sections
- Laravel-rendered portfolio content in `PortfolioController`
- CSRF-protected inquiry form with validation and rate limiting
- Private local JSON storage for inquiries at `storage/app/private/portfolio-inquiries`
- Accessible mobile navigation, reduced-motion support, and keyboard-friendly interactions
- Feature tests for the page and inquiry flow

The three displayed projects are explicitly labeled as concept showcases. Replace them with verified DevTZ case studies when those details are available.

## Run locally

```bash
php artisan serve
```

Open `http://127.0.0.1:8000`.

## Verify

```bash
php artisan test
vendor/bin/pint --test
php artisan view:cache
```

## Main files

- `app/Http/Controllers/PortfolioController.php` — portfolio content
- `app/Http/Controllers/InquiryController.php` — inquiry storage
- `app/Http/Requests/StoreInquiryRequest.php` — form validation
- `resources/views/portfolio.blade.php` — Blade page
- `public/css/portfolio.css` — visual design and responsive styles
- `public/js/portfolio.js` — menu, header, and reveal interactions

## Deployment note

This is a server-rendered PHP application and needs Laravel-compatible hosting. Point the web root at `public/`, set production environment values, and ensure `storage/` and `bootstrap/cache/` are writable. The previously created ChatGPT Sites record is retained in `.openai/hosting.json`, but it is not deployed because Sites runs Cloudflare Workers rather than PHP.
