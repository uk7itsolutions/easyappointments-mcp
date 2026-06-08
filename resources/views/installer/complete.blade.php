<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyAppointments MCP — Installed</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { background: #fff; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,.1); padding: 2.5rem; width: 100%; max-width: 480px; }
        .icon { font-size: 2.5rem; margin-bottom: 1rem; }
        h1 { font-size: 1.4rem; font-weight: 600; margin-bottom: .5rem; }
        p { color: #555; font-size: .9rem; line-height: 1.6; margin-bottom: 1rem; }
        code { display: block; background: #f4f4f5; border-radius: 6px; padding: .85rem 1rem; font-size: .85rem; word-break: break-all; margin: .5rem 0 1rem; }
        h2 { font-size: 1rem; font-weight: 600; margin: 1.5rem 0 .5rem; }
        pre { background: #f4f4f5; border-radius: 6px; padding: .85rem 1rem; font-size: .8rem; overflow-x: auto; }
    </style>
</head>
<body>
<div class="card">
    <div class="icon">✓</div>
    <h1>Installation complete</h1>
    <p>Your EasyAppointments MCP server is ready. Connect your MCP client using the details below.</p>

    <h2>Server URL</h2>
    <code>{{ config('app.url') }}/mcp</code>

    <h2>Client configuration</h2>
    <pre>{
  "mcpServers": {
    "easyappointments": {
      "url": "{{ config('app.url') }}/mcp",
      "headers": {
        "Authorization": "Bearer &lt;your-ea-api-key&gt;"
      }
    }
  }
}</pre>

    <p style="margin-top:1.5rem; font-size:.8rem; color:#888;">
        The API key is the same one used during setup. Each user can use their own key from EasyAppointments → Settings → API.
    </p>
</div>
</body>
</html>
