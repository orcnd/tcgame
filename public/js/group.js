$(document).ready(function () {
  startGettingUsers()

  $('#start_game_btn').click(function () {
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      url: '/startGame',
      type: 'POST',
      dataType: 'json',
    }).done(function (data) {
      if (data.status && data.status == 'ok') {
        startGettingUsers()
      } else {
        alert('game not started because of ' + data.message)
      }
    })
  })

  $('#set_ready_btn').click(function () {
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      url: '/setReady',
      type: 'POST',
      dataType: 'json',
    }).done(function (data) {
      if (data.status && data.status == 'ok') {
        startGettingUsers()
      } else {
        alert('status not set because of ' + data.message)
      }
    })
  })

  $('#set_pending_btn').click(function () {
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      url: '/setPending',
      type: 'POST',
      dataType: 'json',
    }).done(function (data) {
      if (data.status && data.status == 'ok') {
        startGettingUsers()
      } else {
        alert('status not set because of ' + data.message)
      }
    })
  })
})

var userGetInterval
function startGettingUsers() {
  clearInterval(userGetInterval)
  userGetInterval = setInterval(getUsers, 2000)
  getUsers()
}
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
      var readyPlayers = 0
      $('#players').html(' ')
      for (var i = 0; i < data.users.length; i++) {
        if (data.users[i].game_status == 'ready') {
          readyPlayers++
        }
        var str = '<li>' + data.users[i].name
        str += ' (' + getUserStatusHuman(data.users[i].game_status) + ')'
        str += ' (' + data.users[i].date_added + ')'
        if (data.users[i].is_creator) {
          str += ' (Creator)'
        }
        if (window.user.id == parseInt(data.users[i].id)) {
          str += ' (You)'
          displayUserStatus(data.users[i].game_status)
        }
        str += '</li>'
        $('#players').append(str)
      }
      if (readyPlayers == 4) {
        $('#start_game_btn').removeClass('d-none')
      } else {
        $('#start_game_btn').addClass('d-none')
      }
    }
  })
}

function getUserStatusHuman($status) {
  if ($status == 'pending') {
    return 'Pending'
  } else if ($status == 'ready') {
    return 'Ready'
  } else if ($status == 'playing') {
    return 'Playing'
  } else if ($status == 'left') {
    return 'Left'
  } else {
    return $status
  }
}

function displayUserStatus($status) {
  if ($status == 'pending') {
    $('#set_ready_btn').show()
    $('#set_pending_btn').hide()
    $('#go_play_btn').hide()
  } else if ($status == 'ready') {
    $('#set_ready_btn').hide()
    $('#set_pending_btn').show()
    $('#go_play_btn').hide()
  } else if ($status == 'playing') {
    $('#set_ready_btn').hide()
    $('#set_pending_btn').hide()
    $('#go_play_btn').show()
  }
  $('#your_status').html(getUserStatusHuman($status))
}
