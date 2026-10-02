# Electrogrid

A web-based electrical grid monitoring and feeder outage management system built with Laravel, MySQL, and Pusher. The project tracks substation and feeder line statuses, logs historical outage incidents, and pushes real-time telemetry updates to an operator dashboard without requiring page refreshes.

---

## Features

- Real-time feeder monitoring: Uses WebSockets via Pusher to broadcast line status changes, voltage drops, and outage alerts instantly to connected dashboard clients.
- Incident logging & resolution: Keeps track of outage reports, assigned line technicians, downtime duration, and resolution timestamps.
- Role-based dashboard: Separates views and administrative privileges between grid engineers and field technicians.
- Telemetry ingestion API: Internal REST endpoints to receive telemetry or sensor simulation data and trigger broadcast events.

---

## Tech Stack

- Backend: PHP 8.x, Laravel
- Database: MySQL
- Real-time service: Pusher Channels
- Frontend: Blade templates, JavaScript, Bootstrap / Tailwind
- Tooling: Composer, NPM, Git

---

## Requirements

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL >= 5.7
- Pusher account credentials (App ID, Key, Secret, Cluster)

---

## Installation & Setup

1. Clone the repository:
   git clone https://github.com/your-username/electrogrid.git
   cd electrogrid

2. Install PHP and JavaScript dependencies:
   composer install
   npm install

3. Configure environment:
   cp .env.example .env
   php artisan key:generate

4. Update database and broadcast credentials in .env:
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=electrogrid
   DB_USERNAME=root
   DB_PASSWORD=

   BROADCAST_DRIVER=pusher

   PUSHER_APP_ID=your_pusher_id
   PUSHER_APP_KEY=your_pusher_key
   PUSHER_APP_SECRET=your_pusher_secret
   PUSHER_APP_CLUSTER=your_pusher_cluster

5. Run database migrations:
   php artisan migrate

6. Build frontend assets:
   npm run build

7. Start the development server:
   php artisan serve

The application will be accessible at http://127.0.0.1:8000.

---

## Project Structure

app/
├── Events/              # Event classes handling Pusher broadcasts (e.g., FeederStatusChanged)
├── Http/
│   ├── Controllers/     # Feeder, Outage, and Dashboard logic
│   └── Middleware/      # Auth and role verification
└── Models/              # Eloquent models (Feeder, OutageReport, Substation)
database/
├── migrations/          # Schema migrations for feeders, logs, and users
resources/
├── js/                  # Pusher client initialization and DOM listeners
└── views/               # Blade templates for monitoring dashboards
routes/
├── web.php              # Authenticated user routes and dashboards
└── api.php              # Status update hooks and simulation payloads

---

## License

This project was built for practical engineering demonstrations. Licensed under the MIT License.
