# Flight Management System

A university project made with Laravel, Blade, plain CSS, MySQL and the OpenWeather API.

## Main Features

- Passenger registration and login using Laravel Breeze
- Admin and passenger roles
- Airline, airport and flight management
- Flight search by route and date
- Safe booking and cancellation with seat tracking
- Current weather for departure and arrival airports
- Admin dashboard and booking report

## Installation

1. Create a MySQL database named `flight_management`.
2. Copy `.env.example` to `.env` and update the database settings.
3. Put an OpenWeather API key in `OPENWEATHER_API_KEY` (optional).
4. Run:

```bash
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

Open `http://127.0.0.1:8000`.

## Demo Accounts

- Admin: `admin@fms.com` / `password`
- Passenger: `student@fms.com` / `password`

Change these passwords before using the project outside a classroom demo.

## Weather

Weather responses are cached for 30 minutes. The site continues to work without an API key and displays “Weather unavailable”.

## Booking Rules

- A booking can include 1 to 10 seats.
- Booking uses a database transaction so two users cannot take the same last seat.
- Passengers can cancel only their own future booking.
- A flight with booking history cannot be deleted; the admin should cancel it.
- Cancelling a flight also cancels its confirmed bookings.
