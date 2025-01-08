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

// Make Leaflet available globally
window.L = L;

// Update fungsi global loading
window.showLoader = () => {
    window.dispatchEvent(new Event('show-loader'));
};

window.hideLoader = () => {
    window.dispatchEvent(new Event('hide-loader'));
};

// Tambahkan ini untuk menangani navigasi
document.addEventListener('livewire:navigating', () => {
    showLoader();
});

document.addEventListener('livewire:navigated', () => {
    hideLoader();
});

// Untuk AJAX requests
document.addEventListener('livewire:loading.state.before', () => {
    showLoader();
});

document.addEventListener('livewire:loading.state.after', () => {
    hideLoader();
});
