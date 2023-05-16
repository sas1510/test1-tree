<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TEST: Binary tree</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="index.js"></script>
    <nav class="navbar navbar-expand-lg bg-body-tertiary navbar bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <input type="file" id="js-file" class="btn-light form-control bt-upload" placeholder="Введите искомое значение">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Выберите поле для поиска
                        </a>
                        <ul class="dropdown-menu" id="items">
                        </ul>
                    </li>
                </ul>
                <input class="form-control me-2 bt-field" type="search" placeholder="Введите поле" aria-label="Search" id="search">
                    <input class="form-control me-2 bt-field" type="search" placeholder="Введите искомое значение" aria-label="Search" id="search-value">
                    <button class="btn btn-primary bt-btn-search" id="search-btn">Поиск перебором</button>
                    <button class="btn btn-success bt-btn-search" id="search-index-btn">Индексный поиск</button>
                    <button class="btn btn-danger bt-btn-search" id="search-all-btn">Показать все</button>
            </div>
        </div>
    </nav>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-4 container-fluid h-100 terminal" id="result_form"></div>
            <div class="col-8 container-fluid h-100 documents" id="documents"></div>
        </div>
    </div>
</body>
</html>
<?php ?>