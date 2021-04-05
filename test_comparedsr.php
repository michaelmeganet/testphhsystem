<?php ?>
<!DOCTYPE html>
<html lang="en">

    <?php include "header.php"; ?>

    <body>

        <?php #include"navmenu.php";  ?>

        <div class="container">

            <div class="page-header" id="banner">
                <div class="row">
                    <div class="col-lg-12 col-md-9 col-sm-8">
                        <h1>COMPARE DSR LEGACY v NEW FUNCTION</h1>
                        <br>
                        <br>
                    </div>
                    <br>

                </div>
                <div class='row'>
                    <?php include_once "./test_dsr/compare_dsr.php"; ?>
                    <br>

                </div>            
            </div>



        </div>
        <?php include"footer.php" ?>

    </body>
</html>


