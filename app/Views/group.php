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
            <?php foreach ($group->users() as $user) {
                echo $user->name;
                if ($group->creator_id == $user->id) {
                    echo ' (Creator)';
                }
                if (auth()->user()->id == $user->id) {
                    echo ' (You added at `' .
                        betterDate($user->date_added) .
                        '`)';
                }
                echo '<br>';
            } ?>
            
        </div>
    </div>

</div>
<?php view('footer'); ?>
