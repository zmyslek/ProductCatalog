// Reusable card showing a product's image, title and price in lists.

import { price } from './price.js';

export function productCard(product) {
    const image = product.image
        ? `<img src="${escapeHtml(product.image)}" alt="${escapeHtml(product.title)}" loading="lazy">`
        : `<div class="product-placeholder" aria-hidden="true"><span>your surprise</span></div>`;
    const discount = Number(product.discount_percentage ?? 0);

    return `
        <article class="product-card reveal">
            <a class="product-card-image" href="#/products/${product.id}">
                ${discount > 0 ? `<span class="discount-badge">-${Math.round(discount)}%</span>` : ''}
                ${image}
            </a>
            <div class="product-card-body">
                ${product.category?.name ? `<span class="product-category">${escapeHtml(product.category.name)}</span>` : ''}
                <a class="product-title" href="#/products/${product.id}">${escapeHtml(product.title)}</a>
                <div class="product-card-footer">
                    <div class="price">${price(product)}</div>
                    <a class="card-arrow" href="#/products/${product.id}" aria-label="View ${escapeHtml(product.title)}">→</a>
                </div>
            </div>
        </article>
    `;
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, (character) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
    })[character]);
}
