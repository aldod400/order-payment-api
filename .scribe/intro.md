# Introduction

REST API for managing orders and payments, with pluggable payment gateways, secured with JWT.

<aside>
    <strong>Base URL</strong>: <code>http://localhost:8000</code>
</aside>

Welcome to the Order & Payment API. Register or log in to get a JWT token, then send it as
`Authorization: Bearer {token}` on every other endpoint.

Users can only see and manage their own orders and payments. Accessing someone else's returns `403`.

## Response format

Every response uses the same JSON envelope:

```json
{
    "status": 200,
    "message": "Success",
    "data": {}
}
```

Validation failures return `422` with the first error in `message` and every field error under `error`.
Send `Accept-Language: ar` or `Accept-Language: en` to localise response messages.

<aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside>

