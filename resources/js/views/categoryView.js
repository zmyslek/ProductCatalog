// Category view: fetches and lists the (paginated) active products in one category.

import { apiGet } from '../api.js';
import { productCard } from '../components/productCard.js';

export function renderCategoryView(categoryId) {
    const outlet = document.getElementById('main-outlet');
    outlet.innerHTML = `
        <section class="collection-page">
            <div class="collection-heading">
                <a class="back-link" href="#/">← Back to all gifts</a>
                <span class="eyebrow">Your surprise</span>
                <h1 id="category-title">Loading gifts...</h1>
            </div>
            <div class="catalog-toolbar">
                <span id="product-count">0 gifts</span>
                <label>Sort
                    <select id="sort-products">
                        <option value="default">Recommended</option>
                        <option value="price-low">Price: low to high</option>
                        <option value="price-high">Price: high to low</option>
                    </select>
                </label>
            </div>
            <div id="category-products" class="product-grid"><div class="loading-spinner"></div></div>
        </section>
    `;

    apiGet(`/categories/${categoryId}`).then((response) => {
        const category = response.data;
        document.getElementById('category-title').textContent = category.name;
        let products = category.products || [];
        document.getElementById('product-count').textContent = `${products.length} gift${products.length === 1 ? '' : 's'}`;
        const renderProducts = () => {
            document.getElementById('category-products').innerHTML = products.length
                ? products.map((product) => productCard(product)).join('')
                : '<p class="empty-message">No active gifts in this category yet.</p>';
        };
        renderProducts();
        document.getElementById('sort-products').addEventListener('change', (event) => {
            if (event.target.value === 'price-low') products = [...products].sort((a, b) => a.discounted_price - b.discounted_price);
            if (event.target.value === 'price-high') products = [...products].sort((a, b) => b.discounted_price - a.discounted_price);
            if (event.target.value === 'default') products = category.products || [];
            renderProducts();
        });
    }).catch((error) => {
        document.getElementById('category-title').textContent = 'Category unavailable';
        document.getElementById('category-products').innerHTML = `<p class="error-message">${escapeHtml(error.message)}</p>`;
    });
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, (character) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
    })[character]);
}
