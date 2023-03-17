<?php view('header', ['title' => 'Game', 'scripts' => ['/js/game.js']]); ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Game</h1>
            <?php if (auth()->user()->status == 0) { ?>
                <a href="/join" class="btn btn-primary btn-lg">Join Now!</a>
            <?php } ?>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            
            <b>Active Games: </b><span id="active_games"></span><br>
            <b>Active Players: </b><span id="active_players"></span><br>
            <b>Waiting Players: </b><span id="waiting_players"></span><br>
        </div>
    </div>

</div>
<?php view('footer'); ?>
