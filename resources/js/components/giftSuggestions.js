// Reusable block that lists the LLM gift suggestions, or a message when none are available.

export function giftSuggestions(suggestions) {
    if (!suggestions) return '';
    const items = Array.isArray(suggestions) ? suggestions : suggestions.suggestions;
    if (!items || !items.length) {
        return '<p class="gift-empty">No gift suggestions yet.</p>';
    }
    return `
        <div class="suggestion-grid">
            ${items.map((item) => `
                <article class="suggestion-card">
                    <span>Gift idea</span>
                    <h3>${escapeHtml(typeof item === 'string' ? item : item.title || item.name)}</h3>
                </article>
            `).join('')}
        </div>
    `;
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, (character) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
    })[character]);
}
