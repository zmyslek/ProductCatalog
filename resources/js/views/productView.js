// Product detail view: image, description, category name, price and discount, stock, shipping and warranty info.

import { apiGet } from '../api.js';
import { price } from '../components/price.js';

export function renderProductView(productId) {
    const outlet = document.getElementById('main-outlet');
    outlet.innerHTML = `
        <section class="detail-page">
            <div class="detail-loading"><div class="loading-spinner"></div></div>
        </section>
    `;

    apiGet(`/products/${productId}`).then((response) => {
        const product = response.data;
        const category = product.category?.name
            ? `<a class="detail-category" href="#/categories/${product.category.id}">${escapeHtml(product.category.name)}</a>`
            : '';
        const image = product.image
            ? `<img src="${escapeHtml(product.image)}" alt="${escapeHtml(product.title)}">`
            : `<div class="detail-placeholder"><span>your surprise</span></div>`;
        outlet.innerHTML = `
            <section class="detail-page">
                <a class="back-link" href="javascript:history.back()">← Back</a>
                <div class="detail-layout">
                    <div class="detail-gallery reveal is-visible">
                        ${Number(product.discount_percentage ?? 0) > 0 ? `<span class="discount-badge detail-discount">-${Math.round(product.discount_percentage)}%</span>` : ''}
                        ${image}
                    </div>
                    <div class="detail-info reveal is-visible">
                        ${category}
                        <h1>${escapeHtml(product.title)}</h1>
                        <div class="detail-price">${price(product)}</div>
                        ${product.rating ? `<div class="rating"><span>★★★★★</span> <b>${Number(product.rating).toFixed(1)}</b></div>` : ''}
                        <p class="detail-description">${escapeHtml(product.description || 'A thoughtful gift, ready to make someone smile.')}</p>
                        <div class="detail-stock ${Number(product.stock) > 0 ? '' : 'out'}">
                            <span></span>${Number(product.stock) > 0 ? `In stock · ${product.stock} available` : 'Currently out of stock'}
                        </div>
                        ${Number(product.stock) > 0 ? `<button class="primary-button detail-add" type="button">Choose this gift <span>→</span></button>` : ''}
                        <a class="cart-link" href="#/cart">View shopping cart</a>
                        <dl class="detail-specs">
                            ${product.brand ? `<div><dt>Brand</dt><dd>${escapeHtml(product.brand)}</dd></div>` : ''}
                            ${product.sku ? `<div><dt>SKU</dt><dd>${escapeHtml(product.sku)}</dd></div>` : ''}
                            ${product.shippingInformation ? `<div><dt>Delivery</dt><dd>${escapeHtml(product.shippingInformation)}</dd></div>` : ''}
                            ${product.warrantyInformation ? `<div><dt>Warranty</dt><dd>${escapeHtml(product.warrantyInformation)}</dd></div>` : ''}
                            ${product.returnPolicy ? `<div><dt>Returns</dt><dd>${escapeHtml(product.returnPolicy)}</dd></div>` : ''}
                        </dl>
                    </div>
                </div>
            </section>
        `;
    }).catch((error) => {
        outlet.innerHTML = `
            <section class="detail-page">
                <a class="back-link" href="#/">← Back to gifts</a>
                <p class="error-message">${escapeHtml(error.message)}</p>
            </section>
        `;
    });
}
