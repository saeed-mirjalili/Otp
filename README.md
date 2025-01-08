OTP Authentication Package for Laravel

This README will guide you through setting up the OTP (One-Time Password) authentication package for your Laravel project.


Step 1: Register for UltraMsg


Go to UltraMsg and create an account.

After registering, acquire the following parameters:

Instance

Token


Step 2: Install the Package

Run the following command in your Laravel project's terminal to install the OTP package:

composer require saeed/otp:dev-main

Step 3: Update Composer Autoload

Add the following line to the autoload section of your composer.json file:

"Saeed\\Otp\\": "src/"

Then run:

composer dump-autoload

Step 4: Configure Service Provider

Open the config/app.php file and add the service provider to the providers array:

Saeed\Otp\OtpServiceProvider::class,

Also, add an alias to the aliases array:

'Otp' => Saeed\Otp\OtpFacade::class,

Step 5: Update Authentication Providers

In config/auth.php, replace the model entry with:

'model' => \Saeed\Otp\Models\OtpUser::class,

instead of using your default User model.


Step 6: Publish Configuration File

To publish the configuration file for the package, run:

php artisan vendor:publish --provider="saeed\otp\OtpServiceProvider" --tag="otp" --force

Step 7: Configure Instance and Token

Open the published configuration file located at config/otp.php. Add the instance and token you received from UltraMsg:

'WhatsApp_Intance' => instance,
'WhatsApp_Token' => token,

Step 8: Run Migrations

Run the following command to migrate the necessary database tables:

php artisan migrate

Step 9: Create Home Page

Now, you can create a home page to utilize the OTP functionality in your Laravel application.

