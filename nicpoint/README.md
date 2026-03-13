# NICPOINT WHMCS Registrar Module

This directory contains the WHMCS registrar module for integration with the NICPOINT.BA API.

## Directory Structure

```text
WHMCS/
├── nicpoint.php          # Main entry point for WHMCS
├── lib/
│   └── NicpointApiClient.php   # API Client library
└── README.md             # This file
```

## Installation

1.  Upload the `nicpoint` folder (and its contents) to your WHMCS installation at:
    `modules/registrars/nicpoint/`
2.  Login to your WHMCS Admin Area.
3.  Navigate to **System Settings** > **Domain Registrars** (or **Setup** > **Products/Services** > **Domain Registrars** in older versions).
4.  Find **NICPOINT.BA Registrar** in the list and click **Activate**.
5.  Enter your **Username** and **Password**. (URLs are already pre-configured).
6.  Click **Save Changes**.

## Features Supported

- Domain Availability Check
- Domain Registration
- Domain Renewal
- Domain Transfer (with EPP Code)
- Nameserver Management (Get/Save Nameservers)

## Technical Details

The module uses JWT (JSON Web Token) authentication. It automatically handles token retrieval and refresh during API operations. 

For custom implementations or debugging, refer to the `lib/NicpointApiClient.php` which contains the core HTTP logic.

---
Built by: **BYTE d.o.o. Sarajevo** | [https://byte.ba](https://byte.ba)
