// Hash-based client-side router (#/, #/categories/:id, #/products/:id, #/cart) that renders the matching view without a page reload.

import { renderCatalogView } from './views/catalogView.js';
import { renderCategoryView } from './views/categoryView.js';
import { renderProductView } from './views/productView.js';
import { renderCartView } from './views/cartView.js';

export function startRouter() {
    const routes = [
        { pattern: /^#\/categories\/(\d+)\/?$/, render: (_, id) => renderCategoryView(id) },
        { pattern: /^#\/products\/(\d+)\/?$/, render: (_, id) => renderProductView(id) },
        { pattern: /^#\/cart\/?$/, render: () => renderCartView() },
        { pattern: /^#\/?$/, render: () => renderCatalogView() },
    ];

    const navigate = () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
        const hash = window.location.hash || '#/';
        const route = routes.find(({ pattern }) => pattern.test(hash));
        if (route) {
            const match = hash.match(route.pattern);
            route.render(...match.slice(0));
        } else {
            renderCatalogView();
        }
    };

    window.addEventListener('hashchange', navigate);
    navigate();
}
