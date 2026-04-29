# Umrah KOL API

A Laravel-based API for managing Umrah pilgrimage referral programs through Key Opinion Leaders (KOLs). This system tracks referrals, manages pilgrims (jamaahs), and monitors departure schedules.

## Features

- **KOL Management**: Create and manage Key Opinion Leaders with unique referral codes
- **Referral Tracking**: Track clicks and conversions from referral links
- **Jamaah (Pilgrim) Management**: Manage pilgrim registrations and payments
- **City Management**: Manage cities for KOL locations
- **Departure Schedules**: Track and manage Umrah departure schedules
- **Analytics**: Track booking counts, registrations, and referral performance

## Requirements

- PHP >= 8.3
- Composer
- MySQL
- Node.js & NPM

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd umrah-kol-api
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Configure environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Update your `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=umrah_kol_api
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Run migrations:
```bash
php artisan migrate
```

6. Build assets:
```bash
npm run build
```

## Development

Start the development server:
```bash
composer dev
```

This will start:
- Laravel development server (http://localhost:8000)
- Queue worker
- Log watcher
- Vite dev server

## API Endpoints

### Cities
- `GET /api/cities` - List all cities

### KOLs (Key Opinion Leaders)
- `GET /api/kols` - List KOLs with filtering and pagination
  - Query params: `search`, `city_id`, `min_jamaahs`, `min_clicks`, `sort_by`, `sort_order`, `per_page`, `page`
- `GET /api/kols/referral/{referralCode}` - Get KOL by referral code
- `POST /api/kols` - Create a new KOL

### Referrals
- `GET /api/referrals` - List all referrals
- `POST /api/referrals/click/{referralCode}` - Track referral link click
- `POST /api/referrals/register` - Register a new referral

### Jamaahs (Pilgrims)
- `GET /api/jamaahs` - List all pilgrims
- `POST /api/jamaahs` - Register a new pilgrim
- `PATCH /api/jamaah/{id}/paid` - Update pilgrim status to paid

### Departure Schedules
- `GET /api/departure-schedule` - List all departure schedules

## Testing

Run tests using Pest:
```bash
composer test
```

## Database Schema

### kols
- id
- name
- email
- number_whatsapp
- tiktok_instagram_account
- city_id (foreign key)
- referral_code (unique)
- total_click
- total_register
- timestamps

### cities
- id
- name
- timestamps

### jamaahs
- id
- kol_id (foreign key)
- departure_schedule_id (foreign key)
- name
- number_whatsapp
- status (booking/paid)
- having_passport (boolean)
- timestamps

### referrals
- id
- kol_id (foreign key)
- status (clicked)
- is_unique (boolean)
- ip_address
- user_agent
- clicked_at
- timestamps

### departure_schedules
- id
- departure_date
- available_slots
- timestamps

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
