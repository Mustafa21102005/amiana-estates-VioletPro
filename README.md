# Amiana Estates

A portfolio website and content-management dashboard for **Amiana Estates**, a modern luxury residential development in Aberdeen, Sierra Leone.

Visitors browse the residences (an ultra-luxury duplex penthouse, premium 3-bedroom apartments and modern 2-bedroom apartments), the estate's amenities and lifestyle, and can send an enquiry. The administrator manages every part of the website from a dashboard, without touching the source code.

Built as an internship task for **VioletPro**.

## Features

### Public website

- **Home page:** hero (photo or looping video), featured residence showcase with photo mosaic and highlights, residence cards, story sections (estate experience, design philosophy, lifestyle, location advantages, the Amiana difference), amenities with icons.
- **Residence pages** (`/residences/{slug}`): hero, key facts, description, features grouped by category, photo gallery with lightbox (six photos with a "See all" toggle), a custom video player, and links to the other residences.
- **Contact form:** phone (required, with country code), email (optional) and message. Protected by a honeypot field and a rate limit.
- **Light and dark mode** with a switcher. The choice is remembered.
- Fully responsive, with page titles, meta descriptions and Open Graph tags on every page.
- Reading-progress line, branded scrollbar and a keyboard-accessible interface.

### Admin dashboard (`/admin`)

- **Dashboard:** counters, unread messages, and a "Needs attention" checklist that reads the real data (missing photos, incomplete contact details, no featured residence, unread messages older than 15 days).
- **Property types:** create, edit, reorder and delete. One can be featured. At least one must always remain.
- **Features:** added per residence in bulk (one per line), grouped by category, edited inline.
- **Feature categories** and **amenities:** managed in modals. Amenities have a searchable icon picker.
- **Content sections:** the home page's text blocks. Edit only, with a single photo each (the hero also accepts a video).
- **Photos and videos:** uploads are resized automatically, with cover selection, ordering, alt text and captions.
- **Contact and settings:** phone, WhatsApp, email, address, map link, social links, tagline.
- **Messages inbox:** unread badges, call / WhatsApp / email buttons, mark as unread, delete.
- Collapsible sidebar (remembered), confirmation before every delete, paginated tables, light and dark mode.

## Tech stack

- **Backend:** Laravel, PHP, MySQL
- **Authentication:** Laravel Breeze (Blade), with registration and email verification removed. There is a single administrator.
- **Frontend:** Blade, Tailwind CSS 3, Alpine.js, Vite
- **Images:** Intervention Image v3 (GD driver), saved as WebP
- **Fonts:** Cormorant Garamond and Montserrat, self-hosted through `@fontsource`

## Requirements

- PHP (the version required in `composer.json`) with the **GD extension with WebP support**
- Composer
- Node.js and pnpm
- MySQL

Check WebP support with:

```bash
php -r "var_dump(function_exists('imagewebp'));"
```

It must print `bool(true)`.

## Getting started

```bash
git clone https://github.com/Mustafa21102005/amiana-estates-VioletPro.git
cd amiana-estates

composer install
pnpm install

cp .env.example .env
php artisan key:generate
```

Open `.env` and set the database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=amiana_estates
DB_USERNAME=root
DB_PASSWORD=
```

Then:

```bash
php artisan migrate --seed
php artisan storage:link
pnpm run dev
php artisan serve
```

- Website: <http://localhost:8000>
- Admin login: <http://localhost:8000/login>

## Configuration

**Upload limits** live in `config/media.php`:

| Setting    | Default               |
| ---------- | --------------------- |
| `image_kb` | 1024 (1 MB per photo) |
| `video_kb` | 2048 (2 MB per video) |

PHP's own limits must be larger than these (`php.ini`):

```ini
upload_max_filesize = 20M
post_max_size = 40M
memory_limit = 512M
max_file_uploads = 30
```

`memory_limit` matters for photo resizing. A request larger than `post_max_size` is discarded by PHP, which shows up as a **419 Page Expired** error.

**Business rules** are constants in the code, so they are easy to change:

- `PropertyType::MINIMUM`: how many property types must always remain (default 1)
- `Message::OVERDUE_DAYS`: age at which an unread message raises a dashboard warning (default 15)

## Business rules

- At least one property type always remains, and only one can be featured at a time.
- Display orders are always gap-free (1, 2, 3...). Choosing a taken number pushes the others down.
- A category that still has features cannot be deleted.
- Content section text is required, and the hero is the only section that accepts a video.
- Deleting a record deletes its photos and videos from disk as well.
- The contact form requires a phone number that starts with `+` and the country code.

## Project structure

| Path                             | Contents                                                                          |
| -------------------------------- | --------------------------------------------------------------------------------- |
| `app/Models/Concerns/`           | `GeneratesSlug`, `HasOrderedPosition`, `HasMedia`                                 |
| `app/Services/MediaUploader.php` | Resizes photos to WebP and stores files                                           |
| `app/Support/AmenityIcons.php`   | The registry of amenity icons                                                     |
| `app/View/Components/`           | `SiteLayout`, `MediaGallery`, `IconSelect`                                        |
| `app/Http/Controllers/`          | `SiteController`, `ContactController`, and `Admin/*`                              |
| `app/Http/Requests/`             | Validation, with separate store and update requests                               |
| `resources/views/`               | `layouts/`, `site/` (public), `admin/`, `components/`, `partials/`, `pagination/` |
| `resources/js/`                  | `app.js`, `video-player.js`, `upload-form.js`, `icon-select.js`                   |
| `database/seeders/`              | Administrator, content, media and demo data                                       |

## Troubleshooting

| Problem                                          | Fix                                                                                                |
| ------------------------------------------------ | -------------------------------------------------------------------------------------------------- |
| Photos are broken images                         | `php artisan storage:link`                                                                         |
| 419 Page Expired when uploading                  | The request is larger than PHP's `post_max_size`                                                   |
| Styles or scripts missing                        | Run `pnpm run dev` or `pnpm run build`. A stale `public/build` folder can override the dev server. |
| "Unable to locate a class or view for component" | `php artisan view:clear`                                                                           |
| The theme is not remembered                      | Preferences are saved per address. Use one address consistently.                                   |

## License

This project is open-sourced under the [MIT License](LICENSE).

## Author

**Mustafa Azmi Khalil** — © 2026
