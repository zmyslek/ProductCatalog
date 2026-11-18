// Catalog home view: fetches and lists all categories.

import { apiGet } from '../api.js';

export function renderCatalogView() {
    const outlet = document.getElementById('main-outlet');
    outlet.innerHTML = `
        <section class="hero">
            <div class="hero-blob blob-one"></div>
            <div class="hero-blob blob-two"></div>
            <div class="hero-inner">
                <div class="hero-copy reveal">
                    <span class="eyebrow">Create moments that matter</span>
                    <h1>A little gift.<br><em>A lot of love.</em></h1>
                    <p>Personal, unexpected and made especially for the people you love.</p>
                    <button class="primary-button" type="button" data-scroll-categories>Explore gifts <span>→</span></button>
                </div>
                <div class="hero-art reveal" aria-hidden="true">
                    <div class="orbit orbit-one"></div>
                    <div class="orbit orbit-two"></div>
                    <div class="gift-box">
                        <div class="ribbon ribbon-v"></div>
                        <div class="ribbon ribbon-h"></div>
                        <div class="bow"><i></i><i></i></div>
                    </div>
                    <span class="floating-note note-one">made for you</span>
                    <span class="floating-heart">♥</span>
                </div>
            </div>
        </section>
        <section class="category-section" id="categories">
            <div class="section-heading">
                <div><span class="eyebrow">Find their perfect gift</span><h2>Shop by category</h2></div>
            </div>
            <div id="category-grid" class="category-grid skeleton-grid"></div>
        </section>
        <section class="featured-section">
            <div class="section-heading">
                <div><span class="eyebrow">Popular right now</span><h2>Gifts they'll love</h2></div>
                <a class="text-link" href="#/categories/1">View all <span>→</span></a>
            </div>
            <div id="featured-grid" class="product-grid skeleton-grid"></div>
        </section>
    `;

    document.querySelector('[data-scroll-categories]').addEventListener('click', () => {
        document.getElementById('categories').scrollIntoView({ behavior: 'smooth' });
    });

    apiGet('/categories?per_page=8').then((response) => {
        const colors = ['sun', 'rose', 'blue', 'green', 'lavender', 'peach', 'mint', 'yellow'];
        document.getElementById('category-grid').innerHTML = response.data.map((category, index) => `
            <a class="category-card category-${colors[index % colors.length]} reveal" href="#/categories/${category.id}">
                <span class="category-icon" aria-hidden="true">${['✦','☕','♥','★','☀','◆','♪','☁'][index % 8]}</span>
                <span class="category-name">${escapeHtml(category.name)}</span>
                <span class="category-count">${category.products_count || 0} gifts</span>
                <span class="category-arrow">→</span>
            </a>
        `).join('');
        observeReveals();
    }).catch(showCategoriesError);

    apiGet('/products?per_page=8').then((response) => {
        document.getElementById('featured-grid').innerHTML =
            response.data.map((product) => productCard(product)).join('');
        observeReveals();
    }).catch(() => {
        document.getElementById('featured-grid').innerHTML = '<p class="empty-message">Our gifts could not be loaded.</p>';
    });
}

import { productCard } from '../components/productCard.js';

function showCategoriesError() {
    document.getElementById('category-grid').innerHTML =
        '<p class="empty-message">Categories could not be loaded.</p>';
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, (character) => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
    })[character]);
}

function observeReveals() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });
    document.querySelectorAll('.reveal:not(.is-visible)').forEach((element) => observer.observe(element));
}
