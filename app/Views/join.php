<?php view('header', ['title' => 'Join Game', 'scripts' => ['/js/game.js']]); ?>

<div class="container">

    <div class="row">
        <div class="col-12">
            
            <?php if (count($availableGroups) > 0) {
                foreach ($availableGroups as $group) {
                    var_dump($group);
                }
            } else {
                echo "No available groups, create new one <a href='/new_group'>here</a>";
            } ?>
        </div>
    </div>

</div>
<?php view('footer'); ?>
