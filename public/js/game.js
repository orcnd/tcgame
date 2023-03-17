$(document).ready(function () {
  setInterval(function () {
    getStats()
  }, 2000)
  getStats()
})

function getStats() {
  $.ajax({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    url: '/game_home_stats',
    type: 'POST',
    dataType: 'json',
  }).done(function (data) {
    $('#active_games').html(data.active_games)
    $('#active_players').html(data.active_players)
    $('#waiting_players').html(data.waiting_players)
  })
}
