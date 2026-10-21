# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer {YOUR_JWT_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

Get a JWT from <code>POST /api/v1/auth/register</code> or <code>POST /api/v1/auth/login</code>, then send it as <code>Authorization: Bearer {token}</code>.
