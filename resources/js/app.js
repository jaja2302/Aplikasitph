import L from 'leaflet';
import 'leaflet.markercluster';
import jQuery from 'jquery';
import 'leaflet-editable';
// Import CSS
import 'leaflet/dist/leaflet.css';
import 'leaflet.markercluster/dist/MarkerCluster.css';
import 'leaflet.markercluster/dist/MarkerCluster.Default.css';

// Import icon images
import iconRetinaUrl from 'leaflet/dist/images/marker-icon-2x.png';
import iconUrl from 'leaflet/dist/images/marker-icon.png';
import shadowUrl from 'leaflet/dist/images/marker-shadow.png';
import 'leaflet-search/dist/leaflet-search.min.css';
import 'leaflet-search';

// Fix untuk icon Leaflet
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl,
    iconUrl,
    shadowUrl,
});

// Make Leaflet available globally
window.L = L;
window.$ = jQuery;

// Global loader functions
window.showLoader = function() {
    const loader = document.getElementById('loader');
    if (loader) {
        loader.style.display = 'flex';
    }
};

window.hideLoader = function() {
    const loader = document.getElementById('loader');
    if (loader) {
        loader.style.display = 'none';
    }
};
