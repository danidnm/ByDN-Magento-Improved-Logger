# Bydn_ImprovedLogger for Magento 2

`Bydn_ImprovedLogger` is a Magento 2 module designed to enhance system logging by providing contextual information and real-time alerts. It uses Monolog processors and handlers to enrich log data and send notifications via Email and Telegram.

## Features

### 1. Extended Log Information
The module adds useful metadata to every log entry, making debugging significantly easier by identifying exactly where and how a log was generated.
- **IP Addresses**: Adds Remote IP, Server IP, and Client IP.
- **Request URL**: Records the full browser URL where the log occurred.
- **Controller Info**: Includes the module, controller, and action name.
- **Trace Info**: Automatically detects the exact file and line number that called the logger.

### 2. Real-time Notifications
Stay informed about critical system issues as they happen.
- **Email Alerts**: Sends log details to configured email addresses.
- **Telegram Notifications**: Pushes alerts directly to a Telegram chat via Bot API.
- **Filtered Alerts**: By default, notifications are triggered for **Critical** and **Emergency** log levels.

## Configuration

Navigate to `Stores > Configuration > Utilities (by DN) > Improved Logger` to manage the module settings.

### Log Files Extra Info
- **Enable extra info**: Global toggle for log enrichment.
- **Add IP info**: Toggle IP address logging.
- **Add browser URL**: Toggle current URL logging.
- **Add controller info**: Toggle Magento request metadata.
- **Add caller info**: Toggle file/line trace (Note: small performance impact).

### Notifications (Email & Telegram)
- **Email/Telegram Enable**: Toggle the respective notification service.
- **Credentials**: Configure Email destinations or Telegram Bot Token and Chat ID.
- **Exception Logging**: (Optional) Send all `exception.log` records via the selected channel.

## Installation

```bash
composer require bydn/module-improved-logger
bin/magento module:enable Bydn_ImprovedLogger
bin/magento setup:upgrade
bin/magento cache:flush
```

---

## Technical Overview

- **Processor**: `Bydn\ImprovedLogger\Processor\Extrainfo` implements a Monolog processor to inject data into the `$record->extra` array.
- **Handler**: `Bydn\ImprovedLogger\Handler\Notification` is a custom Monolog handler that intercepts log records and triggers notification models.
- **Plugin**: A plugin on `Magento\Framework\Logger\Monolog` ensures the custom handler is correctly pushed into the Monolog stack.

> [!NOTE]
> This module is designed with extensibility in mind, allowing for easy addition of new notification channels or data processors.
