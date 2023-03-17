<?php view('header', [
    'title' => 'Game Group',
    'scripts' => ['/js/group.js'],
]); ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h1><?php echo $group->name; ?></h1>
            <?php if (
                $group->creator_id == auth()->user()->id &&
                count($group->users()) == 4
            ) {
                if ($group->status == 0) { ?>
            <a href="/join" class="btn btn-primary btn-lg">Start the game Now!</a>
            <?php } else { ?>
            <a href="/join" class="btn btn-primary btn-lg">Stop the game Now!</a>
            <?php }
            } ?>
            <hr>
            <span>
            <b> Status: </b><?php echo $group->status == 0
                ? 'Waiting'
                : 'Playing'; ?>
            
            </span>
        </div>
    </div>

    <div class="row">
        <div class="col-4">
            <h4>Players</h4>
            <ul id="players">

            </ul>
            
            
        </div>
    </div>

</div>
<?php view('footer'); ?>
