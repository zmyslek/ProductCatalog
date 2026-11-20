// Reusable block that lists the LLM gift suggestions, or a message when none are available.

const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, character => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
}[character]));

export function giftSuggestions(suggestions = []) {
    if (!suggestions.length) {
        return '<p class="text-sm text-slate-500">No suggestions available.</p>';
    }

    return suggestions.map(suggestion => `
        <article class="rounded-xl border border-slate-200 bg-white p-3">
            <h3 class="font-semibold">${escapeHtml(suggestion.title)}</h3>
            <p class="mt-1 text-sm text-slate-600">${escapeHtml(suggestion.reason)}</p>
        </article>`).join('');
}
