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

// Fungsi untuk mengambil data dari URL
window.fetchData = async function(url) {
    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error('Network response was not ok');
        return await response.json();
    } catch (error) {
        console.error('Error fetching data:', error);
        return [];
    }
}

// window.showNotification = async function(message) {

//     // You can customize this notification style
//     const notification = document.createElement('div');
//     notification.className = 'fixed top-4 right-4 bg-white border border-gray-200 rounded-lg shadow-lg p-4 z-50 animate-fade-in-down';
//     notification.innerHTML = `
//         <div class="flex items-center">
//             <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
//                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
//             </svg>
//             <span class="text-gray-700">${message}</span>
//         </div>
//     `;

//     document.body.appendChild(notification);

//     // Remove notification after 5 seconds
//     setTimeout(() => {
//         notification.remove();
//     }, 5000);
// }
window.showNotification = async function (message, type = 'error') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white transform transition-all duration-300 translate-y-0 opacity-100 z-50`;
    notification.innerHTML = message;

    document.body.appendChild(notification);

    // Animate and remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-y-2', 'opacity-0');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}