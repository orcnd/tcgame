<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>404 Not Found</title>
  <link rel="stylesheet" href="/css/404.css">

</head>
<body>
<!-- partial:index.partial.html -->
<div class="notfound">
    <div class="centered"><span class="inverted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;</div>
    <div class="centered"><span class="inverted">&nbsp;4&nbsp;0&nbsp;4&nbsp;</span><span class="shadow">&nbsp;</span></div>
    <div class="centered"><span class="inverted">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="shadow">&nbsp;</span></div>
    <div class="centered">&nbsp;<span class="shadow">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
    <div class="row">&nbsp;</div>
    <div class="row">A fatal exception 404 has occurred at C0DE:ABAD1DEA in 0xC0DEBA5E.</div>
    <div class="row">The current request will be terminated.</div>
    <div class="row">&nbsp;</div>
    <div class="row">* Press any key to return to the previous page.</div>
    <div class="row">* Press CTRL+ALT+DEL to restart your computer. You will</div>
    <div class="row">&nbsp;&nbsp;lose any unsaved information in all applications.</div>
    <div class="row">&nbsp;</div>
    <div class="centered">Press any key to continue...<span class="blink">&#9608;</span></div>
</div>
<!-- partial -->
<script>
  document.addEventListener('keydown', function (e) {
    
      console.log("back");
      window.history.back();
      e.preventDefault();
  });

</script>
</body>
</html>
