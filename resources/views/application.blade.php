<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <link rel="icon" href="/favicon.ico" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Digivive CMS | Login</title>
  <link rel="stylesheet" type="text/css" href="/loader.css" />
  <!-- Include Quill stylesheet -->
<link href="https://cdn.quilljs.com/1.0.0/quill.snow.css" rel="stylesheet" />
  @vite(['resources/ts/main.ts'])
</head>

<body>
  <div id="app">
    <div id="loading-bg" >
      <!-- <div class="loading-logo">
        <img src="/logo.png" alt="Logo" />
      </div> -->
      <div class="loading">
        <div class="effect-1 effects"></div>
        <div class="effect-2 effects"></div>
        <div class="effect-3 effects"></div>
      </div>
    </div>
  </div>
  
  <script>
    const loaderColor = localStorage.getItem('Vuexy-initial-loader-bg') || '#FFFFFF'
    const primaryColor = localStorage.getItem('Vuexy-initial-loader-color') || '#7367F0'

    if (loaderColor)
      document.documentElement.style.setProperty('--initial-loader-bg', loaderColor)
    if (primaryColor)
      document.documentElement.style.setProperty('--initial-loader-color', primaryColor)

  </script>
  <!-- Include the Quill library -->
<script src="https://cdn.quilljs.com/1.0.0/quill.js"></script>
</body>

</html>
