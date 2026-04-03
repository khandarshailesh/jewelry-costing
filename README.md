# Jewelry Costing Application

A comprehensive jewelry costing and management system built with Laravel and Vue.js, featuring multi-tenant architecture, advanced costing calculations, and BRWZR ZTAGE 3D editor integration.

## Features

- **Multi-Tenant Architecture**: Support for multiple organizations with isolated data
- **Comprehensive Costing System**:
  - Chain costing with detailed calculations
  - Lucky items costing
  - Bill of Materials (BOM) management
  - Advanced rate configurations
- **3D Editor Integration**: BRWZR ZTAGE 3D editor for jewelry design
- **Master Data Management**:
  - Categories
  - Materials
  - Stone Types
  - OXO Factors
  - Rate Configurations
- **User Management**: Role-based access control
- **Product Management**: Complete CRUD for jewelry products
- **PDF Export**: Generate detailed costing reports

## Tech Stack

- **Backend**: Laravel 11.x
- **Frontend**: Vue.js 3 with Inertia.js
- **Database**: MySQL
- **UI Framework**: Tailwind CSS
- **Build Tool**: Vite

## Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- NPM or Yarn
- MySQL >= 8.0

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/khandarshailesh/jewelry-costing.git
cd jewelry-costing
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the example environment file and configure your environment variables:

```bash
cp .env.example .env
```

Update the following variables in `.env`:

```env
APP_NAME="Jewelry Costing"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jewelry_costing
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@jewelrycosting.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Create Database

Create a MySQL database named `jewelry_costing` (or your preferred name):

```sql
CREATE DATABASE jewelry_costing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7. Run Database Migrations

```bash
php artisan migrate
```

### 8. Seed the Database (Optional)

Seed the database with initial data for stone types and OXO factors:

```bash
php artisan db:seed
```

### 9. Create Storage Symlink

```bash
php artisan storage:link
```

### 10. Build Frontend Assets

For development:
```bash
npm run dev
```

For production:
```bash
npm run build
```

### 11. Start the Application

Start the Laravel development server:

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Default Login Credentials

After installation, you'll need to register a new user. The first registered user will have admin privileges.

## Project Structure

```
jewelry-costing/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # API & Web Controllers
│   │   ├── Middleware/       # Custom Middleware
│   │   └── Requests/         # Form Requests
│   ├── Models/               # Eloquent Models
│   └── Services/             # Business Logic Services
├── database/
│   ├── migrations/           # Database Migrations
│   └── seeders/              # Database Seeders
├── resources/
│   ├── js/
│   │   ├── Components/       # Vue Components
│   │   ├── Layouts/          # Layout Components
│   │   └── Pages/            # Page Components
│   └── css/                  # Stylesheets
├── routes/
│   ├── web.php               # Web Routes
│   └── auth.php              # Authentication Routes
└── public/                   # Public Assets
```

## Key Modules

### 1. Materials Management
Manage different types of materials used in jewelry production with rates and specifications.

### 2. Products
Create and manage jewelry products with detailed specifications and 3D models.

### 3. Costing Calculator
Calculate comprehensive costs for:
- Chain items with detailed breakdowns
- Lucky items with stone and labor costs
- Bill of Materials with component-wise costing

### 4. Rate Configuration
Configure various rates used in costing calculations:
- Gold rates
- Labor rates
- Stone rates
- Manufacturing overhead

### 5. Master Data
Manage master data including:
- Categories
- Stone Types
- OXO Factors
- Units of Measurement

## Development

### Running Tests

```bash
php artisan test
```

### Code Style

This project follows PSR-12 coding standards for PHP and Vue.js best practices.

### Database Migrations

Create a new migration:
```bash
php artisan make:migration create_tablename_table
```

Run migrations:
```bash
php artisan migrate
```

Rollback migrations:
```bash
php artisan migrate:rollback
```

## Production Deployment

### 1. Optimize Configuration

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Build Assets

```bash
npm run build
```

### 3. Set Permissions

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 4. Environment Variables

Set `APP_ENV=production` and `APP_DEBUG=false` in your `.env` file.

### 5. Web Server Configuration

Configure your web server (Apache/Nginx) to point to the `public` directory.

#### Nginx Example:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/jewelry-costing/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Troubleshooting

### Common Issues

**Issue**: "Class not found" errors
```bash
composer dump-autoload
```

**Issue**: Permission denied errors
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

**Issue**: NPM build errors
```bash
rm -rf node_modules package-lock.json
npm install
npm run build
```

**Issue**: Database connection errors
- Check your `.env` database credentials
- Ensure MySQL service is running
- Verify the database exists

## API Documentation

The application provides RESTful APIs for all major functionalities. API endpoints are prefixed with `/api/`.

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Security

If you discover any security vulnerabilities, please email info@flexgrew.com instead of using the issue tracker.

## License

This project is proprietary software developed by Flexgrew Technologies.

## Support

For support and inquiries, please contact:
- Email: info@flexgrew.com
- GitHub Issues: https://github.com/khandarshailesh/jewelry-costing/issues

## Credits

Developed and maintained by **Flexgrew Technologies**

---

Built with Laravel 11 and Vue.js 3
