const center = L.latLng(15.486712, 120.589966);
const southWest = L.latLng(15.5777,120.7113);
const northEast = L.latLng(15.3974,120.5136);
const bounds = L.latLngBounds(southWest, northEast);

let map = L.map('map', {
    maxBounds: bounds,
    minZoom: 12,
    maxZoom: 18
}).setView(center, 18);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        
        
let marker = L.marker(center).addTo(map);


/*

let popup = L.popup();

function onMapClick(e) {
            popup
                .setLatLng(e.latlng)
                .setContent("You clicked the map at " + e.latlng.toString())
                .openOn(map);
        }
        
        map.on('click', onMapClick); 
 */   //for dev purposes