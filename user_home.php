<?php
require_once("config.php");
require_once("_helper/helper.php");
require_once("_helper/user_login.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require_once("_/head.php");
    ?>
    <title>Home - <?php echo $c_brand ?></title>
</head>

<body class="mbg-primary">
    <div class="flex flex-col justify-between h-full">
        <div>
            <?php require_once("_/header.php")
            ?>
            <div class="w-full mt-[100px] md:mt-[200px]  container-xxl ">
                <div class="w-full flex md:flex-row flex-col gap-8 ">
                    <?php require_once("_home/user.php") ?>
                    <div class="w-full ">
                        <div class="h-max">
                            <div class="flex flex-row gap gap-4">
                                <?php
                                    $path = "Home";
                                    require_once("_home/menu.php");
                                ?>
                            </div>
                            <div class="mt-4">
                                <div class="text-lg text-gray-300 p-4 rounded-lg bg-fifth text-sky-100">Selamat datang di <?php echo $c_brand ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("_/general.php") ?>
    <script src="<?php echo $c_url ?>/assets/app.js"></script>
</body>

</html>