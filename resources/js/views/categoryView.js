// Category view: fetches and lists the (paginated) active products in one category.

import { apiGet } from '../api.js';
import { productCard } from '../components/productCard.js';

const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, character => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
}[character]));

export async function renderCategoryView(outlet, id) {
    const body = await apiGet(`/categories/${encodeURIComponent(id)}`);
    const category = body.data;

    outlet.innerHTML = `
        <section class="space-y-6">
            <a href="#/" class="text-sm text-slate-500 hover:text-slate-900">&larr; All products</a>
            <div><h1 class="text-4xl font-bold">${escapeHtml(category.name)}</h1><p class="mt-2 text-slate-600">${category.products?.length || 0} products</p></div>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">${(category.products || []).map(productCard).join('')}</div>
        </section>`;
}
