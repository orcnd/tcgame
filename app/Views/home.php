<?php view('header', ['title' => 'Welcome']); ?>

<div class="container mt-5">
    <div class="p-5 mb-4 bg-light rounded-3">
      <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Welcome To game</h1>
        <p class="col-md-8 fs-4">Please login to start, game need 4 players. patiently wait for group to fill, then enjoy.</p>
        <a href="/game" class="btn btn-primary btn-lg">Start Now! </a>
      </div>
    </div>
</div>

<?php view('footer'); ?>
