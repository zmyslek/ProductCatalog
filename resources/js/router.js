// Hash-based client-side router (#/, #/categories/:id, #/products/:id, #/cart) that renders the matching view without a page reload.

import { renderCatalogView } from './views/catalogView.js';
import { renderCategoryView } from './views/categoryView.js';
import { renderProductView } from './views/productView.js';
import { renderCartView } from './views/cartView.js';

export function startRouter() {
    const render = async () => {
        const outlet = document.querySelector('#route-outlet');
        const route = window.location.hash.replace(/^#/, '') || '/';
        outlet.innerHTML = '<p class="text-slate-500">Loading...</p>';

        try {
            if (route === '/' || route === '') {
                await renderCatalogView(outlet);
            } else if (route === '/cart') {
                await renderCartView(outlet);
            } else if (route.startsWith('/categories/')) {
                await renderCategoryView(outlet, route.split('/')[2]);
            } else if (route.startsWith('/products/')) {
                await renderProductView(outlet, route.split('/')[2]);
            } else {
                outlet.innerHTML = '<h1 class="text-2xl font-bold">Page not found</h1>';
            }
        } catch (error) {
            outlet.innerHTML = `<p class="rounded-lg bg-red-50 p-4 text-red-700">${error.message}</p>`;
        }
    };

    window.addEventListener('hashchange', render);
    render();
}
