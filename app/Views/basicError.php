<?php view('header', ['title' => 'Error']); ?>

<div class="container">
    <div class="row">
        <div class="col-12">            
            <div class="alert alert-danger" role="alert">
                <?php echo $message; ?>
                
            </div>
            <?php if (isset($goBack)) { ?>
                    <button onclick="history.back()" class="btn btn-primary">Go Back</button>
                <?php } ?>
        </div>
    </div>                 

</div>
<?php view('footer'); ?>
