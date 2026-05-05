# Evento — Modern Event Management Platform

**Evento** is a clean, minimal, and premium event management platform built with Laravel and Tailwind CSS. It empowers organizers to manage attendee registrations, track attendance, and provide users with a seamless event exploration experience.

![Evento Screenshot](https://via.placeholder.com/1200x600/0f766e/ffffff?text=Evento+Platform)

## ✨ Features

### For Users
- **Explore Events**: Dynamic event grid with advanced search and sorting (Soonest/Latest First).
- **My Tickets**: Organized tabbed view for "Upcoming" and "Past" registrations.
- **Save to Calendar**: One-click "Add to Google Calendar" or `.iCal` download.
- **Link Sharing**: Instant copy-to-clipboard for public event pages.
- **Cancellation**: Easy registration cancellation before event starts with automatic seat restoration.

### For Organizers
- **Rich Dashboard**: At-a-glance analytics (Total Events, Registrations, Active Events).
- **Registration Progress**: Visual progress bars showing event capacity status.
- **Attendee Management**: Full list of attendees with real-time "Mark Present/Absent" toggling.
- **CSV Export**: Professional attendee list exports (Excel compatible).
- **Branded Emails**: Professional, teal-themed transactional emails (Welcome, Registration Confirmed, Password Reset).

## 🛠️ Tech Stack
- **Framework**: [Laravel 11](https://laravel.com)
- **Styling**: [Tailwind CSS](https://tailwindcss.com)
- **Interactivity**: [Alpine.js](https://alpinejs.dev)
- **Database**: [PostgreSQL](https://www.postgresql.org)
- **Icons**: [Heroicons](https://heroicons.com)

## 🚀 Getting Started

### 1. Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- PostgreSQL

### 2. Installation
```bash
# Clone the repository
git clone https://github.com/yourusername/evento.git

# Navigate to the app directory
cd app

# Install dependencies
composer install
npm install
```

### 3. Configuration
Copy the `.env.example` to `.env` and configure your database and mail settings:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup & Admin Creation
Ensure your PostgreSQL server is running and the database exists. Then, set your admin credentials in `.env`:
```env
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=your-secret-password
```

Run the migrations and seeders:
```bash
php artisan migrate --seed
```

### 5. Start the Application
```bash
npm run dev
# In a separate terminal
php artisan serve
```

## 🔒 Security
- **Secure Seeding**: Admin credentials are never hardcoded and are managed via environment variables.
- **Authorization**: Strict role-based access control (Admin, Organizer, User).
- **Data Protection**: Sensitive information like `.env` is excluded from version control.

---

Built with ❤️ by the Evento Team.
