$(document).ready(function () {
  setInterval(getUsers, 2000)
  getUsers()
})

var oldUsers = ''
function getUsers() {
  $.ajax({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    url: '/group_wait_list',
    data: {
      oldData: oldUsers,
    },
    type: 'POST',
    dataType: 'json',
  }).done(function (data) {
    if (data.update) {
      oldUsers = data.hash
      $('#players').html()
      for (var i = 0; i < data.users.length; i++) {
        var str = '<li>' + data.users[i].name
        str += ' (' + data.users[i].date_added + ')'
        if (data.users[i].is_creator) {
          str += ' (Creator)'
        }
        if (window.user.id == parseInt(data.users[i].id)) {
          str += ' (You)'
        }
        str += '</li>'
        $('#players').append(str)
      }
    }
  })
}
