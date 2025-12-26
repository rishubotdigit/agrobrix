# Agrobrix Property Management System

A comprehensive Laravel-based property management platform that connects property owners, agents, and buyers with advanced features like contact management, payment integration, and role-based access control.

## Features

### 🏠 Property Management
- **Property Listing**: Owners can create and manage property listings with versioning
- **Property Approval**: Admin approval system for property changes
- **Advanced Search**: Buyers can browse properties with filtering options

### 👥 User Roles & Permissions
- **Admin**: Full system management, user management, property approvals, settings
- **Owner**: Property listing and management
- **Agent**: Property assistance and client management
- **Buyer**: Property browsing and contact viewing

### 💰 Subscription & Payments
- **Flexible Plans**: Tiered subscription plans with different capabilities
- **Add-ons**: Additional features and capacity boosts
- **Razorpay Integration**: Secure payment processing for premium features
- **Contact Limits**: Controlled access to owner contact information

### 📱 Communication
- **OTP Verification**: Secure registration with SMS verification via Twilio
- **Contact Viewing**: Buyers can view owner contact details (with limits)
- **Payment for Extra Access**: Pay-as-you-go for additional contacts

### ✅ Task Management
- **Todo System**: Built-in task management for all user roles
- **Status Tracking**: Pending, In Progress, Completed status management

## Email Queue System

✅ Queue is ON:

When you set Email Queue Mode ON in Admin Settings, every email is now processed properly via cron.

All emails like user registration, inquiry forms, or system notifications are automatically sent in the background.

Admin email logs show all emails accurately.

🔹 Key features working now:

Cron job runs automatically every minute.

No duplicate emails are sent.

Failed emails are retried automatically.

Test emails can be sent immediately, whether the queue is ON or OFF.

No manual commands or supervisor processes required.

Summary:

Queue ON → emails reliably processed in the background.

Queue OFF → emails not queued (except test emails).

✅ The system is now fully production-ready and email processing is fully automated. and admin email log properly show

## Architecture Overview

### Tech Stack
- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: SQLite (configurable to MySQL/PostgreSQL)
- **Frontend**: Blade Templates with Tailwind CSS
- **Asset Compilation**: Vite
- **Payments**: Razorpay
- **SMS**: Twilio
- **Authentication**: Laravel Sanctum with OTP

### Key Components
- **Role-based Middleware**: Capability-based access control
- **Property Versioning**: Track changes and require approvals
- **Payment Gateway**: Integrated Razorpay for transactions
- **OTP System**: Secure user verification
- **Settings Management**: Configurable system settings

### Database Schema
- **Users**: Role-based user management with plans and addons
- **Properties**: Property listings with versioning
- **Payments**: Transaction tracking
- **Plans & Addons**: Subscription management
- **Todos**: Task management system
- **Viewed Contacts**: Contact access tracking

## Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm
- SQLite (or MySQL/PostgreSQL)

### Quick Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd new-agrobrix
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Configure your `.env` file with:
   - Database settings
   - Razorpay credentials
   - Twilio credentials
   - Mail settings

5. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed  # Optional: Load sample data
   ```

6. **Build Assets**
   ```bash
   npm run build
   ```

7. **Start the Application**
   ```bash
   php artisan serve
   ```

   For development with hot reload:
   ```bash
   composer run dev
   ```

### Environment Variables

Key configuration options in `.env`:

```env
# Database
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Razorpay
RAZORPAY_KEY_ID=your_razorpay_key
RAZORPAY_KEY_SECRET=your_razorpay_secret

# Twilio
TWILIO_SID=your_twilio_sid
TWILIO_TOKEN=your_twilio_token
TWILIO_FROM=your_twilio_number

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_email@example.com
MAIL_PASSWORD=your_password
```

## Deployment

### Production Deployment

1. **Server Requirements**
   - PHP 8.2+
   - Web server (Apache/Nginx)
   - Database server
   - SSL certificate

2. **Deployment Steps**
   ```bash
   # Set production environment
   APP_ENV=production
   APP_DEBUG=false

   # Install dependencies
   composer install --optimize-autoloader --no-dev
   npm run build

   # Database migration
   php artisan migrate --force

   # Cache optimization
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache

   # Set proper permissions
   chown -R www-data:www-data storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```

3. **Web Server Configuration**

   **Nginx Example:**
   ```nginx
   server {
       listen 80;
       server_name your-domain.com;
       root /path/to/public;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           include fastcgi_params;
           fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
       }
   }
   ```

4. **SSL Setup**
   - Obtain SSL certificate (Let's Encrypt recommended)
   - Configure HTTPS redirect

### Docker Deployment (Optional)

A `docker-compose.yml` can be created for containerized deployment:

```yaml
version: '3.8'
services:
  app:
    image: php:8.2-fpm
    volumes:
      - .:/var/www/html
    environment:
      - APP_ENV=production

  web:
    image: nginx:alpine
    ports:
      - "80:80"
    volumes:
      - .:/var/www/html
      - ./nginx.conf:/etc/nginx/nginx.conf

  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: agrobrix
      MYSQL_USER: agrobrix
      MYSQL_PASSWORD: password
```

## API Documentation

Complete API documentation is available in [`API.md`](API.md), including:
- Authentication endpoints
- Property management APIs
- Payment integration
- User management

## User Guides

Detailed user guides for each role:
- [Admin Guide](docs/user-guide.md#admin)
- [Owner Guide](docs/user-guide.md#owner)
- [Agent Guide](docs/user-guide.md#agent)
- [Buyer Guide](docs/user-guide.md#buyer)

## UI Components

Reusable UI components and patterns are documented in [`Components.md`](Components.md), featuring:
- Tailwind CSS classes
- Component examples
- Responsive design patterns

## Development

### Code Style
- Follow PSR-12 coding standards
- Use Laravel conventions
- Maintain consistent naming

### Testing
```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter TestName
```

### Code Quality
```bash
# Run Pint for code formatting
./vendor/bin/pint

# Run static analysis (if configured)
# phpstan analyse
```

## Security

- CSRF protection on all forms
- Input validation and sanitization
- Role-based access control
- Secure payment processing
- OTP-based verification

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the documentation

---

Built with ❤️ using Laravel
