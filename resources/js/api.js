// Small fetch wrapper around the Laravel REST API (/api/...): categories, products, carts and gift suggestions.

export async function apiGet(path) {
    const response = await fetch(`/api${path}`, {
        headers: { Accept: 'application/json' },
    });
    const responseText = await response.text();
    let body;

    try {
        body = responseText ? JSON.parse(responseText) : {};
    } catch {
        throw new Error('The API returned an invalid response.');
    }

    if (!response.ok) {
        throw new Error(body.message || `Request failed (${response.status})`);
    }

    return body;
}
