<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>HTML</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>

<body>
    <img src="{{ asset('logo-sedes-lapaz.png') }}" width="100" height="100">
    <div class="container">
        <div class="row">
          <div class="col p-3 mb-2 bg-primary text-white">Column</div>
          <div class="col p-3 mb-2 bg-danger text-white">Column</div>
          <div class="w-100"></div>
          <div class="col">Column</div>
          <div class="col">Column</div>
        </div>
      </div>
</body>

</html>
