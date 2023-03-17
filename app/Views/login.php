<?php view('header', ['title' => 'Login', 'withoutNav' => true]); ?>
<style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
      html,
body {
  height: 100%;
}

body {
  display: flex;
  align-items: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}

.form-signin {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: auto;
}

.form-signin .checkbox {
  font-weight: 400;
}

.form-signin .form-floating:focus-within {
  z-index: 2;
}

.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
    </style>


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
