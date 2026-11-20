# YourSurprise application assessment

Source code: https://github.com/zmyslek/ProductCatalog

The database file can be found in the provided database repository:
https://github.com/YourSurpriseCom/database-test

## Backend System (PHP REST API) 
Develop a PHP application using a framework of your choice that connects to the 
yoursurprise_test.db database and exposes a RESTful API, the API must allow for basic 
interaction with the product catalog: 
 
●  Catalog Browsing: Implement a mechanism to retrieve a paginated list of currently 
active products (active = 1). 
●  Product Detail: Implement a mechanism to retrieve the full details of any product, 
including a clear representation of its associated category name. 
●  Cart Retrieval: Provide an endpoint that, given a Cart ID, returns a detailed view of all 
items in that cart. The response should include the product name, quantity, and the final 
calculated price for each item (after applying its discount). 
 
## Frontend Visualization (JavaScript Application) 
Create a single-page frontend application using JavaScript (and a library/framework/toolchain of 
your choice) that consumes your custom REST API. 
Dynamic Cart Summary: 
●  Goal: Provide a live, detailed view of a specific shopping cart. 
●  Requirement: Allow a user to input a Cart ID and dynamically fetch and display the cart 
contents. 
## AI Integration (Bonus/Challenge) 
This assignment requires you to leverage the power of an external Large Language Model (e.g., 
OpenAI, Gemini) and combine it with your application. 
 
## Submission and Evaluation 
1.  Source Code: Provide a link to a GitHub repository with all source code. Include a 
clear README.md with instructions for: 
○  Installation and setup of the application. 
○  Database connection and initialization (using the provided .db file). 
○  How to run the frontend application. 
2.  Presentation (30 minutes): Make clear how you went about it and explain the different 
choices you made. Not necessarily a powerpoint presentation ;).. We would rather have 
you opening the code and walking us through.

## Local setup

The provided SQLite database is included at
`../database-test/yoursurprise_test.db` relative to the `ProductCatalog`
directory. Do not replace it with a newly migrated Laravel database.

```powershell
cd ProductCatalog
Copy-Item .env.example .env
composer install
npm install
npm run build
php artisan key:generate
php artisan serve
```

Set `DB_DATABASE` in `.env` to the absolute path of
`database-test/yoursurprise_test.db`. For example:

```dotenv
DB_CONNECTION=sqlite
DB_DATABASE=C:\path\to\YourSurprise-test\database-test\yoursurprise_test.db
```

Alternatively, when running commands from `ProductCatalog`, the relative
value in `.env.example` resolves to the same database:

```dotenv
DB_DATABASE=../database-test/yoursurprise_test.db
```

Verify that the database file exists before starting the application:

```powershell
Test-Path ..\database-test\yoursurprise_test.db
```

The command should return `True`.

## Frontend

The frontend is a JavaScript single-page interface served by Laravel. It does
not require a separate frontend server:

- Home: http://localhost:8000/
- Product catalogue: http://localhost:8000/products
- Product details: http://localhost:8000/products/{id}
- Cart lookup: http://localhost:8000/cart

Run `npm run build` after changing frontend assets. The frontend calls the
Laravel API under `/api`.

## REST API examples

List active products with pagination:

```http
GET http://localhost:8000/api/products?per_page=10
Accept: application/json
```

Retrieve full product details, including the category name:

```http
GET http://localhost:8000/api/products/1
Accept: application/json
```

Retrieve a cart with product names, quantities, discounted line prices, and
the cart total:

```http
GET http://localhost:8000/api/carts/1
Accept: application/json
```

The cart response contains `items[].product_name`, `items[].quantity`,
`items[].discount_percentage`, `items[].final_price`, and `total`.

Gift suggestions are available through the bonus endpoint:

```http
GET http://localhost:8000/api/carts/1/gift-suggestions
Accept: application/json
```

Set `GEMINI_API_KEY` in `.env` to enable this endpoint. If the provider is
not configured or returns invalid data, the API responds with HTTP 503 and
logs the provider failure; the cart itself remains available.

## Architecture

- Laravel provides the REST API and serves the frontend pages.
- Eloquent models map the provided singular SQLite tables, including `product`,
  `category`, `cart`, and `cart_items`.
- API resources define the JSON representation returned to the frontend.
- `GiftSuggestionService` builds a cart-based prompt and uses the `LlmClient`
  abstraction with Gemini as the configured provider.
- The browser communicates with Laravel using JSON requests under `/api`.

## Manual frontend verification

1. Start the application with `php artisan serve`.
2. Open http://localhost:8000/cart.
3. Confirm that available cart IDs are loaded.
4. Enter a valid Cart ID and confirm that product name, quantity, unit price,
   discount, final price, and total are displayed.
5. Enter an unavailable Cart ID and confirm that a clear validation message is
   shown.
6. With `GEMINI_API_KEY` configured, confirm that gift suggestions appear.
   Without a configured provider, confirm that the cart remains visible and the
   suggestions area reports the temporary unavailability.

For Docker, use `docker compose up --build`; the compose file mounts the database at
`/data/yoursurprise_test.db`. Do not commit `.env` or API keys.

Run the automated checks with:

```powershell
php artisan test
npm run build
```

## SonarQube

From the workspace root, start PostgreSQL and SonarQube:

```powershell
docker compose -f compose.sonar.yml up -d
```

Open `http://localhost:9000`. The project key is `YourSurprise_ProductCatalog`.

Create a SonarQube token and run the configured scanner:

```powershell
docker run --rm --network yoursurprise-sonar_default `
  -v "${PWD}/ProductCatalog:/usr/src" `
  sonarsource/sonar-scanner-cli:latest `
  -Dsonar.host.url=http://sonarqube:9000 `
  -Dsonar.token=<token> `
  -Dsonar.projectBaseDir=/usr/src
```