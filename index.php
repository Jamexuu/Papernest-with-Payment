<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
    <div class="container-fluid">
        <?php include 'components/navbar.php'; ?>
        <div class="row">
            <div class="col-12">
                <div id="carouselExample" class="carousel slide">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="assets/img/carousel/large-screens/slide1.png" class="d-none d-md-block w-100" alt="...">
                            <img src="assets/img/carousel/small-screens/slide1.png" class="d-block d-md-none w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="assets/img/carousel/large-screens/slide2.png" class="d-none d-md-block w-100" alt="...">
                            <img src="assets/img/carousel/small-screens/slide2.png" class="d-block d-md-none w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="assets/img/carousel/large-screens/slide3.png" class="d-none d-md-block w-100" alt="...">
                            <img src="assets/img/carousel/small-screens/slide3.png" class="d-block d-md-none w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="assets/img/carousel/large-screens/slide4.png" class="d-none d-md-block w-100" alt="...">
                            <img src="assets/img/carousel/small-screens/slide4.png" class="d-block d-md-none w-100" alt="...">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12" style="background-color: var(--secondary-color);">
                <div class="h1  text-white fw-bold p-3">What's Trending?</div>
                <div class="container pb-5">
                    <div class="row" id="trending"></div>
                </div>              
            </div>
        </div>
        <div class="row mt-5" style="background-color: var(--primary-color);">
            <div class="col-12 col-md-4">
                <img class="img-fluid rounded-circle p-5 mx-auto d-block" src="assets/img/userplaceholder.jpg" alt="">
            </div>
            <div class="col-12 col-md-8 text-white p-5">
                <div class="h3 text-center fw-semibold">Featured Author</div>
                <div class="h5 fw-semibold">John Doe</div>
                <div class="p">Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum ex inventore
                    exercitationem dolore delectus repellat, unde ut consequuntur quam iure vitae labore nemo culpa
                    pariatur sunt quibusdam sapiente excepturi non?</div>
                <br>
                <div class="h5 fw-semibold">Famous Books</div>
                <div class="p">book 1, book 2, book 3</div>
            </div>
        </div>  
        <?php include 'components/footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <script>
        function displayTrendingBooks() {
            const trending = document.getElementById('trending');

            for (let i = 0; i < 4; i++) {
                trending.innerHTML += `
                <div class="col-12 col-md-3">
                    <div class="card border-0" style="background-color: var(--secondary-color);">
                        <div class="card-body">
                            <img class="img-fluid mx-auto d-block" src="assets/img/books/1120_Front.jpg" alt="">
                            <div class="text-white p-3" style="background-color: var(--primary-color);">
                                <div class="card-title fw-semibold">Atomic Habits</div>
                                <div class="card-subtitle">James Clear</div>
                                <div class="card-text">1</div>
                            </div>
                            <div class="btn text-white rounded-0 w-100 p-3" style="background-color: var(--button-color);">Add to Cart</div>
                        </div>      
                    </div>
                </div>
            `;
            }
        }
        displayTrendingBooks();
    </script>

</body>

</html>