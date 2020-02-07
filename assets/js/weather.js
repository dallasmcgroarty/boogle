// weather api call
// short script to get weather info
$(document).ready(function () {
  let apiKey = 'bbe2356fb7803221aa32281f0c467282';
  var elm = document.getElementById('loc');

  function getLocation () {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition);
    } else {
      elm.textContent = 'Geolocation is not supported by this browser.';
    }
  }

  function showPosition (position) {
    let lat = position.coords.latitude;
    let lon = position.coords.longitude;

    $.post('./search.php', { lat: lat, lon: lon }).done(function (data) {
      if (data !== '') {
        return;
      }
    });

    callWeatherAPI(lat, lon);
  }

  function callWeatherAPI (lat, lon) {
    let url = 'https://api.openweathermap.org/data/2.5/weather?lat=' + lat.toString() + '&lon=' + lon.toString() +
        'APPID=' + apiKey;
    fetch(url)
      .then((response) => {
        return response.json();
      })
      .then((myJson) => {
        console.log(myJson);
      });
  }
  // call getLocation on page load
  getLocation();
});
