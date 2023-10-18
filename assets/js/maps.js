function getPosition() {
  if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(loadMap, function() {
          alert('Could get not get your position')
      })
  }
}

function loadMap(pos) {
  const {latitude} = pos.coords;
  const {longitude} = pos.coords;

  const coords = [latitude, longitude]

  let map = L.map('map').setView(coords, 13);

  L.tileLayer('https://tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map);
}

$(document).ready(function () {
  getPosition();
});
