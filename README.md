# Oauth Payment API

This Laravel-based project demonstrates the integration of Google OAuth 2.0 for authentication and payment gateway integrations for eSewa and Khalti. The system provides user authentication and a seamless checkout process using popular payment platforms in Nepal.

## Features

-   **Google OAuth 2.0**: Enables users to log in using their Google account.
-   **eSewa Integration**: Supports eSewa payment processing with status verification.
-   **Khalti Integration**: Handles Khalti payment initiation and verification.
-   **Secure Authentication**: Uses Laravel's built-in authentication features.

## Prerequisites

Before running the project, ensure you have the following installed:

-   PHP >= 8.0
-   Composer
-   Laravel Framework
-   MySQL or other supported databases

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/RahulKoju/Oauth_Payment_API.git
    ```

2. Navigate to the project directory:

    ```bash
    cd Oauth_Payment_API
    ```

3. Install dependencies:

    ```bash
    composer install
    ```

4. Set up the environment file:

    ```bash
    cp .env.example .env
    ```

    Update the `.env` file with your database, Google OAuth, eSewa, and Khalti credentials.

5. Generate the application key:

    ```bash
    php artisan key:generate
    ```

6. Run database migrations:

    ```bash
    php artisan migrate
    ```

7. Start the development server:
    ```bash
    php artisan serve
    ```

## Routes

### Authentication Routes

-   `/auth/google`: Redirects to Google for authentication.
-   `/auth/google/callback`: Handles Google OAuth callback.
-   `/logout`: Logs out the user.

### eSewa Routes

-   `/checkout/esewa`: Initiates eSewa payment.
-   `/payment/esewa/success`: Handles successful eSewa payment.
-   `/payment/esewa/failure`: Handles failed eSewa payment.
-   `/payment/esewa/status`: Checks the status of an eSewa transaction.

### Khalti Routes

-   `/checkout/khalti`: Initiates Khalti payment.
-   `/khalti/payment/initiate`: Starts Khalti payment process.
-   `/khalti/payment/verify`: Verifies Khalti payment.
-   `/khalti/payment/success`: Handles successful Khalti payment.
-   `/khalti/payment/failure`: Handles failed Khalti payment.

## Key Files and Directories

-   **Controllers**:

    -   `AuthController`: Handles Google OAuth authentication.
    -   `EsewaController`: Manages eSewa payment processing.
    -   `KhaltiController`: Manages Khalti payment processing.

-   **Views**:

    -   `resources/views/auth/login.blade.php`: Login page.
    -   `resources/views/dashboard.blade.php`: User dashboard.
    -   `resources/views/payments`: Contains views for payment processes and statuses.

-   **Services**:
    -   `App\Services\EsewaService`: Handles eSewa API interactions.

## Usage

1. Navigate to `/login` to log in using your Google account.
2. After login, access the dashboard and choose a payment gateway.
3. Proceed with the payment and follow the success or failure messages.

## Environment Variables

Add the following keys to your `.env` file:

```env
# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=your-google-redirect-uri

# eSewa
ESEWA_MERCHANT_ID=your-esewa-merchant-id
ESEWA_SECRET_KEY=your-esewa-secret-key

# Khalti
KHALTI_SECRET_KEY=your-khalti-secret-key
```

## Contributions

Feel free to fork the repository and submit pull requests. For major changes, please open an issue first to discuss what you would like to change.

## Contact

For inquiries or support, contact [Rahul Koju](https://www.linkedin.com/in/rahul-koju/).
