export default (initialSpecies, spriteField = null) => ({
    search: initialSpecies || '',
    spriteField,
    results: [],
    open: false,
    loading: false,
    highlighted: 0,

    init() {
        this.$wire.$watch('form.species', v => { this.search = v || ''; });

        if (window._pkCache) return;

        this.loading = true;
        fetch('/pokemons.json')
            .then(r => r.json())
            .then(data => {
                window._pkCache = data;
                this.loading = false;
            })
            .catch(() => { this.loading = false; });
    },

    filter() {
        if (!window._pkCache || !this.search || this.search.length < 2) {
            this.open = false;
            return;
        }
        const q = this.search.toLowerCase();
        this.results = window._pkCache
            .filter(p => p.l.toLowerCase().includes(q) || p.v.includes(q.replace(/ /g, '-')))
            .slice(0, 10);
        this.open = this.results.length > 0;
        this.highlighted = 0;
    },

    select(item) {
        this.search = item.l;
        this.$wire.set('form.species', item.l);
        if (this.spriteField) {
            this.$wire.set(this.spriteField, item.sprite || `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${item.id}.png`);
        }
        this.open = false;
    },

    highlightNext() { this.highlighted = Math.min(this.highlighted + 1, this.results.length - 1); },
    highlightPrev() { this.highlighted = Math.max(this.highlighted - 1, 0); },
    selectHighlighted() { if (this.results[this.highlighted]) this.select(this.results[this.highlighted]); },
});
