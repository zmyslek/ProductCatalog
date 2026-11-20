// Reusable price display: shows the original price struck through next to the discounted price when there is a discount.

export function price(value, originalValue = null) {
    const formatted = new Intl.NumberFormat('en-GB', {
        style: 'currency',
        currency: 'EUR',
    }).format(Number(value));
    const original = originalValue !== null && Number(originalValue) > Number(value)
        ? `<s class="text-slate-400">${new Intl.NumberFormat('en-GB', { style: 'currency', currency: 'EUR' }).format(Number(originalValue))}</s> `
        : '';

    return `${original}<strong>${formatted}</strong>`;
}
