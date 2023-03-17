<?php view('header', ['title' => 'Join Game', 'scripts' => ['/js/game.js']]); ?>

<div class="container">
<div class="row">
<?php if (count($availableGroups) > 0) {
    foreach ($availableGroups as $group) { ?>
    
        <div class="col-4">                   
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $group->name; ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo count(
                        $group->users()
                    ); ?> Players</h6>
                    <a href="/join_group/<?php echo $group->id; ?>" class="card-link">Join</a>

                </div>
            </div>

        </div>
    <?php }
} else {
     ?>
        <div class="col-12">            
            No available groups, create new one <a href='/new_group'>here</a>
        </div>
    <?php
} ?>
    </div>                 

</div>
<?php view('footer'); ?>
