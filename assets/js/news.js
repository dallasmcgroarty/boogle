
function getNews (term) {
  let apiKey = 'c939c288f98045d7a57360fef3f39d25';
  let url = 'https://newsapi.org/v2/everything?' +
          'q=' + term + '&' +
          'from=2020-02-11&' +
          'sortBy=popularity&' +
          'apiKey=' + apiKey;

  let req = new Request(url);

  fetch(req).then(response =>
    response.json().then(data => ({
      data: data
    })
    ).then(res => {
      console.log(res.data.articles[0]);
    }));
};
