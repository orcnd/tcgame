<?php view('header', [
    'title' => 'Game Group',
    'scripts' => ['/js/group.js'],
]); ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h1><?php echo $group->name; ?></h1>
            <b> Your Status: </b><span id="your_status"></span>
            <hr>
            <button id="set_ready_btn" class="btn btn-primary  ">Set Ready</button>
            <button id="set_pending_btn" class="btn btn-primary">Set Pending</button>
            <a id="go_play_btn" class="btn btn-primary" href="/play" >Go Play</a>
            
            <a href="/start_game" id="start_game_btn" class="btn btn-primary d-none">Start Game</a>
        </div>
    </div>
<hr>
    <div class="row">
        <div class="col-4">
            <h4>Players</h4>
            <ul id="players">

            </ul>
            
            
        </div>
    </div>

</div>
<?php view('footer'); ?>
