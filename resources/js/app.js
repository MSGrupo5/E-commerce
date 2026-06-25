import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('searchSuggest', (suggestUrl, initialQuery = '') => ({
    query: initialQuery,
    suggestions: [],
    show: false,
    highlighted: -1,
    timer: null,

    search() {
        clearTimeout(this.timer);
        const term = this.query.trim();

        if (term.length < 2) {
            this.suggestions = [];
            this.show = false;
            return;
        }

        this.timer = setTimeout(() => {
            fetch(`${suggestUrl}?search=${encodeURIComponent(term)}`)
                .then((response) => response.json())
                .then((data) => {
                    this.suggestions = data;
                    this.show = data.length > 0;
                    this.highlighted = -1;
                });
        }, 250);
    },

    move(direction) {
        if (!this.suggestions.length) return;
        this.highlighted = (this.highlighted + direction + this.suggestions.length) % this.suggestions.length;
    },

    chooseHighlighted() {
        const item = this.suggestions[this.highlighted];
        if (item) window.location.href = item.url;
    },

    close() {
        this.show = false;
    },
}));

Alpine.start();
