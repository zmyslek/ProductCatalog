// Catalog home view: fetches and lists all categories.

import { apiGet } from '../api.js';

import { productCard } from '../components/productCard.js';

export async function renderCatalogView(outlet) {
    const [categoriesBody, productsBody] = await Promise.all([
        apiGet('/categories?per_page=100'),
        apiGet('/products?per_page=12'),
    ]);

    outlet.innerHTML = `
        <section class="space-y-8">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-pink-600">Personal gifts</p>
                <h1 class="mt-2 text-4xl font-bold text-slate-900">Find something special</h1>
                <p class="mt-3 max-w-2xl text-slate-600">Browse active products and explore each category.</p>
            </div>
            <section>
                <h2 class="mb-4 text-2xl font-bold">Categories</h2>
                <div class="flex flex-wrap gap-2">${categoriesBody.data.map(category => `
                    <a href="#/categories/${category.id}" class="rounded-full bg-slate-100 px-4 py-2 text-sm hover:bg-slate-200">${category.name} (${category.products_count})</a>
                `).join('')}</div>
            </section>
            <section>
                <h2 class="mb-4 text-2xl font-bold">Products</h2>
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">${productsBody.data.map(productCard).join('')}</div>
            </section>
        </section>`;
}
