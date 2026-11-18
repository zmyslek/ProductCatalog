// Base page layout (header, navigation, main outlet, footer) that the router renders views into.

export function renderLayout() {
    document.getElementById('app').innerHTML = `
        <header class="site-header">
            <div class="announcement">
                <span>Free shipping on personalized gifts over EUR 35</span>
                <span class="announcement-extra">Delivered with love</span>
            </div>
            <nav class="top-nav" aria-label="Main navigation">
                <a class="brand" href="#/" aria-label="YourSurprise home">
                    <span class="brand-mark" aria-hidden="true"><span></span></span>
                    <span class="brand-name">your<span>surprise</span></span>
                </a>
                <div class="nav-links">
                    <a href="#/">For every occasion</a>
                    <a href="#/">Discover gifts</a>
                    <a href="#/cart">Cart</a>
                </div>
                <div class="nav-actions">
                    <button class="icon-button" type="button" aria-label="Search" data-focus-search>
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6"></circle><path d="m16 16 4 4"></path></svg>
                    </button>
                    <a class="icon-button" href="#/cart" aria-label="Shopping cart">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 5h2l2.4 9.2a2 2 0 0 0 2 1.5h7.8a2 2 0 0 0 1.9-1.4L21 8H7"></path><circle cx="10" cy="19" r="1"></circle><circle cx="18" cy="19" r="1"></circle></svg>
                    </a>
                </div>
            </nav>
            <div id="search-panel" class="search-panel" hidden>
                <label for="catalog-search">What are you looking for?</label>
                <input id="catalog-search" type="search" placeholder="Mugs, photo gifts, birthdays...">
            </div>
        </header>
        <main id="main-outlet"></main>
        <footer class="site-footer">
            <div class="footer-inner">
                <a class="brand brand-light" href="#/">
                    <span class="brand-mark" aria-hidden="true"><span></span></span>
                    <span class="brand-name">your<span>surprise</span></span>
                </a>
                <p>Thoughtful gifts, made personal.</p>
                <div class="footer-links"><a href="#/">Catalog</a><a href="#/cart">My cart</a></div>
            </div>
        </footer>
    `;

    const searchButton = document.querySelector('[data-focus-search]');
    const searchPanel = document.getElementById('search-panel');
    searchButton?.addEventListener('click', () => {
        searchPanel.hidden = !searchPanel.hidden;
        if (!searchPanel.hidden) document.getElementById('catalog-search').focus();
    });
}
