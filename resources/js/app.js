import L from 'leaflet';
import 'leaflet.markercluster';

// Import CSS
import 'leaflet/dist/leaflet.css';
import 'leaflet.markercluster/dist/MarkerCluster.css';
import 'leaflet.markercluster/dist/MarkerCluster.Default.css';

// Import icon images
import iconRetinaUrl from 'leaflet/dist/images/marker-icon-2x.png';
import iconUrl from 'leaflet/dist/images/marker-icon.png';
import shadowUrl from 'leaflet/dist/images/marker-shadow.png';

// Fix untuk icon Leaflet
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl,
    iconUrl,
    shadowUrl,
});

// Loading Component
const createLoader = () => {
    const loader = document.createElement('div');
    loader.className = 'loader fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900';
    loader.innerHTML = `
    <div class="text-center">
        <div class="loader-spin inline-block w-16 h-16 border-4 border-primary-200 border-t-primary-600 rounded-full"></div>
        <h2 class="mt-4 text-xl font-semibold text-white">Loading maps</h2>
    </div>
`;
    return loader;
};

// Global loading functions
window.showLoader = () => {
    const loader = createLoader();
    document.body.appendChild(loader);
};

window.hideLoader = () => {
    const loader = document.querySelector('.loader');
    if (loader) {
        loader.remove();
    }
};

// Make Leaflet available globally
window.L = L;
