# EasyAppointments MCP Server

A Laravel-based [Model Context Protocol](https://modelcontextprotocol.io) server for [EasyAppointments](https://easyappointments.org). It exposes your EasyAppointments instance as MCP tools so AI clients like Claude can manage appointments, customers, services, and providers on your behalf.

> **Compatibility:** Built and tested against **EasyAppointments 1.6.0**. This MCP server communicates with EA via its REST API. Future EA versions may add, change, or remove API endpoints. Always verify compatibility against the [EasyAppointments changelog](https://github.com/alextselegidis/easyappointments/releases) before upgrading.

## How It Works

```
MCP Client (e.g. Claude Desktop)
        │  Authorization: Bearer <ea-api-key>
        ▼
appointments-mcp.yourdomain.com/mcp
        │
        ├── ValidateEaApiKey middleware
        │   Verifies the Bearer token against your EA instance's API.
        │   If it fails, returns 401. If it passes, the same token is
        │   used to make all subsequent EA API calls.
        │
        └── MCP Tools → EasyAppointments REST API
```

**Authentication** is handled entirely by EasyAppointments. Each user uses their own EA API key (found in EA → Backend → Settings → API) as the Bearer token. No separate user database is needed.

**First-run installer** — on a fresh install, any request to the app redirects to `/setup`, a web wizard that collects your EA URL and API key, verifies the connection, and writes the configuration automatically.

---

## Requirements

- PHP 8.2+
- Composer
- EasyAppointments **1.6.0** with the API enabled (Settings → API → enable & copy secret token)

---

## Installation (Plesk)

### 1. Create a Subdomain

In Plesk → **Websites & Domains** → **Add Subdomain**.
Name it `appointments-mcp` (or whatever you prefer).

### 2. Clone the Repository

Open **SSH Terminal** for the subdomain and run:

```bash
git clone https://github.com/uk7itsolutions/easyappointments-mcp.git .
```

### 3. Install Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### 4. Set the Document Root

In Plesk → **Websites & Domains** → your subdomain → **Hosting Settings**,
set the document root to:

```
appointments-mcp.yourdomain.com/public
```

### 5. Enable SSL

In Plesk → **SSL/TLS Certificates** → **Let's Encrypt** → check **Redirect HTTP to HTTPS** → **Get it free**.

### 6. Run the Web Installer

Visit `https://appointments-mcp.yourdomain.com/setup` in your browser and complete the two-field form:

- **EasyAppointments URL** — e.g. `https://appointments.yourdomain.com`
- **API Key** — found in EasyAppointments → Backend → Settings → API

The installer will verify the connection, write your `.env`, generate the app key, and show your final MCP endpoint on the confirmation screen.

---

## Connecting an MCP Client

Add the following to your MCP client configuration (e.g. Claude Desktop's `claude_desktop_config.json`):

```json
{
  "mcpServers": {
    "easyappointments": {
      "url": "https://appointments-mcp.yourdomain.com/mcp",
      "headers": {
        "Authorization": "Bearer <your-ea-api-key>"
      }
    }
  }
}
```

Each user authenticates with their own EA API key.

---

## Available Tools

| Tool | Description |
|---|---|
| `check_availability` | Return open time slots for a service on a given date |
| `list_appointments` | List appointments, filtered by date or customer |
| `get_appointment` | Get full details for a single appointment |
| `create_appointment` | Book an appointment |
| `update_appointment` | Update fields on an existing appointment |
| `cancel_appointment` | Cancel an appointment |
| `search_customers` | Search customers by name, email, or phone |
| `get_customer` | Get full details for a single customer |
| `create_customer` | Create a new customer record |
| `update_customer` | Update fields on an existing customer |
| `list_services` | List all available services |
| `list_providers` | List all providers, optionally filtered by service |
| `get_settings` | Return instance settings (business name, hours, etc.) |

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── InstallerController.php   # Web installer logic
│   └── Middleware/
│       ├── ValidateEaApiKey.php       # Validates Bearer token against EA API
│       └── RedirectIfNotInstalled.php # Redirects to /setup until configured
├── Mcp/
│   ├── Servers/
│   │   └── EasyAppointmentsServer.php # Registers all tools
│   └── Tools/                         # One class per MCP tool (13 total)
└── Services/
    └── EasyAppointmentsClient.php     # HTTP client for EA REST API

routes/
├── ai.php    # Registers the MCP server at /mcp
└── web.php   # Registers the installer at /setup

resources/views/installer/
├── index.blade.php     # Setup form
└── complete.blade.php  # Success screen with client config

bootstrap/app.php       # Middleware aliases + CSRF exclusion pre-configured
config/ea.php           # Reads EA_BASE_URL from .env
```

---

## License

This project is licensed under the [MIT License](LICENSE).

It communicates with EasyAppointments solely via its REST API and contains no EasyAppointments source code. It is therefore an independent work and is not subject to EasyAppointments' GPLv3 licence.
