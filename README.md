# NICPOINT WHMCS Registrar Module

![NICPOINT Logo](https://nicpoint.ba/assets/images/logo-full.png)

Official WHMCS registrar module for **NICPOINT.ba**. This module allows hosting providers and resellers to automate the registration, renewal, and management of **.BA** domains directly through the WHMCS platform.

---

## 🌍 Overview / Pregled

| English | Bosnian |
| :--- | :--- |
| This module provides a seamless integration between WHMCS and the NICPOINT API, supporting all core domain lifecycle operations with real-time feedback. | Ovaj modul omogućava besprijekornu integraciju između WHMCS-a i NICPOINT API-ja, podržavajući sve ključne operacije životnog ciklusa domene uz povratne informacije u stvarnom vremenu. |

## ✨ Features / Mogućnosti

- **Real-time Availability Check**: High-speed lookups for .BA domains.
- **Automated Registration**: Register domains instantly upon payment.
- **Renewal & Transfers**: Automated renewal and transfer handling via EPP.
- **Nameserver Management**: Update NS records directly from the WHMCS client area.
- **Multi-Environment Support**: Separate modules for Production and Sandbox (Test) environments.
- **Hardcoded Security**: Pre-configured API endpoints to prevent accidental misconfiguration.

---

## 🚀 Installation / Instalacija

### 1. Choose your version
- **Production**: Use the `nicpoint` directory.
- **Sandbox (Test)**: Use the `nicpointtest` directory.

### 2. Upload
Upload the chosen folder(s) to your WHMCS installation:
- Production: `/your-whmcs-root/modules/registrars/nicpoint/`
- Sandbox: `/your-whmcs-root/modules/registrars/nicpointtest/`

### 3. Activate
1. Log in to your **WHMCS Admin Area**.
2. Navigate to **System Settings > Domain Registrars**.
3. Activate the **Nicpoint** (or **Nicpointtest**) module.
4. Enter your credentials provided by the NICPOINT team.

> [!IMPORTANT]
> API URLs are pre-configured in the module. You only need to enter your **Username** and **Password**.

---

## 🛠 Branch Strategy / Struktura Grana

Following the CI/CD best practices for this repository:
- **`main`**: Production-ready code. Always stable.
- **`develop`**: Development and testing. Used for verifying new features against the Sandbox API.

---

## ⚖️ License / Licenca

This project is licensed under the **MIT License**. See the `LICENSE` file for details.

---

&copy; 2026 [NICPOINT.ba](https://nicpoint.ba) by [BYTEDOO](https://byte.ba).
