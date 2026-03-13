# NICPOINT.BA TEST Environment Registrar Module

This is a dedicated version of the NICPOINT.BA WHMCS registrar module, specifically pre-configured for the **TEST Environment**.

## Why use this?
By using this separate module (`nicpointtest`), you can:
1.  Keep your Production module (`nicpoint`) connected to the live API.
2.  Have a separate entry in WHMCS for testing.
3.  Assign specific TLDs (like `.test.ba`) to the test module to prevent accidental live registrations.

## Directory Structure

```text
nicpointtest/
├── nicpointtest.php      # Main entry point (prefixed functions)
├── lib/
│   └── NicpointTestApiClient.php   # API Client (Unique namespace)
└── README.md             # This file
```

## Installation

1.  Upload the `nicpointtest` folder to your WHMCS installation at:
    `modules/registrars/nicpointtest/`
2.  In WHMCS Admin, go to **System Settings** > **Domain Registrars**.
3.  Activate **NICPOINT.BA TEST ENV**.
4.  The default test URLs are already pre-configured and hidden from the UI. Enter your test **Username** and **Password** to begin.

## Simultaneous Use
Because all functions and classes are renamed, this module **will not conflict** with the standard `nicpoint` module. You can have both active at the same time.

---
Built by: **BYTE d.o.o. Sarajevo** | [https://byte.ba](https://byte.ba)
