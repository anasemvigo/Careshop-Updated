# Careshop-Updated

[GitHub Repository](https://github.com/anasemvigo/Careshop-Updated)

## Description
Careshop-Updated is an e-commerce platform built with Magento, designed to provide a robust and flexible solution for care-related products and services. This repository contains the latest updates and improvements to the Careshop platform. The main branch for development is `main`.

## Features
- Built on Magento for scalability and flexibility
- User-friendly interface
- Product catalog management
- Secure authentication
- Shopping cart and checkout functionality
- Order management
- Responsive design

## Installation
1. **Clone the repository:**
   ```bash
   git clone https://github.com/anasemvigo/Careshop-Updated.git
   cd Careshop-Updated
   ```
2. **Set up your environment:**
   - Ensure you have PHP, Composer, and a web server (Apache/Nginx) installed.
   - Set up a MySQL database for Magento.
3. **Install Magento dependencies:**
   ```bash
   composer install
   ```
4. **Magento setup:**
   ```bash
   php bin/magento setup:install \
     --base-url=http://localhost/careshop-updated \
     --db-host=localhost \
     --db-name=your_db_name \
     --db-user=your_db_user \
     --db-password=your_db_password \
     --admin-firstname=Admin \
     --admin-lastname=User \
     --admin-email=admin@example.com \
     --admin-user=admin \
     --admin-password=admin123 \
     --language=en_US \
     --currency=USD \
     --timezone=America/Chicago \
     --use-rewrites=1
   ```
   Adjust the parameters as needed for your environment.

5. **Set permissions:**
   ```bash
   find var generated vendor pub/static pub/media app/etc -type f -exec chmod g+w {} +
   find var generated vendor pub/static pub/media app/etc -type d -exec chmod g+ws {} +
   chown -R :www-data .
   chmod u+x bin/magento
   ```

## Usage
- Access the storefront at `http://localhost/careshop-updated` (or your configured base URL).
- Access the Magento admin panel at `http://localhost/careshop-updated/admin`.

## Contributing
Contributions are welcome! Please fork the repository and submit a pull request to the `main` branch. For major changes, open an issue first to discuss what you would like to change.

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
