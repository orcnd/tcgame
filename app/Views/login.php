<?php view('header', [
    'title' => 'Login',
    'extracss' => ['css/login.css'],
    'withoutNav' => true,
]); ?>

<main class="form-signin text-center">
  <form method="post">
    <?php input_csrf_token(); ?>
    <h1>TC Game</h1>
    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
    <?php if (isset($errors)) {
        if (count($errors) > 0) { ?>
    <div class="alert alert-danger" role="alert">
        <?php echo implode('<br>', $errors); ?>
    </div>
    <?php }
    } ?>
    <div class="form-floating">
      <input type="text" class="form-control" value="<?php echo old(
          'username'
      ); ?>" id="floatingInput" name="username">
      <label for="floatingInput">User Name</label>
    </div>

    <button class="w-100 mt-2 btn btn-lg btn-primary" type="submit">Sign in</button>
    <p class="mt-5 mb-3 text-muted">Available user names<br> <?php echo implode(
        ', ',
        availableUserNames()
    ); ?></p>
  </form>
</main>

<?php view('footer'); ?>
