# Judge Scoring System - LAMP Stack Web Application

A minimal web application built on the LAMP (Linux, Apache, MySQL, PHP) stack that allows judges to submit scores for participants with a real-time public scoreboard.

## Table of Contents
- [Overview](#overview)
- [Features](#features)
- [Project Structure](#project-structure)
- [Prerequisites](#prerequisites)
- [Installation & Setup](#installation--setup)
- [Database Schema](#database-schema)
- [Usage](#usage)
- [Design Choices](#design-choices)
- [Assumptions Made](#assumptions-made)
- [Future Enhancements](#future-enhancements)
- [Technical Stack](#technical-stack)

## Overview

This application provides three main interfaces:
1. **Admin Panel** - Manage judges and participants
2. **Judge Portal** - Submit scores for participants
3. **Public Scoreboard** - Real-time display of rankings

## Features

- **Admin Panel**: Add and manage judges and participants
- **Judge Portal**: Score submission interface for judges
- **Public Scoreboard**: Live rankings with auto-refresh
- **Responsive Design**: Works on desktop and mobile devices
- **Real-time Updates**: Scoreboard refreshes automatically
- **Data Validation**: Form validation and error handling
- **Clean UI**: Modern, intuitive interface design

## Project Structure

```
scoring_system/
├── admin/                  # Admin panel files
│   ├── index.php          # Admin dashboard
│   ├── manage_judges.php  # Judge management
│   ├── manage_users.php   # User management
│   └── update_judge.php   # Judge update handler
├── assets/                # Static assets
│   ├── css/              # Stylesheets
│   ├── images/           # Images
│   └── js/               # JavaScript files
├── config/               # Configuration files
│   └── database.php      # Database connection
├── includes/             # PHP includes
│   ├── functions.php     # Common functions
│   ├── header.php        # Common header
│   ├── footer.php        # Common footer
│   └── *.php            # Various utility files
├── judge/                # Judge portal
│   ├── index.php         # Judge dashboard
│   ├── select_user.php   # User selection
│   └── submit_score.php  # Score submission
├── public/               # Public facing pages
│   ├── index.php         # Homepage
│   └── scoreboard.php    # Public scoreboard
├── sql/                  # Database files
│   └── database_schema.sql
└── test/                 # Testing files
    └── test_connection.php
```

## Prerequisites

- **Web Server**: Apache 2.4+
- **Database**: MySQL 5.7+ or MariaDB 10.2+
- **PHP**: PHP 7.4+ (with PDO and mysqli extensions)
- **Composer**: For PHP dependency management (phpdotenv)
- **Development Environment**: XAMPP, WAMP, or Docker LAMP stack

## Installation & Setup

### 1. Clone/Download the Project
```bash
# If using Git
git clone [https://github.com/nokekip/scoring_system.git]
cd scoring_system

# Or extract the ZIP file to your web server directory
```

### 2. Web Server Setup
Place the project in your web server's document root:
- **XAMPP**: `/xampp/htdocs/scoring_system/`
- **WAMP**: `/wamp64/www/scoring_system/`
- **Linux Apache**: `/var/www/html/scoring_system/`

### 3. Install Dependencies
Navigate to the project directory and install PHP dependencies:
```bash
cd scoring_system
composer install
```

This will install **vlucas/phpdotenv** for environment variable management.

### 4. Database Setup

#### Create Database
```sql
CREATE DATABASE scoring_system;
USE scoring_system;
```

#### Import Schema
Execute the SQL file located at `sql/database_schema.sql`:
```bash
mysql -u your_username -p scoring_system < sql/database_schema.sql
```

Or import via phpMyAdmin:
1. Open phpMyAdmin
2. Select `scoring_system` database
3. Go to Import tab
4. Choose `sql/database_schema.sql`
5. Click Go

**Note**: The schema includes sample data for testing purposes.

### 5. Configure Database Connection
Create a `.env` file in the project root with your database credentials:
```env
DB_HOST=127.0.0.1
DB_NAME=scoring_system
DB_USER=your_username
DB_PASS=your_password
DB_CHARSET=utf8mb4
```

The application uses **PHP-DotEnv** for environment variable management, ensuring sensitive credentials are kept separate from code.

### 6. Test Installation
1. Start your web server and MySQL
2. Navigate to `http://localhost/scoring_system/test/test_connection.php`
3. Verify database connection is successful
4. Visit `http://localhost/scoring_system/` to access the application

## Database Schema

```sql
-- Users table (event participants)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Judges table
CREATE TABLE judges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Scores table (allows multiple scores per user from different judges)
CREATE TABLE scores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    judge_id INT NOT NULL,
    points INT NOT NULL CHECK (points >= 0 AND points <= 100),
    comments TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (judge_id) REFERENCES judges(id) ON DELETE CASCADE,
    UNIQUE KEY unique_judge_user (judge_id, user_id)
);

-- Performance indexes
CREATE INDEX idx_scores_user_id ON scores(user_id);
CREATE INDEX idx_scores_judge_id ON scores(judge_id);
CREATE INDEX idx_scores_points ON scores(points);
```

### Key Relationships:
- **judges** ↔ **scores** (One judge can give multiple scores)
- **users** ↔ **scores** (One user can receive multiple scores)
- **Unique constraint** prevents duplicate scoring by same judge for same user
- **Comments field** allows judges to provide feedback with scores
- **Updated timestamps** track when records are modified
- **Performance indexes** optimize query performance for scoreboard generation

## Usage

### Admin Panel (`/admin/`)
1. **Manage Judges**: Add new judges with username, display name, and email
2. **Manage Users**: Add participants who will be scored
3. **Dashboard**: Overview of system statistics

### Judge Portal (`/judge/`)
1. **Login**: Select judge from dropdown (simplified for demo)
2. **Select User**: Choose participant to score
3. **Submit Score**: Enter points (1-100) with optional comments

### Public Scoreboard (`/public/scoreboard.php`)
- **Live Rankings**: Auto-refreshes every 30 seconds
- **Total Points**: Displays accumulated scores for each participant
- **Sorted Display**: Descending order by total points
- **Responsive**: Works on all device sizes

## Design Choices

### Database Design
- **Normalized Structure**: Separate tables for judges, users, and scores
- **Foreign Keys**: Ensure data integrity with proper relationships  
- **Unique Constraints**: Prevent duplicate scores from same judge
- **Score Validation**: Database-level constraints for point ranges

### PHP Architecture
- **Environment Configuration**: PHP-DotEnv for secure credential management
- **PDO Database Layer**: Modern PHP Data Objects with prepared statements
- **Comprehensive Functions**: 20+ utility functions in `includes/functions.php`
- **Error Handling**: Database exceptions logged and user-friendly error messages
- **Input Validation**: Sanitization and validation for all user inputs
- **Email Validation**: Cross-table email uniqueness checking

### Frontend Approach
- **Progressive Enhancement**: Works without JavaScript, enhanced with JS
- **Responsive Design**: Mobile-first CSS approach
- **Auto-refresh**: JavaScript-based scoreboard updates
- **User Experience**: Clear navigation and feedback messages

### File Organization
- **Logical Separation**: Admin, judge, and public interfaces in separate directories
- **Asset Management**: Centralized CSS, JS, and images
- **Environment Configuration**: Secure `.env` file for database credentials
- **Dependency Management**: Composer for PHP package management
- **Reusable Components**: Common headers, footers, and comprehensive function library

## Assumptions Made

1. **Authentication**: Simplified judge selection for demo purposes (production would require proper login)
2. **Admin Security**: Admin panel assumes trusted access (production needs authentication)
3. **Score Range**: Points limited to 1-100 scale with database constraints
4. **Single Scoring**: Each judge can score each participant once (with update capability)
5. **Sample Data**: Database schema includes test data for immediate functionality
6. **Environment Variables**: Database credentials managed via .env file
7. **Comments Optional**: Judges can provide feedback but it's not required

## Future Enhancements

If given more time, I would implement:

### Security & Authentication
- [ ] **Secure Login System**: Password hashing, session management
- [ ] **Role-based Access Control**: Admin vs Judge permissions
- [ ] **CSRF Protection**: Form token validation
- [ ] **Input Sanitization**: Enhanced XSS prevention

### Advanced Features
- [ ] **Score Editing**: Allow judges to modify their scores
- [ ] **Bulk Operations**: Import judges/users via CSV
- [ ] **Advanced Analytics**: Score distribution charts and statistics
- [ ] **Real-time Notifications**: WebSocket-based live updates
- [ ] **Audit Trail**: Log all scoring activities

### User Experience
- [ ] **Advanced Filtering**: Filter scoreboard by categories/groups
- [ ] **Export Functionality**: PDF/Excel export of results
- [ ] **Mobile App**: Native mobile application
- [ ] **Dark Mode**: Theme switching capability

### Technical Improvements
- [ ] **Caching**: Redis/Memcached for better performance
- [ ] **API Development**: RESTful API for external integrations
- [ ] **Docker Deployment**: Containerized deployment
- [ ] **Automated Testing**: Unit and integration tests
- [ ] **CI/CD Pipeline**: Automated deployment process

### Database Enhancements
- [ ] **Multiple Events**: Support for different scoring events
- [ ] **Score Categories**: Different scoring criteria
- [ ] **Historical Data**: Archive completed events
- [ ] **Backup System**: Automated database backups

## Technical Stack

- **Backend**: PHP 7.4+ with PDO
- **Database**: MySQL 5.7+ with performance indexes
- **Environment Management**: PHP-DotEnv for configuration
- **Frontend**: HTML5, CSS3, JavaScript
- **Web Server**: Apache 2.4+
- **Development**: LAMP stack with Composer dependency management

## Support

For issues or questions regarding this implementation:
1. Check the `test/test_connection.php` for database connectivity
2. Verify the `.env` file contains correct database credentials
3. Ensure Composer dependencies are installed (`composer install`)
4. Verify all file permissions are correctly set
5. Ensure PHP extensions (PDO, pdo_mysql) are enabled
6. Check Apache error logs for detailed error information

---

**Note**: This is a demonstration application built for technical screening purposes. For production use, additional security measures, proper authentication, and comprehensive testing would be required.