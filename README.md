# Web-Based Payroll Management System (WBPMS)

A browser-based payroll, attendance, and employee self-service platform for
Light Diamond Enterprises. The system replaces a manual, spreadsheet-based
payroll process with a centralized web application for attendance processing,
salary computation, government contributions, requests, approvals, payslips,
and reports.

---

## System requirements

| Requirement | Version |
|---|---|
| PHP | 8.1 or higher |
| MySQL / MariaDB | 8.0 / 10.4 or higher |
| Apache | 2.4 or higher |
| Composer | 2.x |

> **Recommended local setup:** XAMPP 8.x on Windows, which bundles Apache,
> MySQL (or MariaDB), and PHP 8.x in a single installer.

---

## Modules

1. Login and authentication (role-based access, password recovery)
2. Role-based dashboards (Business Owner, HR Head, Employee)
3. User management
4. Employee and branch management
5. Work schedule and holiday calendar management
6. Biometric attendance import and timesheet generation
7. Leave, overtime, and cash-advance request management
8. Payroll computation and Business Owner approval workflow
9. Salary structure management
10. Government contributions and deductions (SSS, PhilHealth, Pag-IBIG)
11. Reports and file exports
12. Employee self-service portal (attendance, requests, payslips)

---

## Quick setup

For detailed step-by-step instructions, see [SETUP.md](SETUP.md).

### 1. Place files

Copy the entire project folder into your web server root:

```
C:\xampp\htdocs\wbpms\
```

### 2. Configure environment

Copy `.env.example` to `.env` and fill in your local values:

```ini
APP_ENV=development
APP_BASE_URL=http://localhost/wbpms/public
APP_KEY=your-random-32-character-secret-key

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=wbpms
DB_USER=root
DB_PASSWORD=your_mysql_password

MAIL_DRIVER=log
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME=WBPMS
```

Set `MAIL_DRIVER=smtp` and fill in your SMTP credentials to enable live
password-reset emails. Set `MAIL_DRIVER=log` to write reset links to
`storage/private/mail.log` instead (useful for local development without
an SMTP server).

### 3. Create the database

In phpMyAdmin or the MySQL command line:

```sql
CREATE DATABASE wbpms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Run migrations

```bat
vendor\bin\phinx migrate -c phinx.php
```

### 5. Load initial data (seeders)

```bat
vendor\bin\phinx seed:run -c phinx.php
```

### 6. Start the application

Start Apache and MySQL in XAMPP, then open:

```
http://localhost/wbpms/public/login
```

Or double-click **RUNME.bat** for a guided start.

---

## Demo accounts

After running migrations and seeders, the following synthetic accounts are
available for local setup and testing.
**Change or delete these before deploying to any shared or production
environment.**

| Role | Email | Password |
|---|---|---|
| Business Owner | `owner@demo.test` | `owner-demo-pass` |
| HR Head | `hrhead@demo.test` | `hrhead-demo-pass` |
| Employee | `employee@demo.test` | `employee-demo-pass` |

---

## Useful commands

Run from the project root in a Command Prompt:

```bat
REM Install PHP dependencies (only if vendor\ is missing)
composer install

REM Run all database migrations
vendor\bin\phinx migrate -c phinx.php

REM Load demo/reference data
vendor\bin\phinx seed:run -c phinx.php

REM Check migration status
vendor\bin\phinx status -c phinx.php

REM Roll back the last migration (use with caution)
vendor\bin\phinx rollback -c phinx.php

REM Run automated tests
vendor\bin\phpunit --configuration phpunit.xml

REM Start PHP built-in server (alternative to XAMPP Apache)
php -S localhost:8000 -t public\
```

---

## Documentation

| Document | Purpose |
|---|---|
| [SETUP.md](SETUP.md) | Step-by-step local setup guide for XAMPP on Windows |
| [docs/xampp-windows-deployment.md](docs/xampp-windows-deployment.md) | Detailed XAMPP and Apache configuration reference |
| [docs/tech.md](docs/tech.md) | Technology stack, architecture, and development reference |
| [docs/product.md](docs/product.md) | Product overview, modules, and business rules |
| [docs/requirements.md](docs/requirements.md) | Functional requirements and acceptance criteria |
| [docs/User_Manual.md](docs/User_Manual.md) | End-user manual (Markdown) |
| [docs/User_Manual.docx](docs/User_Manual.docx) | End-user manual (Word document) |
| [docs/employee-lifecycle-archive-rehire-spec.md](docs/employee-lifecycle-archive-rehire-spec.md) | Employee archive, rehire, and account lifecycle rules |

---

## Production checklist

Before deploying to a shared or production environment:

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Change `APP_KEY` to a unique random string (32+ characters)
- [ ] Set a strong `DB_PASSWORD`
- [ ] Configure a real SMTP server (`MAIL_DRIVER=smtp`)
- [ ] Delete or change the demo accounts
- [ ] Enable HTTPS (configure SSL in Apache or your hosting control panel)
- [ ] Confirm `storage/private/` is not publicly accessible
- [ ] Remove `storage/private/mail.log` if present

---

## License

No open-source license has been applied to this repository. Unless the
project owners state otherwise, the source code and documentation should
be treated as all-rights-reserved project material.
