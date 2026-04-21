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
- See their created events (dashboard)
- View participants (volunteers) and can review->accept/reject

### Volunteer
- Join events
- Track participation
- See own events (dashboard)

## 🗄️ Database Structure

- users
- events
- event_sections
- event_attendees
- section_volunteers
- preloaded_photos
- contact_messages
- notification_logs
- event_applications
- event_application_status_histories
- messages

## ⚙️ Tech Stack

- Laravel 13
- Blade (UI)
- MySQL
- TailwindCSS

## 🔐 Roles

- Organizer → manages events
- Volunteer → joins events

## 📝 Applying to the event

The system supports two event types:

### 🟢 Simple event
- Volunteer submits an application (EventApplication)
- Organizer reviews application (approve/reject)
- If approved → volunteer becomes EventAttendee

### 🟣 Sectioned event
- Volunteer submits an application (EventApplication)
- Volunteer selects a section when applying
- Organizer reviews application and assigns/validates section
- If approved → volunteer becomes SectionVolunteer

## 🔮 Planned Features

- Waitlist system
- Admin dashboard
- Multi-language support
- Analytics dashboard
- Editable pages (About, Terms, etc.)
- Ratings/reputation system

## 📦 Installation

```bash
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve