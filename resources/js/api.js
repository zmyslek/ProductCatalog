// Small fetch wrapper around the Laravel REST API (/api/...): categories, products, carts and gift suggestions.

export async function apiGet(path) {
    const response = await fetch(`/api${path}`, {
        headers: { Accept: 'application/json' },
    });
    const body = await response.json().catch(() => null);
    if (!response.ok) {
        throw new Error(body?.message || 'The requested information could not be loaded.');
    }
    return body;
}
