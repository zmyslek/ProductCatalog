// Product detail view: image, description, category name, price and discount, stock, shipping and warranty info.

import { apiGet } from '../api.js';
import { price } from '../components/price.js';

const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, character => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
}[character]));

export async function renderProductView(outlet, id) {
    const body = await apiGet(`/products/${encodeURIComponent(id)}`);
    const product = body.data;

    outlet.innerHTML = `
        <article class="grid gap-8 md:grid-cols-2">
            <img src="${escapeHtml(product.image)}" alt="${escapeHtml(product.title)}" class="max-h-[520px] w-full rounded-2xl object-cover">
            <div class="space-y-5">
                <a href="#/" class="text-sm text-slate-500 hover:text-slate-900">&larr; All products</a>
                <div><p class="text-sm text-slate-500">${escapeHtml(product.category?.name || 'Uncategorised')}</p><h1 class="mt-2 text-4xl font-bold">${escapeHtml(product.title)}</h1></div>
                <p class="text-2xl text-slate-700">${price(product.discounted_price, product.price)}</p>
                <p class="leading-7 text-slate-600">${escapeHtml(product.description)}</p>
                <dl class="grid grid-cols-2 gap-3 text-sm">
                    <div><dt class="text-slate-500">Stock</dt><dd class="font-semibold">${product.stock}</dd></div>
                    <div><dt class="text-slate-500">Rating</dt><dd class="font-semibold">${product.rating}</dd></div>
                    <div><dt class="text-slate-500">Shipping</dt><dd class="font-semibold">${escapeHtml(product.shippingInformation || 'Not specified')}</dd></div>
                    <div><dt class="text-slate-500">Returns</dt><dd class="font-semibold">${escapeHtml(product.returnPolicy || 'Not specified')}</dd></div>
                </dl>
            </div>
        </article>`;
}
