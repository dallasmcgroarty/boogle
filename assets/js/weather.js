// weather api call
// short script to get weather info

let apiKey = 'bbe2356fb7803221aa32281f0c467282';

function getLocation () {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else {
    alert('Geolocation not supported by browser or you must enable location services');
  }
}

function showPosition (position) {
  let lat = position.coords.latitude;
  let lon = position.coords.longitude;

  callWeatherAPI(lat, lon);
}

function callWeatherAPI (lat, lon) {
  let url = 'https://api.openweathermap.org/data/2.5/weather?lat=' + lat.toString() + '&lon=' + lon.toString() +
      '&units=imperial&APPID=' + apiKey;
  fetch(url)
    .then((response) => {
      return response.json();
    })
    .then((myJson) => {
      console.log(myJson);
      $.post('ajax/weather.php', { weather: JSON.stringify(myJson) });
    });
};
