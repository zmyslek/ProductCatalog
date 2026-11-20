// Base page layout (header, navigation, main outlet, footer) that the router renders views into.

export function renderLayout() {
    document.querySelector('#app').innerHTML = `
        <div class="site-shell">
            <div class="top-strip">Made personal, made with love <span>|</span> Free delivery from €50</div>
            <header class="site-header">
                <nav class="site-nav">
                    <a href="#/" class="brand">
                        <span class="brand-mark" aria-hidden="true">♡</span>
                        <span>YourSurprise</span>
                    </a>
                    <div class="site-links">
                        <a href="#/">All gifts</a>
                        <a href="#/cart">My cart</a>
                        <a href="#/cart" aria-label="Go to cart">🛒</a>
                    </div>
                </nav>
            </header>
            <main id="route-outlet" class="site-main"></main>
            <footer class="site-footer">
                <strong>YourSurprise</strong>
                <span>For all life's special moments.</span>
            </footer>
        </div>`;

    return document.querySelector('#route-outlet');
}
