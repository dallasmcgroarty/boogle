/*
  using js here because we need to update click count for the clicked link
  before going to the clicked link
*/
$(document).ready(function () {
  $('.result').on('click', function (e) {
    var url = $(this).attr('href');
    var id = $(this).attr('data-linkId');
    // in plain javascript get href/url value
    // var urls = e.target.attributes.href.value;

    if (!id) {
      alert('data-linkId not found');
    }

    increaseLinkClicks(id, url);

    e.preventDefault();
  });
});

function increaseLinkClicks (linkId, url) {
  $.post('ajax/updateLinkCount.php', { linkId: linkId })
    .done(function (result) {
      if (result !== '') {
        alert(result);
      }
      window.location.href = url;
    });
}
