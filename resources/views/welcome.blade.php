<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel URL Shortener Demo</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen',
                'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue',
                sans-serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            line-height: 1.6;
            background: #f9fafb;
            color: #111827;
        }
        code {
            background: #e5e7eb;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.9em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            border: 1px solid #d1d5db;
            text-align: left;
        }
        th {
            background: #f3f4f6;
        }
        h1 { color: #1f2937; }
        h2 { margin-top: 30px; color: #374151; }
    </style>
</head>
<body>
    <h1>Laravel URL Shortener Demo</h1>
    <p>A small demo showcasing Laravel + Redis + queued jobs + Docker + PHPUnit + CI.</p>

    <h2>Endpoints</h2>
    <table>
        <thead>
            <tr>
                <th>Method</th>
                <th>Endpoint</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>POST</code></td>
                <td><code>/api/links</code></td>
                <td>Create a short link. Body: <code>{"url": "https://..."}</code></td>
            </tr>
            <tr>
                <td><code>GET</code></td>
                <td><code>/api/links/{code}</code></td>
                <td>Get link details and visit count</td>
            </tr>
            <tr>
                <td><code>GET</code></td>
                <td><code>/{code}</code></td>
                <td>Redirect to the original URL and queue a visit</td>
            </tr>
        </tbody>
    </table>

    <h2>Quick example</h2>
    <pre><code>curl -X POST http://localhost:8000/api/links \
  -H "Content-Type: application/json" \
  -d '{"url": "https://laravel.com"}'</code></pre>

    <p>Visit the returned <code>short_url</code> to redirect and record a visit.</p>

    <h2>Notes</h2>
    <ul>
        <li>Code → URL lookups are cached (Redis / array in tests).</li>
        <li>Visit counting runs as a queued <code>RegisterVisit</code> job.</li>
        <li>Tests run with SQLite in-memory, <code>array</code> cache, and <code>sync</code> queue.</li>
    </ul>
</body>
</html>
