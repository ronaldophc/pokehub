import './bootstrap';
import pokemonAutocomplete from './components/pokemon-autocomplete';
import heldItemSelect from './components/held-item-select';

document.addEventListener('alpine:init', () => {
    Alpine.data('pokemonAutocomplete', pokemonAutocomplete);
    Alpine.data('heldItemSelect', heldItemSelect);
});
