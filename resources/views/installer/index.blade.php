<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyAppointments MCP — Setup</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { background: #fff; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,.1); padding: 2.5rem; width: 100%; max-width: 480px; }
        h1 { font-size: 1.4rem; font-weight: 600; margin-bottom: .25rem; }
        p.sub { color: #666; font-size: .9rem; margin-bottom: 2rem; }
        label { display: block; font-size: .85rem; font-weight: 500; margin-bottom: .35rem; color: #333; }
        input { width: 100%; padding: .65rem .85rem; border: 1px solid #ddd; border-radius: 6px; font-size: .95rem; transition: border-color .15s; }
        input:focus { outline: none; border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.1); }
        .field { margin-bottom: 1.25rem; }
        .hint { font-size: .8rem; color: #888; margin-top: .35rem; }
        .error { font-size: .8rem; color: #dc2626; margin-top: .35rem; }
        button { width: 100%; padding: .75rem; background: #4f46e5; color: #fff; border: none; border-radius: 6px; font-size: 1rem; font-weight: 500; cursor: pointer; margin-top: .5rem; }
        button:hover { background: #4338ca; }
        .alert { background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: .85rem 1rem; margin-bottom: 1.5rem; font-size: .875rem; color: #991b1b; }
    </style>
</head>
<body>
<div class="card">
    <h1>EasyAppointments MCP</h1>
    <p class="sub">Connect this MCP server to your EasyAppointments instance.</p>

    @if ($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('installer.install') }}">
        @csrf

        <div class="field">
            <label for="ea_base_url">EasyAppointments URL</label>
            <input type="url" id="ea_base_url" name="ea_base_url"
                   value="{{ old('ea_base_url') }}"
                   placeholder="https://appointments.yourdomain.com"
                   required>
            <p class="hint">The full URL of your EasyAppointments installation.</p>
            @error('ea_base_url') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="ea_api_key">API Key</label>
            <input type="password" id="ea_api_key" name="ea_api_key"
                   placeholder="Your EasyAppointments API secret token"
                   required>
            <p class="hint">Found in EasyAppointments → Backend → Settings → API.</p>
            @error('ea_api_key') <p class="error">{{ $message }}</p> @enderror
        </div>

        <button type="submit">Install →</button>
    </form>
</div>
</body>
</html>
