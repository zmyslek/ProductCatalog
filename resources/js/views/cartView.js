// Dynamic cart summary: the user enters a cart ID, the cart is fetched from the API and its items, discounted prices, totals and gift suggestions are shown.

import { apiGet } from '../api.js';
import { price } from '../components/price.js';
import { giftSuggestions } from '../components/giftSuggestions.js';

export function renderCartView() {
    const outlet = document.getElementById('main-outlet');
    outlet.innerHTML = `
        <section class="cart-page">
            <div class="cart-heading">
                <span class="eyebrow">Your shopping bag</span>
                <h1>My cart</h1>
            </div>
            <form class="cart-search" id="cart-form">
                <label for="cart-id">Cart number</label>
                <div>
                    <input id="cart-id" type="number" min="1" value="1" required>
                    <button class="primary-button" type="submit">View cart <span>→</span></button>
                </div>
            </form>
            <div id="cart-result" aria-live="polite"></div>
        </section>
    `;

    document.getElementById('cart-form').addEventListener('submit', (event) => {
        event.preventDefault();
        loadCart(document.getElementById('cart-id').value);
    });
    loadCart(document.getElementById('cart-id').value);
}

async function loadCart(id) {
    const result = document.getElementById('cart-result');
    result.innerHTML = '<div class="cart-loading"><div class="loading-spinner"></div></div>';
    try {
        const { data: cart } = await apiGet(`/carts/${id}`);
        const items = cart.items || [];
        const shipping = cart.total > 35 ? 0 : (items.length ? 4.95 : 0);
        result.innerHTML = `
            <div class="cart-layout reveal is-visible">
                <div class="cart-items">
                    <div class="cart-meta">
                        <span>Cart #${cart.id}</span>
                        ${cart.user ? `<span>For ${escapeHtml(cart.user.name)}</span>` : ''}
                    </div>
                    ${items.length ? items.map((item) => `
                        <article class="cart-item">
                            <div class="cart-item-art">${escapeHtml(item.product_name?.charAt(0) || 'G')}</div>
                            <div class="cart-item-info">
                                <span>Gift</span>
                                <h2>${escapeHtml(item.product_name)}</h2>
                                <p>Quantity ${item.quantity}</p>
                            </div>
                            <div class="cart-item-price">
                                ${item.discount_percentage > 0 ? `<small>-${Math.round(item.discount_percentage)}%</small>` : ''}
                                <strong>${price(item.final_price)}</strong>
                                ${item.unit_price != item.final_price ? `<del>${price(item.unit_price)}</del>` : ''}
                            </div>
                        </article>
                    `).join('') : '<div class="cart-empty"><span>♡</span><h2>Your bag is waiting</h2><p>Add a thoughtful gift to get started.</p><a class="primary-button" href="#/">Explore gifts</a></div>'}
                </div>
                ${items.length ? `
                    <aside class="cart-summary">
                        <h2>Order summary</h2>
                        <dl>
                            <div><dt>Subtotal</dt><dd>${price(cart.total)}</dd></div>
                            <div><dt>Shipping</dt><dd>${shipping === 0 ? 'Free' : price(shipping)}</dd></div>
                        </dl>
                        ${cart.total < 35 ? `<div class="free-shipping"><p>Add ${price(35 - cart.total)} for free shipping</p><div><span style="width:${Math.min(100, cart.total / 35 * 100)}%"></span></div></div>` : '<p class="free-shipping-success">You qualify for free shipping!</p>'}
                        <div class="summary-total"><span>Total</span><strong>${price(cart.total + shipping)}</strong></div>
                        <button class="primary-button checkout-button" type="button">Checkout <span>→</span></button>
                    </aside>
                ` : ''}
            </div>
            <section class="suggestion-section">
                <div class="section-heading"><div><span class="eyebrow">A little inspiration</span><h2>Gift suggestions</h2></div></div>
                <div id="gift-suggestions"><div class="loading-spinner small"></div></div>
            </section>
        `;
        loadGiftSuggestions(id);
    } catch (error) {
        result.innerHTML = `<div class="cart-empty error-state"><span>!</span><h2>Cart not found</h2><p>${escapeHtml(error.message)}</p></div>`;
    }
}

async function loadGiftSuggestions(id) {
    const result = document.getElementById('gift-suggestions');
    if (!result) return;
    try {
        const response = await apiGet(`/carts/${id}/gift-suggestions`);
        result.innerHTML = giftSuggestions(response.data || response);
    } catch {
        result.innerHTML = '<p class="gift-empty">Gift suggestions are taking a little break.</p>';
    }
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, (character) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
    })[character]);
}
