export default (initialItem, tiers, wireNameField, wireTierField) => ({
    item: initialItem || '',
    tiers: tiers,

    get availableTiers() {
        return this.tiers[this.item] ?? [];
    },

    init() {
        this.$watch('item', v => {
            const t = this.tiers[v] ?? [];
            this.$wire.set(wireTierField, t.length === 1 ? t[0] : null);
            this.$wire.set(wireNameField, v);
        });
    },
});
