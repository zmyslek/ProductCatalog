// Reusable price display: shows the original price struck through next to the discounted price when there is a discount.

export function price(productOrPrice, discountPercentage = 0) {
    const original = typeof productOrPrice === 'object'
        ? Number(productOrPrice.price)
        : Number(productOrPrice);
    const discount = typeof productOrPrice === 'object'
        ? Number(productOrPrice.discount_percentage ?? 0)
        : Number(discountPercentage ?? 0);
    const discounted = discount > 0 ? original * (1 - discount / 100) : original;
    const format = (value) => `EUR ${value.toFixed(2).replace('.', ',')}`;

    return discount > 0
        ? `<span class="price-current">${format(discounted)}</span><span class="price-old">${format(original)}</span>`
        : `<span class="price-current">${format(original)}</span>`;
}
