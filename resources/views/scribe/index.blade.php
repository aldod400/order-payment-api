<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Order & Payment API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "{{ config("app.url") }}";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.11.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.11.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                                    <ul id="tocify-subheader-introduction" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="response-format">
                                <a href="#response-format">Response format</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authentication" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authentication">
                    <a href="#authentication">Authentication</a>
                </li>
                                    <ul id="tocify-subheader-authentication" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="authentication-POSTapi-v1-auth-register">
                                <a href="#authentication-POSTapi-v1-auth-register">Register</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-POSTapi-v1-auth-login">
                                <a href="#authentication-POSTapi-v1-auth-login">Login</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-POSTapi-v1-auth-refresh">
                                <a href="#authentication-POSTapi-v1-auth-refresh">Refresh token</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-GETapi-v1-auth-me">
                                <a href="#authentication-GETapi-v1-auth-me">Current user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-POSTapi-v1-auth-logout">
                                <a href="#authentication-POSTapi-v1-auth-logout">Logout</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-orders" class="tocify-header">
                <li class="tocify-item level-1" data-unique="orders">
                    <a href="#orders">Orders</a>
                </li>
                                    <ul id="tocify-subheader-orders" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="orders-GETapi-v1-orders">
                                <a href="#orders-GETapi-v1-orders">List orders</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="orders-POSTapi-v1-orders">
                                <a href="#orders-POSTapi-v1-orders">Create an order</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="orders-GETapi-v1-orders--id-">
                                <a href="#orders-GETapi-v1-orders--id-">Show an order</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="orders-PUTapi-v1-orders--id-">
                                <a href="#orders-PUTapi-v1-orders--id-">Update an order</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="orders-DELETEapi-v1-orders--id-">
                                <a href="#orders-DELETEapi-v1-orders--id-">Delete an order</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-payments" class="tocify-header">
                <li class="tocify-item level-1" data-unique="payments">
                    <a href="#payments">Payments</a>
                </li>
                                    <ul id="tocify-subheader-payments" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="payments-GETapi-v1-orders--order_id--payments">
                                <a href="#payments-GETapi-v1-orders--order_id--payments">List an order's payments</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="payments-POSTapi-v1-orders--order_id--payments">
                                <a href="#payments-POSTapi-v1-orders--order_id--payments">Pay for an order</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="payments-GETapi-v1-payments">
                                <a href="#payments-GETapi-v1-payments">List payments</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="payments-GETapi-v1-payments--id-">
                                <a href="#payments-GETapi-v1-payments--id-">Show a payment</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: October 21, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>REST API for managing orders and payments, with pluggable payment gateways, secured with JWT.</p>
<aside>
    <strong>Base URL</strong>: <code>http://localhost:8000</code>
</aside>
<p>Welcome to the Order &amp; Payment API. Register or log in to get a JWT token, then send it as
<code>Authorization: Bearer {token}</code> on every other endpoint.</p>
<p>Users can only see and manage their own orders and payments. Accessing someone else's returns <code>403</code>.</p>
<h2 id="response-format">Response format</h2>
<p>Every response uses the same JSON envelope:</p>
<pre><code class="language-json">{
    "status": 200,
    "message": "Success",
    "data": {}
}</code></pre>
<p>Validation failures return <code>422</code> with the first error in <code>message</code> and every field error under <code>error</code>.
Send <code>Accept-Language: ar</code> or <code>Accept-Language: en</code> to localise response messages.</p>
<aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>To authenticate requests, include an <strong><code>Authorization</code></strong> header with the value <strong><code>"Bearer {YOUR_JWT_TOKEN}"</code></strong>.</p>
<p>All authenticated endpoints are marked with a <code>requires authentication</code> badge in the documentation below.</p>
<p>Get a JWT from <code>POST /api/v1/auth/register</code> or <code>POST /api/v1/auth/login</code>, then send it as <code>Authorization: Bearer {token}</code>.</p>

        <h1 id="authentication">Authentication</h1>

    <p>Register, log in and manage your JWT. Send the token as <code>Authorization: Bearer {token}</code> on every other endpoint.
Login and register are limited to 5 requests per minute per IP.</p>

                                <h2 id="authentication-POSTapi-v1-auth-register">Register</h2>

<p>
</p>

<p>Create an account and receive a JWT straight away.</p>

<span id="example-requests-POSTapi-v1-auth-register">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/auth/register" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Ali Hassan\",
    \"email\": \"ali@example.com\",
    \"password\": \"secret123\",
    \"password_confirmation\": \"secret123\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/auth/register"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Ali Hassan",
    "email": "ali@example.com",
    "password": "secret123",
    "password_confirmation": "secret123"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-register">
            <blockquote>
            <p>Example response (201, Registered):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 201,
    &quot;message&quot;: &quot;Registered successfully&quot;,
    &quot;data&quot;: {
        &quot;user&quot;: {
            &quot;id&quot;: &quot;9d8f1e62-3c4b-7a1e-9f2a-6b7c8d9e0f12&quot;,
            &quot;name&quot;: &quot;Ali Hassan&quot;,
            &quot;email&quot;: &quot;ali@example.com&quot;,
            &quot;created_at&quot;: &quot;2026-10-21T18:32:42.000000Z&quot;
        },
        &quot;token&quot;: &quot;eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI5ZDhmMWU2MiJ9.signature&quot;,
        &quot;token_type&quot;: &quot;bearer&quot;,
        &quot;expires_in&quot;: 3600
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 422,
    &quot;message&quot;: &quot;The email has already been taken.&quot;,
    &quot;error&quot;: {
        &quot;email&quot;: [
            &quot;The email has already been taken.&quot;
        ],
        &quot;password&quot;: [
            &quot;The password field must contain at least one number.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Too many attempts):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 429,
    &quot;message&quot;: &quot;Too many requests, please try again later&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-register" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-register"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-register"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-register" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-register">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-register" data-method="POST"
      data-path="api/v1/auth/register"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-register', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-register"
                    onclick="tryItOut('POSTapi-v1-auth-register');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-register"
                    onclick="cancelTryOut('POSTapi-v1-auth-register');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-register"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/register</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-auth-register"
               value="Ali Hassan"
               data-component="body">
    <br>
<p>Full name. Max 255 characters. Example: <code>Ali Hassan</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-auth-register"
               value="ali@example.com"
               data-component="body">
    <br>
<p>A unique email address. Example: <code>ali@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-auth-register"
               value="secret123"
               data-component="body">
    <br>
<p>At least 8 characters, with letters and numbers. Example: <code>secret123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password_confirmation</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password_confirmation"                data-endpoint="POSTapi-v1-auth-register"
               value="secret123"
               data-component="body">
    <br>
<p>Must match <code>password</code>. Example: <code>secret123</code></p>
        </div>
        </form>

                    <h2 id="authentication-POSTapi-v1-auth-login">Login</h2>

<p>
</p>

<p>Exchange email and password for a JWT.</p>

<span id="example-requests-POSTapi-v1-auth-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/auth/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"ali@example.com\",
    \"password\": \"secret123\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/auth/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "ali@example.com",
    "password": "secret123"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-login">
            <blockquote>
            <p>Example response (200, Logged in):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Logged in successfully&quot;,
    &quot;data&quot;: {
        &quot;user&quot;: {
            &quot;id&quot;: &quot;9d8f1e62-3c4b-7a1e-9f2a-6b7c8d9e0f12&quot;,
            &quot;name&quot;: &quot;Ali Hassan&quot;,
            &quot;email&quot;: &quot;ali@example.com&quot;,
            &quot;created_at&quot;: &quot;2026-10-21T18:32:42.000000Z&quot;
        },
        &quot;token&quot;: &quot;eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI5ZDhmMWU2MiJ9.signature&quot;,
        &quot;token_type&quot;: &quot;bearer&quot;,
        &quot;expires_in&quot;: 3600
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Wrong email or password):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Invalid email or password&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 422,
    &quot;message&quot;: &quot;The email field is required.&quot;,
    &quot;error&quot;: {
        &quot;email&quot;: [
            &quot;The email field is required.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Too many attempts):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 429,
    &quot;message&quot;: &quot;Too many requests, please try again later&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-login" data-method="POST"
      data-path="api/v1/auth/login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-login"
                    onclick="tryItOut('POSTapi-v1-auth-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-login"
                    onclick="cancelTryOut('POSTapi-v1-auth-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-auth-login"
               value="ali@example.com"
               data-component="body">
    <br>
<p>Example: <code>ali@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-auth-login"
               value="secret123"
               data-component="body">
    <br>
<p>Example: <code>secret123</code></p>
        </div>
        </form>

                    <h2 id="authentication-POSTapi-v1-auth-refresh">Refresh token</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Exchange the current token (even an expired one, inside the refresh window) for a new one.
The old token stops working.</p>

<span id="example-requests-POSTapi-v1-auth-refresh">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/auth/refresh" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/auth/refresh"
);

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-refresh">
            <blockquote>
            <p>Example response (200, Refreshed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Token refreshed successfully&quot;,
    &quot;data&quot;: {
        &quot;user&quot;: {
            &quot;id&quot;: &quot;9d8f1e62-3c4b-7a1e-9f2a-6b7c8d9e0f12&quot;,
            &quot;name&quot;: &quot;Ali Hassan&quot;,
            &quot;email&quot;: &quot;ali@example.com&quot;,
            &quot;created_at&quot;: &quot;2026-10-21T18:32:42.000000Z&quot;
        },
        &quot;token&quot;: &quot;eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJuZXcifQ.signature&quot;,
        &quot;token_type&quot;: &quot;bearer&quot;,
        &quot;expires_in&quot;: 3600
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Token missing, invalid or already used):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Unauthenticated, please login first&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-refresh" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-refresh"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-refresh"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-refresh" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-refresh">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-refresh" data-method="POST"
      data-path="api/v1/auth/refresh"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-refresh', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-refresh"
                    onclick="tryItOut('POSTapi-v1-auth-refresh');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-refresh"
                    onclick="cancelTryOut('POSTapi-v1-auth-refresh');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-refresh"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/refresh</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-auth-refresh"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-refresh"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-refresh"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="authentication-GETapi-v1-auth-me">Current user</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Return the user who owns the token.</p>

<span id="example-requests-GETapi-v1-auth-me">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/auth/me" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/auth/me"
);

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-auth-me">
            <blockquote>
            <p>Example response (200, Success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Success&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: &quot;9d8f1e62-3c4b-7a1e-9f2a-6b7c8d9e0f12&quot;,
        &quot;name&quot;: &quot;Ali Hassan&quot;,
        &quot;email&quot;: &quot;ali@example.com&quot;,
        &quot;created_at&quot;: &quot;2026-10-21T18:32:42.000000Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing or invalid token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Unauthenticated, please login first&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-auth-me" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-auth-me"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-auth-me"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-auth-me" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-auth-me">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-auth-me" data-method="GET"
      data-path="api/v1/auth/me"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-auth-me', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-auth-me"
                    onclick="tryItOut('GETapi-v1-auth-me');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-auth-me"
                    onclick="cancelTryOut('GETapi-v1-auth-me');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-auth-me"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/auth/me</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-auth-me"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-auth-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-auth-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="authentication-POSTapi-v1-auth-logout">Logout</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Invalidate the current token.</p>

<span id="example-requests-POSTapi-v1-auth-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/auth/logout" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/auth/logout"
);

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-logout">
            <blockquote>
            <p>Example response (200, Logged out):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Logged out successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing or invalid token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Unauthenticated, please login first&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-logout" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-logout"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-logout" data-method="POST"
      data-path="api/v1/auth/logout"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-logout', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-logout"
                    onclick="tryItOut('POSTapi-v1-auth-logout');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-logout"
                    onclick="cancelTryOut('POSTapi-v1-auth-logout');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-logout"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/logout</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-auth-logout"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="orders">Orders</h1>

    <p>Create and manage your orders. You only ever see and change your own orders.</p>
<p>Status flow: <code>pending</code> → <code>confirmed</code> or <code>cancelled</code>; <code>confirmed</code> → <code>cancelled</code> (only while unpaid).
Customer details and items can only change while the order is <code>pending</code>.</p>

                                <h2 id="orders-GETapi-v1-orders">List orders</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Your orders, newest first, paginated.</p>

<span id="example-requests-GETapi-v1-orders">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/orders?status=pending&amp;page=1&amp;per_page=15" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/orders"
);

const params = {
    "status": "pending",
    "page": "1",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-orders">
            <blockquote>
            <p>Example response (200, Success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Success&quot;,
    &quot;data&quot;: [
        {
            &quot;id&quot;: &quot;01a15fbc-af62-704f-8643-f69b490d1ea9&quot;,
            &quot;customer_name&quot;: &quot;Ali Hassan&quot;,
            &quot;customer_email&quot;: &quot;ali@example.com&quot;,
            &quot;customer_phone&quot;: &quot;+201001234567&quot;,
            &quot;status&quot;: &quot;pending&quot;,
            &quot;total&quot;: &quot;60.07&quot;,
            &quot;items&quot;: [
                {
                    &quot;id&quot;: &quot;01a15fbc-af64-7190-8a65-6e8f95840565&quot;,
                    &quot;product_name&quot;: &quot;Book&quot;,
                    &quot;quantity&quot;: 3,
                    &quot;price&quot;: &quot;19.99&quot;,
                    &quot;subtotal&quot;: &quot;59.97&quot;
                },
                {
                    &quot;id&quot;: &quot;01a15fbc-af64-7190-8a65-6e8f95e6e532&quot;,
                    &quot;product_name&quot;: &quot;Pen&quot;,
                    &quot;quantity&quot;: 1,
                    &quot;price&quot;: &quot;0.10&quot;,
                    &quot;subtotal&quot;: &quot;0.10&quot;
                }
            ],
            &quot;created_at&quot;: &quot;2026-10-21T18:32:42.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-10-21T18:32:42.000000Z&quot;
        }
    ],
    &quot;current_page&quot;: 1,
    &quot;from&quot;: 1,
    &quot;last_page&quot;: 1,
    &quot;per_page&quot;: 15,
    &quot;to&quot;: 1,
    &quot;total&quot;: 1,
    &quot;links&quot;: {
        &quot;first&quot;: &quot;http://localhost:8000/api/v1/orders?page=1&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/v1/orders?page=1&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing or invalid token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Unauthenticated, please login first&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Invalid filter):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 422,
    &quot;message&quot;: &quot;The selected status is invalid.&quot;,
    &quot;error&quot;: {
        &quot;status&quot;: [
            &quot;The selected status is invalid.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-orders" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-orders"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-orders"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-orders" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-orders">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-orders" data-method="GET"
      data-path="api/v1/orders"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-orders', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-orders"
                    onclick="tryItOut('GETapi-v1-orders');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-orders"
                    onclick="cancelTryOut('GETapi-v1-orders');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-orders"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/orders</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-orders"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-orders"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-orders"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-v1-orders"
               value="pending"
               data-component="query">
    <br>
<p>Filter by status: <code>pending</code>, <code>confirmed</code> or <code>cancelled</code>. Example: <code>pending</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-orders"
               value="1"
               data-component="query">
    <br>
<p>Page number. Defaults to 1. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-orders"
               value="15"
               data-component="query">
    <br>
<p>Items per page, 1 to 100. Defaults to 15. Example: <code>15</code></p>
            </div>
                </form>

                    <h2 id="orders-POSTapi-v1-orders">Create an order</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>The order starts as <code>pending</code>. Each item's subtotal and the order total are calculated on the server;
a <code>total</code> sent by the client is ignored.</p>

<span id="example-requests-POSTapi-v1-orders">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/orders" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"customer_name\": \"Ali Hassan\",
    \"customer_email\": \"ali@example.com\",
    \"customer_phone\": \"+201001234567\",
    \"items\": [
        {
            \"product_name\": \"Book\",
            \"quantity\": 3,
            \"price\": 19.99
        }
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/orders"
);

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "customer_name": "Ali Hassan",
    "customer_email": "ali@example.com",
    "customer_phone": "+201001234567",
    "items": [
        {
            "product_name": "Book",
            "quantity": 3,
            "price": 19.99
        }
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-orders">
            <blockquote>
            <p>Example response (201, Created):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 201,
    &quot;message&quot;: &quot;Created successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01a15fbc-af62-704f-8643-f69b490d1ea9&quot;,
        &quot;customer_name&quot;: &quot;Ali Hassan&quot;,
        &quot;customer_email&quot;: &quot;ali@example.com&quot;,
        &quot;customer_phone&quot;: &quot;+201001234567&quot;,
        &quot;status&quot;: &quot;pending&quot;,
        &quot;total&quot;: &quot;59.97&quot;,
        &quot;items&quot;: [
            {
                &quot;id&quot;: &quot;01a15fbc-af64-7190-8a65-6e8f95840565&quot;,
                &quot;product_name&quot;: &quot;Book&quot;,
                &quot;quantity&quot;: 3,
                &quot;price&quot;: &quot;19.99&quot;,
                &quot;subtotal&quot;: &quot;59.97&quot;
            }
        ],
        &quot;created_at&quot;: &quot;2026-10-21T18:32:42.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-10-21T18:32:42.000000Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing or invalid token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Unauthenticated, please login first&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 422,
    &quot;message&quot;: &quot;The customer name field is required.&quot;,
    &quot;error&quot;: {
        &quot;customer_name&quot;: [
            &quot;The customer name field is required.&quot;
        ],
        &quot;items.0.quantity&quot;: [
            &quot;The quantity of item #1 field must be at least 1.&quot;
        ],
        &quot;items.0.price&quot;: [
            &quot;The price of item #1 field must have 0-2 decimal places.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-orders" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-orders"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-orders"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-orders" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-orders">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-orders" data-method="POST"
      data-path="api/v1/orders"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-orders', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-orders"
                    onclick="tryItOut('POSTapi-v1-orders');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-orders"
                    onclick="cancelTryOut('POSTapi-v1-orders');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-orders"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/orders</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-orders"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-orders"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-orders"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer_name"                data-endpoint="POSTapi-v1-orders"
               value="Ali Hassan"
               data-component="body">
    <br>
<p>Max 255 characters. Example: <code>Ali Hassan</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer_email"                data-endpoint="POSTapi-v1-orders"
               value="ali@example.com"
               data-component="body">
    <br>
<p>Example: <code>ali@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer_phone"                data-endpoint="POSTapi-v1-orders"
               value="+201001234567"
               data-component="body">
    <br>
<p>Optional. Max 30 characters. Example: <code>+201001234567</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>items</code></b>&nbsp;&nbsp;
<small>object[]</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>1 to 100 items.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>product_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="items.0.product_name"                data-endpoint="POSTapi-v1-orders"
               value="Book"
               data-component="body">
    <br>
<p>Example: <code>Book</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>quantity</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="items.0.quantity"                data-endpoint="POSTapi-v1-orders"
               value="3"
               data-component="body">
    <br>
<p>1 to 10000. Example: <code>3</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>price</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="items.0.price"                data-endpoint="POSTapi-v1-orders"
               value="19.99"
               data-component="body">
    <br>
<p>Unit price, up to 2 decimal places. Example: <code>19.99</code></p>
                    </div>
                                    </details>
        </div>
        </form>

                    <h2 id="orders-GETapi-v1-orders--id-">Show an order</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>One of your orders, with its items.</p>

<span id="example-requests-GETapi-v1-orders--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/orders/01a15fbc-af62-704f-8643-f69b490d1ea9" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/orders/01a15fbc-af62-704f-8643-f69b490d1ea9"
);

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-orders--id-">
            <blockquote>
            <p>Example response (200, Success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Success&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01a15fbc-af62-704f-8643-f69b490d1ea9&quot;,
        &quot;customer_name&quot;: &quot;Ali Hassan&quot;,
        &quot;customer_email&quot;: &quot;ali@example.com&quot;,
        &quot;customer_phone&quot;: &quot;+201001234567&quot;,
        &quot;status&quot;: &quot;confirmed&quot;,
        &quot;total&quot;: &quot;59.97&quot;,
        &quot;items&quot;: [
            {
                &quot;id&quot;: &quot;01a15fbc-af64-7190-8a65-6e8f95840565&quot;,
                &quot;product_name&quot;: &quot;Book&quot;,
                &quot;quantity&quot;: 3,
                &quot;price&quot;: &quot;19.99&quot;,
                &quot;subtotal&quot;: &quot;59.97&quot;
            }
        ],
        &quot;created_at&quot;: &quot;2026-10-21T18:32:42.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-10-21T18:35:10.000000Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing or invalid token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Unauthenticated, please login first&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Someone else&#039;s order):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 403,
    &quot;message&quot;: &quot;You don&#039;t have permission to access this resource&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Order not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 404,
    &quot;message&quot;: &quot;Resource not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-orders--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-orders--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-orders--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-orders--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-orders--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-orders--id-" data-method="GET"
      data-path="api/v1/orders/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-orders--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-orders--id-"
                    onclick="tryItOut('GETapi-v1-orders--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-orders--id-"
                    onclick="cancelTryOut('GETapi-v1-orders--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-orders--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/orders/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-orders--id-"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-orders--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-orders--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="GETapi-v1-orders--id-"
               value="01a15fbc-af62-704f-8643-f69b490d1ea9"
               data-component="url">
    <br>
<p>The order ID (UUID). Example: <code>01a15fbc-af62-704f-8643-f69b490d1ea9</code></p>
            </div>
                    </form>

                    <h2 id="orders-PUTapi-v1-orders--id-">Update an order</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Send only the fields you want to change (at least one).
Sending <code>items</code> replaces all current items and recalculates the total.
Use <code>status</code> to confirm or cancel the order.</p>

<span id="example-requests-PUTapi-v1-orders--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/v1/orders/01a15fbc-af62-704f-8643-f69b490d1ea9" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"status\": \"confirmed\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/orders/01a15fbc-af62-704f-8643-f69b490d1ea9"
);

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "status": "confirmed"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-orders--id-">
            <blockquote>
            <p>Example response (200, Updated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Updated successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01a15fbc-af62-704f-8643-f69b490d1ea9&quot;,
        &quot;customer_name&quot;: &quot;Ali Hassan&quot;,
        &quot;customer_email&quot;: &quot;ali@example.com&quot;,
        &quot;customer_phone&quot;: &quot;+201001234567&quot;,
        &quot;status&quot;: &quot;confirmed&quot;,
        &quot;total&quot;: &quot;59.97&quot;,
        &quot;items&quot;: [
            {
                &quot;id&quot;: &quot;01a15fbc-af64-7190-8a65-6e8f95840565&quot;,
                &quot;product_name&quot;: &quot;Book&quot;,
                &quot;quantity&quot;: 3,
                &quot;price&quot;: &quot;19.99&quot;,
                &quot;subtotal&quot;: &quot;59.97&quot;
            }
        ],
        &quot;created_at&quot;: &quot;2026-10-21T18:32:42.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-10-21T18:35:10.000000Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing or invalid token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Unauthenticated, please login first&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Someone else&#039;s order):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 403,
    &quot;message&quot;: &quot;You don&#039;t have permission to access this resource&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Order not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 404,
    &quot;message&quot;: &quot;Resource not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (409, Order is not pending):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 409,
    &quot;message&quot;: &quot;Only pending orders can have their details or items changed&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Status change not allowed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 422,
    &quot;message&quot;: &quot;Order status cannot change from confirmed to pending&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Empty update):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 422,
    &quot;message&quot;: &quot;Send at least one field to update&quot;,
    &quot;error&quot;: {
        &quot;order&quot;: [
            &quot;Send at least one field to update&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-orders--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-orders--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-orders--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-orders--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-orders--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-orders--id-" data-method="PUT"
      data-path="api/v1/orders/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-orders--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-orders--id-"
                    onclick="tryItOut('PUTapi-v1-orders--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-orders--id-"
                    onclick="cancelTryOut('PUTapi-v1-orders--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-orders--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/orders/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/orders/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-orders--id-"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-orders--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-orders--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="PUTapi-v1-orders--id-"
               value="01a15fbc-af62-704f-8643-f69b490d1ea9"
               data-component="url">
    <br>
<p>The order ID (UUID). Example: <code>01a15fbc-af62-704f-8643-f69b490d1ea9</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer_name"                data-endpoint="PUTapi-v1-orders--id-"
               value=""
               data-component="body">
    <br>
<p>Max 255 characters. Example:</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer_email"                data-endpoint="PUTapi-v1-orders--id-"
               value=""
               data-component="body">
    <br>
<p>Example:</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer_phone"                data-endpoint="PUTapi-v1-orders--id-"
               value=""
               data-component="body">
    <br>
<p>Max 30 characters. Example:</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="PUTapi-v1-orders--id-"
               value="confirmed"
               data-component="body">
    <br>
<p><code>pending</code>, <code>confirmed</code> or <code>cancelled</code>. Example: <code>confirmed</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>items</code></b>&nbsp;&nbsp;
<small>object[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="items[0]"                data-endpoint="PUTapi-v1-orders--id-"
               data-component="body">
        <input type="text" style="display: none"
               name="items[1]"                data-endpoint="PUTapi-v1-orders--id-"
               data-component="body">
    <br>
<p>1 to 100 items, each with <code>product_name</code>, <code>quantity</code> and <code>price</code> (same rules as Create). Replaces the current items. Example:</p>
        </div>
        </form>

                    <h2 id="orders-DELETEapi-v1-orders--id-">Delete an order</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Deletes the order and its items. Orders that have any payment (even a failed one) cannot be deleted.</p>

<span id="example-requests-DELETEapi-v1-orders--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/v1/orders/01a15fbc-af62-704f-8643-f69b490d1ea9" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/orders/01a15fbc-af62-704f-8643-f69b490d1ea9"
);

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-orders--id-">
            <blockquote>
            <p>Example response (200, Deleted):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Deleted successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing or invalid token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Unauthenticated, please login first&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Someone else&#039;s order):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 403,
    &quot;message&quot;: &quot;You don&#039;t have permission to access this resource&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Order not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 404,
    &quot;message&quot;: &quot;Resource not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (409, Order has payments):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 409,
    &quot;message&quot;: &quot;Order cannot be deleted because it has payments&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-orders--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-orders--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-orders--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-orders--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-orders--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-orders--id-" data-method="DELETE"
      data-path="api/v1/orders/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-orders--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-orders--id-"
                    onclick="tryItOut('DELETEapi-v1-orders--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-orders--id-"
                    onclick="cancelTryOut('DELETEapi-v1-orders--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-orders--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/orders/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-v1-orders--id-"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-orders--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-orders--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="DELETEapi-v1-orders--id-"
               value="01a15fbc-af62-704f-8643-f69b490d1ea9"
               data-component="url">
    <br>
<p>The order ID (UUID). Example: <code>01a15fbc-af62-704f-8643-f69b490d1ea9</code></p>
            </div>
                    </form>

                <h1 id="payments">Payments</h1>

    <p>Pay for confirmed orders and view payments. Gateways are simulated:</p>
<ul>
<li><code>credit_card</code>: a card number ending in <code>0002</code> is declined; any other card is approved.</li>
<li><code>paypal</code>: an email ending in <code>@decline.test</code> is declined; any other email is approved.</li>
</ul>
<p>Every payment request needs an <code>Idempotency-Key</code> header. Retrying with the same key returns the
original payment (200) and never charges twice.</p>

                                <h2 id="payments-GETapi-v1-orders--order_id--payments">List an order&#039;s payments</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>All payment attempts for one of your orders, including failed ones.</p>

<span id="example-requests-GETapi-v1-orders--order_id--payments">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/orders/01a15fbc-af62-704f-8643-f69b490d1ea9/payments?page=1&amp;per_page=15" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/orders/01a15fbc-af62-704f-8643-f69b490d1ea9/payments"
);

const params = {
    "page": "1",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-orders--order_id--payments">
            <blockquote>
            <p>Example response (200, Success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Success&quot;,
    &quot;data&quot;: [
        {
            &quot;id&quot;: &quot;01a15fbc-b0c1-73f3-8faf-84d0b62179b8&quot;,
            &quot;order_id&quot;: &quot;01a15fbc-af62-704f-8643-f69b490d1ea9&quot;,
            &quot;payment_method&quot;: &quot;credit_card&quot;,
            &quot;status&quot;: &quot;successful&quot;,
            &quot;amount&quot;: &quot;59.97&quot;,
            &quot;transaction_id&quot;: &quot;cc_3f1c9b2e-7d4a-4c8e-9b1f-2a6d5e8c0f13&quot;,
            &quot;gateway_response&quot;: {
                &quot;gateway&quot;: &quot;credit_card&quot;,
                &quot;card_last4&quot;: &quot;1111&quot;,
                &quot;amount&quot;: &quot;59.97&quot;,
                &quot;currency&quot;: &quot;USD&quot;,
                &quot;message&quot;: &quot;Approved&quot;
            },
            &quot;created_at&quot;: &quot;2026-10-21T18:40:05.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-10-21T18:40:05.000000Z&quot;
        },
        {
            &quot;id&quot;: &quot;01a15fbc-b08a-72c6-97db-9031a7da2a08&quot;,
            &quot;order_id&quot;: &quot;01a15fbc-af62-704f-8643-f69b490d1ea9&quot;,
            &quot;payment_method&quot;: &quot;credit_card&quot;,
            &quot;status&quot;: &quot;failed&quot;,
            &quot;amount&quot;: &quot;59.97&quot;,
            &quot;transaction_id&quot;: null,
            &quot;gateway_response&quot;: {
                &quot;gateway&quot;: &quot;credit_card&quot;,
                &quot;card_last4&quot;: &quot;0002&quot;,
                &quot;amount&quot;: &quot;59.97&quot;,
                &quot;currency&quot;: &quot;USD&quot;,
                &quot;message&quot;: &quot;Card declined&quot;
            },
            &quot;created_at&quot;: &quot;2026-10-21T18:39:51.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-10-21T18:39:51.000000Z&quot;
        }
    ],
    &quot;current_page&quot;: 1,
    &quot;from&quot;: 1,
    &quot;last_page&quot;: 1,
    &quot;per_page&quot;: 15,
    &quot;to&quot;: 2,
    &quot;total&quot;: 2,
    &quot;links&quot;: {
        &quot;first&quot;: &quot;http://localhost:8000/api/v1/orders/01a15fbc-af62-704f-8643-f69b490d1ea9/payments?page=1&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/v1/orders/01a15fbc-af62-704f-8643-f69b490d1ea9/payments?page=1&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing or invalid token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Unauthenticated, please login first&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Someone else&#039;s order):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 403,
    &quot;message&quot;: &quot;You don&#039;t have permission to access this resource&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Order not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 404,
    &quot;message&quot;: &quot;Resource not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-orders--order_id--payments" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-orders--order_id--payments"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-orders--order_id--payments"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-orders--order_id--payments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-orders--order_id--payments">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-orders--order_id--payments" data-method="GET"
      data-path="api/v1/orders/{order_id}/payments"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-orders--order_id--payments', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-orders--order_id--payments"
                    onclick="tryItOut('GETapi-v1-orders--order_id--payments');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-orders--order_id--payments"
                    onclick="cancelTryOut('GETapi-v1-orders--order_id--payments');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-orders--order_id--payments"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/orders/{order_id}/payments</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-orders--order_id--payments"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-orders--order_id--payments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-orders--order_id--payments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>order_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="order_id"                data-endpoint="GETapi-v1-orders--order_id--payments"
               value="01a15fbc-af62-704f-8643-f69b490d1ea9"
               data-component="url">
    <br>
<p>The order ID (UUID). Example: <code>01a15fbc-af62-704f-8643-f69b490d1ea9</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-orders--order_id--payments"
               value="1"
               data-component="query">
    <br>
<p>Page number. Defaults to 1. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-orders--order_id--payments"
               value="15"
               data-component="query">
    <br>
<p>Items per page, 1 to 100. Defaults to 15. Example: <code>15</code></p>
            </div>
                </form>

                    <h2 id="payments-POSTapi-v1-orders--order_id--payments">Pay for an order</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Charges a <code>confirmed</code> order through the chosen gateway for the order's full total.
A declined payment is still saved (status <code>failed</code>, HTTP 201), and the order can be paid again with a new key.</p>
<p>The fields inside <code>details</code> depend on <code>payment_method</code>:</p>
<ul>
<li><code>credit_card</code>: <code>card_number</code>, <code>expiry_month</code>, <code>expiry_year</code>, <code>cvv</code>, <code>holder_name</code></li>
<li><code>paypal</code>: <code>paypal_email</code></li>
</ul>

<span id="example-requests-POSTapi-v1-orders--order_id--payments">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/orders/01a15fbc-af62-704f-8643-f69b490d1ea9/payments" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Idempotency-Key: 7f9c2e4a-1b3d-4e5f-8a6b-9c0d1e2f3a4b" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"payment_method\": \"credit_card\",
    \"details\": {
        \"card_number\": \"4111111111111111\",
        \"expiry_month\": 12,
        \"expiry_year\": 2030,
        \"cvv\": \"123\",
        \"holder_name\": \"Ali Hassan\"
    }
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/orders/01a15fbc-af62-704f-8643-f69b490d1ea9/payments"
);

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Idempotency-Key": "7f9c2e4a-1b3d-4e5f-8a6b-9c0d1e2f3a4b",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "payment_method": "credit_card",
    "details": {
        "card_number": "4111111111111111",
        "expiry_month": 12,
        "expiry_year": 2030,
        "cvv": "123",
        "holder_name": "Ali Hassan"
    }
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-orders--order_id--payments">
            <blockquote>
            <p>Example response (200, Retry with the same Idempotency-Key):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Payment completed successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01a15fbc-b0c1-73f3-8faf-84d0b62179b8&quot;,
        &quot;order_id&quot;: &quot;01a15fbc-af62-704f-8643-f69b490d1ea9&quot;,
        &quot;payment_method&quot;: &quot;credit_card&quot;,
        &quot;status&quot;: &quot;successful&quot;,
        &quot;amount&quot;: &quot;59.97&quot;,
        &quot;transaction_id&quot;: &quot;cc_3f1c9b2e-7d4a-4c8e-9b1f-2a6d5e8c0f13&quot;,
        &quot;gateway_response&quot;: {
            &quot;gateway&quot;: &quot;credit_card&quot;,
            &quot;card_last4&quot;: &quot;1111&quot;,
            &quot;amount&quot;: &quot;59.97&quot;,
            &quot;currency&quot;: &quot;USD&quot;,
            &quot;message&quot;: &quot;Approved&quot;
        },
        &quot;created_at&quot;: &quot;2026-10-21T18:40:05.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-10-21T18:40:05.000000Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (201, Paid):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 201,
    &quot;message&quot;: &quot;Payment completed successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01a15fbc-b0c1-73f3-8faf-84d0b62179b8&quot;,
        &quot;order_id&quot;: &quot;01a15fbc-af62-704f-8643-f69b490d1ea9&quot;,
        &quot;payment_method&quot;: &quot;credit_card&quot;,
        &quot;status&quot;: &quot;successful&quot;,
        &quot;amount&quot;: &quot;59.97&quot;,
        &quot;transaction_id&quot;: &quot;cc_3f1c9b2e-7d4a-4c8e-9b1f-2a6d5e8c0f13&quot;,
        &quot;gateway_response&quot;: {
            &quot;gateway&quot;: &quot;credit_card&quot;,
            &quot;card_last4&quot;: &quot;1111&quot;,
            &quot;amount&quot;: &quot;59.97&quot;,
            &quot;currency&quot;: &quot;USD&quot;,
            &quot;message&quot;: &quot;Approved&quot;
        },
        &quot;created_at&quot;: &quot;2026-10-21T18:40:05.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-10-21T18:40:05.000000Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (201, Declined):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 201,
    &quot;message&quot;: &quot;Payment was declined&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01a15fbc-b08a-72c6-97db-9031a7da2a08&quot;,
        &quot;order_id&quot;: &quot;01a15fbc-af62-704f-8643-f69b490d1ea9&quot;,
        &quot;payment_method&quot;: &quot;credit_card&quot;,
        &quot;status&quot;: &quot;failed&quot;,
        &quot;amount&quot;: &quot;59.97&quot;,
        &quot;transaction_id&quot;: null,
        &quot;gateway_response&quot;: {
            &quot;gateway&quot;: &quot;credit_card&quot;,
            &quot;card_last4&quot;: &quot;0002&quot;,
            &quot;amount&quot;: &quot;59.97&quot;,
            &quot;currency&quot;: &quot;USD&quot;,
            &quot;message&quot;: &quot;Card declined&quot;
        },
        &quot;created_at&quot;: &quot;2026-10-21T18:39:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-10-21T18:39:51.000000Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing or invalid token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Unauthenticated, please login first&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Someone else&#039;s order):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 403,
    &quot;message&quot;: &quot;You don&#039;t have permission to access this resource&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Order not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 404,
    &quot;message&quot;: &quot;Resource not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (409, Already paid):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 409,
    &quot;message&quot;: &quot;This order has already been paid&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Order not confirmed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 422,
    &quot;message&quot;: &quot;Payments can only be processed for confirmed orders&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 422,
    &quot;message&quot;: &quot;The idempotency key field is required.&quot;,
    &quot;error&quot;: {
        &quot;idempotency_key&quot;: [
            &quot;The idempotency key field is required.&quot;
        ],
        &quot;details.cvv&quot;: [
            &quot;The details.cvv field is required.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Unknown payment method):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 422,
    &quot;message&quot;: &quot;The selected payment method is invalid.&quot;,
    &quot;error&quot;: {
        &quot;payment_method&quot;: [
            &quot;The selected payment method is invalid.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-orders--order_id--payments" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-orders--order_id--payments"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-orders--order_id--payments"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-orders--order_id--payments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-orders--order_id--payments">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-orders--order_id--payments" data-method="POST"
      data-path="api/v1/orders/{order_id}/payments"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-orders--order_id--payments', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-orders--order_id--payments"
                    onclick="tryItOut('POSTapi-v1-orders--order_id--payments');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-orders--order_id--payments"
                    onclick="cancelTryOut('POSTapi-v1-orders--order_id--payments');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-orders--order_id--payments"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/orders/{order_id}/payments</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-orders--order_id--payments"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Idempotency-Key</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Idempotency-Key"                data-endpoint="POSTapi-v1-orders--order_id--payments"
               value="7f9c2e4a-1b3d-4e5f-8a6b-9c0d1e2f3a4b"
               data-component="header">
    <br>
<p>Example: <code>7f9c2e4a-1b3d-4e5f-8a6b-9c0d1e2f3a4b</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-orders--order_id--payments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-orders--order_id--payments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>order_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="order_id"                data-endpoint="POSTapi-v1-orders--order_id--payments"
               value="01a15fbc-af62-704f-8643-f69b490d1ea9"
               data-component="url">
    <br>
<p>The order ID (UUID). Example: <code>01a15fbc-af62-704f-8643-f69b490d1ea9</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>payment_method</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="payment_method"                data-endpoint="POSTapi-v1-orders--order_id--payments"
               value="credit_card"
               data-component="body">
    <br>
<p>One of the configured gateways: <code>credit_card</code> or <code>paypal</code>. Example: <code>credit_card</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>details</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>Gateway-specific fields.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>card_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="details.card_number"                data-endpoint="POSTapi-v1-orders--order_id--payments"
               value="4111111111111111"
               data-component="body">
    <br>
<p>13 to 19 digits. Required for <code>credit_card</code>. Example: <code>4111111111111111</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>expiry_month</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="details.expiry_month"                data-endpoint="POSTapi-v1-orders--order_id--payments"
               value="12"
               data-component="body">
    <br>
<p>1 to 12. Required for <code>credit_card</code>. Example: <code>12</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>expiry_year</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="details.expiry_year"                data-endpoint="POSTapi-v1-orders--order_id--payments"
               value="2030"
               data-component="body">
    <br>
<p>Not in the past. Required for <code>credit_card</code>. Example: <code>2030</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>cvv</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="details.cvv"                data-endpoint="POSTapi-v1-orders--order_id--payments"
               value="123"
               data-component="body">
    <br>
<p>3 or 4 digits. Required for <code>credit_card</code>. Example: <code>123</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>holder_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="details.holder_name"                data-endpoint="POSTapi-v1-orders--order_id--payments"
               value="Ali Hassan"
               data-component="body">
    <br>
<p>Required for <code>credit_card</code>. Example: <code>Ali Hassan</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>paypal_email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="details.paypal_email"                data-endpoint="POSTapi-v1-orders--order_id--payments"
               value=""
               data-component="body">
    <br>
<p>Required for <code>paypal</code>. Example:</p>
                    </div>
                                    </details>
        </div>
        </form>

                    <h2 id="payments-GETapi-v1-payments">List payments</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Payments across all your orders, newest first, paginated.</p>

<span id="example-requests-GETapi-v1-payments">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/payments?page=1&amp;per_page=15" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/payments"
);

const params = {
    "page": "1",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-payments">
            <blockquote>
            <p>Example response (200, Success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Success&quot;,
    &quot;data&quot;: [
        {
            &quot;id&quot;: &quot;01a15fbc-b0c1-73f3-8faf-84d0b62179b8&quot;,
            &quot;order_id&quot;: &quot;01a15fbc-af62-704f-8643-f69b490d1ea9&quot;,
            &quot;payment_method&quot;: &quot;credit_card&quot;,
            &quot;status&quot;: &quot;successful&quot;,
            &quot;amount&quot;: &quot;59.97&quot;,
            &quot;transaction_id&quot;: &quot;cc_3f1c9b2e-7d4a-4c8e-9b1f-2a6d5e8c0f13&quot;,
            &quot;gateway_response&quot;: {
                &quot;gateway&quot;: &quot;credit_card&quot;,
                &quot;card_last4&quot;: &quot;1111&quot;,
                &quot;amount&quot;: &quot;59.97&quot;,
                &quot;currency&quot;: &quot;USD&quot;,
                &quot;message&quot;: &quot;Approved&quot;
            },
            &quot;created_at&quot;: &quot;2026-10-21T18:40:05.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-10-21T18:40:05.000000Z&quot;
        }
    ],
    &quot;current_page&quot;: 1,
    &quot;from&quot;: 1,
    &quot;last_page&quot;: 1,
    &quot;per_page&quot;: 15,
    &quot;to&quot;: 1,
    &quot;total&quot;: 1,
    &quot;links&quot;: {
        &quot;first&quot;: &quot;http://localhost:8000/api/v1/payments?page=1&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/v1/payments?page=1&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing or invalid token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Unauthenticated, please login first&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-payments" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-payments"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-payments"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-payments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-payments">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-payments" data-method="GET"
      data-path="api/v1/payments"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-payments', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-payments"
                    onclick="tryItOut('GETapi-v1-payments');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-payments"
                    onclick="cancelTryOut('GETapi-v1-payments');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-payments"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/payments</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-payments"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-payments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-payments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-payments"
               value="1"
               data-component="query">
    <br>
<p>Page number. Defaults to 1. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-payments"
               value="15"
               data-component="query">
    <br>
<p>Items per page, 1 to 100. Defaults to 15. Example: <code>15</code></p>
            </div>
                </form>

                    <h2 id="payments-GETapi-v1-payments--id-">Show a payment</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>One payment that belongs to one of your orders.</p>

<span id="example-requests-GETapi-v1-payments--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/payments/01a15fbc-b0c1-73f3-8faf-84d0b62179b8" \
    --header "Authorization: Bearer {YOUR_JWT_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/payments/01a15fbc-b0c1-73f3-8faf-84d0b62179b8"
);

const headers = {
    "Authorization": "Bearer {YOUR_JWT_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-payments--id-">
            <blockquote>
            <p>Example response (200, Success):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 200,
    &quot;message&quot;: &quot;Success&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01a15fbc-b0c1-73f3-8faf-84d0b62179b8&quot;,
        &quot;order_id&quot;: &quot;01a15fbc-af62-704f-8643-f69b490d1ea9&quot;,
        &quot;payment_method&quot;: &quot;paypal&quot;,
        &quot;status&quot;: &quot;successful&quot;,
        &quot;amount&quot;: &quot;59.97&quot;,
        &quot;transaction_id&quot;: &quot;pp_a7619984-e5b5-4ad7-8ab7-00134378d34c&quot;,
        &quot;gateway_response&quot;: {
            &quot;gateway&quot;: &quot;paypal&quot;,
            &quot;payer_email&quot;: &quot;buyer@example.com&quot;,
            &quot;amount&quot;: &quot;59.97&quot;,
            &quot;currency&quot;: &quot;USD&quot;,
            &quot;message&quot;: &quot;Approved&quot;
        },
        &quot;created_at&quot;: &quot;2026-10-21T18:40:05.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-10-21T18:40:05.000000Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing or invalid token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 401,
    &quot;message&quot;: &quot;Unauthenticated, please login first&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Someone else&#039;s payment):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 403,
    &quot;message&quot;: &quot;You don&#039;t have permission to access this resource&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Payment not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: 404,
    &quot;message&quot;: &quot;Resource not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-payments--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-payments--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-payments--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-payments--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-payments--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-payments--id-" data-method="GET"
      data-path="api/v1/payments/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-payments--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-payments--id-"
                    onclick="tryItOut('GETapi-v1-payments--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-payments--id-"
                    onclick="cancelTryOut('GETapi-v1-payments--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-payments--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/payments/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-payments--id-"
               value="Bearer {YOUR_JWT_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_JWT_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-payments--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-payments--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="GETapi-v1-payments--id-"
               value="01a15fbc-b0c1-73f3-8faf-84d0b62179b8"
               data-component="url">
    <br>
<p>The payment ID (UUID). Example: <code>01a15fbc-b0c1-73f3-8faf-84d0b62179b8</code></p>
            </div>
                    </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
