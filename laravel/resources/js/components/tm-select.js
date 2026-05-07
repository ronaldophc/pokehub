const TM_MAP = {
    "mega ampharos electric": "TM Tank",
    "tangrowth":              "TM Tank",
    "mega ampharos dragon":   "TM Tank",
    "shiny copperajah":       "TM Tank",
    "shiny granbull":         "TM Tank",
    "shiny torkoal":          "TM Tank",
    "mega sableye":           "TM Tank",
    "rhyperior":              "TM Tank",
    "conkeldurr":             "TM Tank",
    "shiny blastoise":        "TM Tank",

    "shiny raichu":           "TM DPS",
    "shiny venusaur":         "TM DPS",
    "shiny venomoth":         "TM DPS",
    "shiny pidgeot":          "TM DPS",
    "mega altaria dragon":    "TM DPS",
    "mega lucario steel":     "TM DPS",
    "mega gardevoir":         "TM DPS",
    "mega alakazam":          "TM DPS",
    "shiny charizard":        "TM DPS",
    "shiny honchkrow":        "TM DPS",
    "shiny tentacruel":       "TM DPS",
    "shiny marowak":          "TM DPS",
    "shiny magcargo":         "TM DPS",
    "shiny machamp":          "TM DPS",
    "shiny feraligatr":       "TM DPS",
    "alolan ninetales":       "TM DPS",

    "electivire":             "TM Burst",
    "shiny ariados":          "TM Burst",
    "shiny dodrio":           "TM Burst",
    "shiny magneton":         "TM Burst",
    "metagross":              "TM Burst",
    "magmortar":              "TM Burst",
    "dusknoir":               "TM Burst",
    "shiny rhydon":           "TM Burst",
    "mega kangaskhan":        "TM Burst",
    "shiny politoed":         "TM Burst",

    "shiny ampharos":         "TM Off-Tank",
    "shiny meganium":         "TM Off-Tank",
    "mega charizard x":       "TM Off-Tank",
    "mega metagross":         "TM Off-Tank",
    "mega altaria fairy":     "TM Off-Tank",
    "alolan marowak":         "TM Off-Tank",
    "shiny muk":              "TM Off-Tank",
    "mega aerodactyl":        "TM Off-Tank",
    "mega lucario fighting":  "TM Off-Tank",
    "milotic":                "TM Off-Tank",
};

const ALL_TMS = ['TM Tank', 'TM DPS', 'TM Burst', 'TM Off-Tank'];

export default (initialSpecies, initialShiny) => ({
    match: null,
    hasSpecies: !!initialSpecies,

    get availableOptions() {
        return this.match ? [this.match] : ALL_TMS;
    },

    init() {
        this.$wire.$watch('form.species', (v) => {
            this.detect(v, this.$wire.form.isShiny);
        });
        this.$wire.$watch('form.isShiny', (v) => {
            this.detect(this.$wire.form.species, v);
        });
        this.detect(initialSpecies, initialShiny);
    },

    detect(species, isShiny) {
        this.hasSpecies = !!species;
        if (!species) { this.match = null; return; }
        const base = species.toLowerCase().trim();
        const withShiny = isShiny ? 'shiny ' + base : base;
        this.match = TM_MAP[withShiny] ?? TM_MAP[base] ?? null;
    },
});
