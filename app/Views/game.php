<?php view('header', ['title' => 'Game']); ?>

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
            
        </div>
    </div>

</div>
<?php view('footer'); ?>
