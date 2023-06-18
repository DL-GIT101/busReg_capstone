const latitude = document.getElementById('latitude');
const longitude = document.getElementById('longitude');

const center = L.latLng(latitude.innerText, longitude.innerText);
map.setView(center,18);
let marker = L.marker(center).addTo(map);