// Dynamic cart summary: the user enters a cart ID, the cart is fetched from the API and its items, discounted prices, totals and gift suggestions are shown.

import { apiGet } from '../api.js';
import { price } from '../components/price.js';
import { giftSuggestions } from '../components/giftSuggestions.js';

const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, character => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
}[character]));

export async function renderCartView(outlet) {
    outlet.innerHTML = `
        <section class="mx-auto max-w-3xl space-y-6">
            <div><h1 class="text-4xl font-bold">Look up a cart</h1><p class="mt-2 text-slate-600">Enter a cart ID to see its items and gift suggestions.</p></div>
            <form id="cart-form" class="flex gap-3">
                <label class="sr-only" for="cart-id">Cart ID</label>
                <input id="cart-id" type="number" min="1" required class="min-w-0 flex-1 rounded-lg border border-slate-300 px-4 py-3" placeholder="Cart ID">
                <button class="rounded-lg bg-slate-900 px-5 py-3 font-semibold text-white">Load cart</button>
            </form>
            <div id="cart-result"></div>
            <aside><h2 class="mb-3 text-2xl font-bold">Gift suggestions</h2><div id="gift-suggestions"></div></aside>
        </section>`;

    outlet.querySelector('#cart-form').addEventListener('submit', async event => {
        event.preventDefault();
        const id = outlet.querySelector('#cart-id').value;
        const result = outlet.querySelector('#cart-result');
        const suggestions = outlet.querySelector('#gift-suggestions');
        result.innerHTML = '<p class="text-slate-500">Loading...</p>';
        suggestions.innerHTML = '';

        try {
            const body = await apiGet(`/carts/${encodeURIComponent(id)}`);
            const cart = body.data;
            result.innerHTML = `
                <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50"><tr><th class="p-3">Product</th><th class="p-3">Quantity</th><th class="p-3">Final price</th></tr></thead>
                        <tbody>${cart.items.map(item => `<tr class="border-t"><td class="p-3">${escapeHtml(item.product_name)}</td><td class="p-3">${item.quantity}</td><td class="p-3">${price(item.final_price)}</td></tr>`).join('')}</tbody>
                        <tfoot><tr class="border-t font-bold"><td class="p-3" colspan="2">Total</td><td class="p-3">${price(cart.total)}</td></tr></tfoot>
                    </table>
                </div>`;

            try {
                const giftBody = await apiGet(`/carts/${encodeURIComponent(id)}/gift-suggestions`);
                suggestions.innerHTML = giftSuggestions(giftBody.data.suggestions);
            } catch (error) {
                suggestions.innerHTML = `<p class="text-sm text-slate-500">${error.message}</p>`;
            }
        } catch (error) {
            result.innerHTML = `<p class="rounded-lg bg-red-50 p-4 text-red-700">${error.message}</p>`;
        }
    });
}
