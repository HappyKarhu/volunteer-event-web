# Volunteer Event Platform (Laravel 13)

A Laravel-based web platform that connects event organizers with volunteers.

## 🚀 Features

### Public
- View all events
- View event details
- Register / login

### Organizer
- Create and manage events
- Edit and delete own events
- View participants (volunteers)

### Volunteer
- Join events
- Track participation

## 🗄️ Database Structure

- users
- events
- event_sections
- event_attendees
- section_volunteers
- preloaded_photos
- contact_messages
- notification_logs

## ⚙️ Tech Stack

- Laravel 13
- Blade (UI)
- MySQL
- TailwindCSS

## 🔐 Roles

- Organizer → manages events
- Volunteer → joins events

## 🔮 Planned Features

- Waitlist system
- Admin dashboard
- Multi-language support
- Analytics dashboard
- Editable pages (About, Terms, etc.)

## 📦 Installation

```bash
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve