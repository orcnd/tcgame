<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TC Game<?php
    echo auth()->user() !== null ? ' u: ' . auth()->user()->name : '';
    echo isset($title) ? ' - ' . $title : '';
    ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script>
      <?php if (auth()->check()) { ?>
        window.user = {
          id: <?php echo auth()->user()->id; ?>,
          name: "<?php echo auth()->user()->name; ?>"
        }
      <?php } ?>
    </script>
    <script
  src="https://code.jquery.com/jquery-3.6.4.min.js"
  integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
  crossorigin="anonymous"></script>
  <?php if (isset($scripts)) { ?>
    <?php foreach ($scripts as $script) { ?>
        <script src="<?php echo $script; ?>"></script>
    <?php }} ?>

  </head>
  <body>
    <?php if (!isset($withoutNav)) { ?>
    <div class="container">
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
            TcGame
        </a>
        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <li><a href="/" class="nav-link px-2 link-dark">Home</a></li>
            <li><a href="/game" class="nav-link px-2 link-dark">Game</a></li>
        </ul>
        <div class="col-md-3 text-end">
            <?php if (auth()->check()) { ?>
            <a class="btn btn-primary" href="/logout">Logout</a>
            <?php } else { ?>
            <a class="btn btn-primary" href="/login">Login</a>
            <?php } ?>
        </div>
        </header>
    </div>
<?php } ?>
