# Garanti BBVA Virtual POS Module for WHMCS

This project contains a payment module for WHMCS to integrate with Garanti BBVA Virtual POS. With this module, your customers can pay directly via Garanti BBVA Virtual POS by entering their credit card information.

## Features
- Collects credit card details from users and processes the payment through Garanti BBVA’s API.
- Sends payment details directly to the bank’s API.
- Updates WHMCS with the result of each transaction (successful or failed).

## Requirements
- WHMCS (version 7.x or higher)
- Garanti BBVA Virtual POS API credentials
- SSL certificate (required for PCI-DSS compliance)

## Installation

1. **Copy the Files:** Place the project files in `modules/gateways/` and `modules/gateways/callback/` directories.

    ```
    modules/
    └── gateways/
        ├── garantibbva.php
        └── garantibbva/
            └── callback.php
    ```

2. **Activate the Module:** Go to your WHMCS Admin Panel, then navigate to `Setup` > `Payments` > `Payment Gateways`. Find "Garanti BBVA Virtual POS" among the modules and activate it.

3. **Configure the Settings:** 
    - `Terminal ID`: Enter the terminal ID provided by Garanti BBVA.
    - `Store Key`: Enter the store security key from Garanti BBVA.
    - `Provision User ID`: Enter the provision user ID provided by Garanti BBVA.

## Usage

1. **Payment Form:** When viewing an invoice, customers can enter their credit card information to complete the payment.
2. **Processing Payment:** Customer card details are sent to the API to initiate the payment process. If successful, the invoice will be marked as paid.
3. **Callback Processing:** Garanti BBVA sends the payment result to the callback file, which logs the result in the WHMCS system.

## Security Precautions

- **SSL is Required:** SSL is mandatory for collecting credit card information securely.
- **PCI-DSS Compliance:** Transactions requiring direct collection of card details necessitate PCI-DSS compliance. In this module, card details are securely transferred directly to Garanti BBVA’s API, so ensuring security measures are in place is essential.

## Design
- It’s recommended to change your design in the `garantibbva.php` file.

## Important Notes
- **Test Mode:** It’s recommended to conduct initial tests using Garanti BBVA’s test environment.
- **Updates:** Check and update module files as needed in case of updates to WHMCS or Garanti BBVA’s API.

## Troubleshooting
Enable WHMCS Debug Mode to view transaction logs and troubleshoot issues:
1. In your WHMCS Admin Panel, go to `Setup > Other Settings > Debug Mode` to enable it.
2. Use `logTransaction` functions in the `modules/gateways/callback/garantibbva_callback.php` file to capture detailed logs for debugging.

## Support
This module provides a basic structure for integrating Garanti BBVA Virtual POS with WHMCS. For further information and support, refer to Garanti BBVA’s technical documentation or visit WHMCS community forums.

---

**Note:** This module example is intended to facilitate your integration process with Garanti BBVA. Before using it in production, ensure full compliance with Garanti BBVA’s security and regulatory requirements.
