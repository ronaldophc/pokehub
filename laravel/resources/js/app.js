import './bootstrap';
import pokemonAutocomplete from './components/pokemon-autocomplete';
import heldItemSelect from './components/held-item-select';
import tmSelect from './components/tm-select';

document.addEventListener('alpine:init', () => {
    Alpine.data('pokemonAutocomplete', pokemonAutocomplete);
    Alpine.data('heldItemSelect', heldItemSelect);
    Alpine.data('tmSelect', tmSelect);
});
