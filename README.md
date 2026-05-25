# TutorConnect: Tutor Booking & Feedback Management Platform

TutorConnect is a full-stack Laravel 11 web application that connects students and tutors in real-time. It streamlines profile matchings, calendar scheduling, booking, virtual lessons coordination, progress monitoring, and receipt invoicing.

---

## Key Features & Highlights

### For Students
- **Smart Matchmaking**: Advanced search query matching tutors by subject, hourly rates, ratings, and vetting status.
- **Appointment Coordinator**: Book available time slots, complete mock payment forms, and review active sessions.
- **Classroom Suite**: Direct joins to virtual Google Meet classrooms.
- **Reports & Academic Seals**: Download invoices and landscapic session certificates as PDFs.
- **Live Chat**: Connect with tutors via an AJAX-polled messenger room.
- **Academic Progress Tracker**: Monitor performance scores and review feedback logs recorded by tutors.

### For Tutors
- **Availability Planner**: Add date/time slots to the student booking calendar.
- **Booking Moderator**: Accept (which dynamically generates meet links) or reject booking requests.
- **Study Materials Uploader**: Share class reference documents and PDFs with students.
- **Milestone logger**: Track student performance by logging scores and written reviews.

### For Administrators
- **Vetting Moderator**: Verify tutor profile credentials to grant verified badges.
- **Account Controller**: Suspend or unsuspend user accounts.
- **Academic Subject Manager**: Subject CRUD interface to create, update, or delete teaching categories.
- **Data Auditor**: Export CSV sheets for user registries and transaction logs.

---

## Technology Stack

- **Backend**: Laravel 11.x (PHP 8.2+)
- **Frontend**: Blade Templates + Bootstrap 5.3 + Vanilla JavaScript
- **Database**: MySQL 8.x
- **PDF Compiler**: DomPDF (`barryvdh/laravel-dompdf`)
- **Real-Time Engine**: Simulated AJAX Polling (no external socket keys required)

---

## Installation & Setup Instructions

To run the application locally on your machine, follow these steps:

### 1. Database Setup
Ensure MySQL is running. Create an empty database named `tutorconnect`:
```sql
CREATE DATABASE tutorconnect;
```

### 2. Configure Environment Parameters
In the project root, open the `.env` file (or duplicate `.env.example` as `.env`) and update the database details to match:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tutorconnect
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Install Dependencies
Run composer to install package modules:
```bash
composer install
```

### 4. Build Application Keys
```bash
php artisan key:generate
```

### 5. Setup Database & Seed Data
Run the migrations and seed the database with mock student, tutor, and admin profiles:
```bash
php artisan migrate:fresh --seed
```

### 6. Link Storage Folders
Run storage linking to enable profile photo and document uploads:
```bash
php artisan storage:link
```

### 7. Run Local Development Server
```bash
php artisan serve
```
Open your browser and navigate to `http://127.0.0.1:8000`.

---

## Pre-seeded Evaluation Accounts

Use these credentials to log in and test different user roles. **Note**: When logging in, the simulated OTP code will display directly on the verification screen for easy entry.

### 1. Platform Administrator
- **Email**: `admin@tutorconnect.com`
- **Password**: `password`

### 2. Tutor / Instructor (Alice Smith)
- **Email**: `alice.smith@tutorconnect.com`
- **Password**: `password`

### 3. Student (Charlie Brown)
- **Email**: `charlie.brown@tutorconnect.com`
- **Password**: `password`
