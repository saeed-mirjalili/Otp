# Laravel OTP Authentication Package

This guide walks you through setting up the OTP (One-Time Password) authentication package for your Laravel project.

---

## Prerequisites

1. A [UltraMsg](https://www.ultramsg.com/) account.
2. Laravel installed on your project.

---

## Setup Instructions

### 1. Register for UltraMsg

- Visit [UltraMsg](https://www.ultramsg.com/) and create an account.
- Obtain the following credentials:
  - **Instance**
  - **Token**

---

### 2. Install the OTP Package

Run the following command in your Laravel project terminal:

```bash
composer require saeed/otp:dev-main
```

---

### 3. Update Composer Autoload

1. Open your `composer.json` file.
2. Add the following line to the `autoload` section:

   ```json
   "Saeed\\Otp\\": "src/"
   ```

3. Run the following command to update the autoloader:

   ```bash
   composer dump-autoload
   ```

---

### 4. Configure the Service Provider

1. Open the `config/app.php` file.
2. Add the service provider to the `providers` array:

   ```php
   Saeed\Otp\OtpServiceProvider::class,
   ```

3. Add an alias to the `aliases` array:

   ```php
   'Otp' => Saeed\Otp\OtpFacade::class,
   ```

---

### 5. Update Authentication Providers

1. Open the `config/auth.php` file.
2. Replace the default model entry with the OTP User model:

   ```php
   'model' => \Saeed\Otp\Models\OtpUser::class,
   ```

---

### 6. Publish Configuration File

To publish the package configuration, run:

```bash
php artisan vendor:publish --provider="Saeed\Otp\OtpServiceProvider" --tag="otp" --force
```

---

### 7. Configure Instance and Token

1. Open the published configuration file: `config/otp.php`.
2. Add your UltraMsg credentials:

   ```php
   'WhatsApp_Instance' => 'your_instance',
   'WhatsApp_Token' => 'your_token',
   ```

---

### 8. Run Database Migrations

Run the following command to create the required database tables:

```bash
php artisan migrate
```

---

### 9. Create a Home Page

You can now create a home page in your application to implement and utilize OTP functionality.

---

## Notes

- Ensure you have your `.env` file properly configured for database and app settings before running migrations.
- For more details and advanced usage, refer to the package documentation or contact the package maintainer.

---
Happy Coding! 🚀

