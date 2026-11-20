// Reusable card showing a product's image, title and price in lists.

import { price } from './price.js';

const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, character => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
}[character]));

export function productCard(product) {
    return `
        <a href="#/products/${product.id}" class="block overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-1 hover:shadow-lg">
            <img src="${escapeHtml(product.image)}" alt="${escapeHtml(product.title)}" class="h-48 w-full object-cover">
            <div class="space-y-2 p-4">
                <h3 class="font-semibold">${escapeHtml(product.title)}</h3>
                <p class="text-lg text-slate-700">${price(product.discounted_price, product.price)}</p>
                <p class="text-sm text-slate-500">${escapeHtml(product.category?.name || 'Uncategorised')}</p>
            </div>
        </a>`;
}
