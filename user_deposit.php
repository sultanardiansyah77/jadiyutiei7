<?php
require_once("config.php");
require_once("_helper/helper.php");
require_once("_helper/user_login.php");

$x_token = my_token();
$depo = $app->grab_data_auth("$api_url/deposit/list", $x_token);
$depo_res = json_decode($depo, true);
// echo $trx;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <?php
    require_once("_/head.php");
    ?>
    <title>Deposit - <?php echo $c_brand ?></title>
    <style>

    </style>
</head>

<body class="mbg-primary">
    <div class="flex flex-col justify-between h-full">
        <div>
            <?php require_once("_/header.php")
            ?>
            <div class="w-full  mt-[100px] md:mt-[200px] container-xxl ">
                <div class="w-full flex md:flex-row flex-col gap-8 ">
                    <?php require_once("_home/user.php") ?>
                    <div class="w-full ">
                        <div class="h-max">
                            <div class="flex flex-row gap gap-4">
                                <?php
                                $path = "Deposit";
                                require_once("_home/menu.php");
                                ?>
                            </div>
                            <div class="mt-4">
                                <div class="text-lg text-gray-300 p-4 rounded-lg bg-fifth text-sky-100">Data deposit</div>
                            </div>
                            <div class="pt-[40px] w-full overflow-x-auto">
                                <?php
                                if (isset($depo_res['status'])) {
                                    $rc = $depo_res['rc'];
                                    $status = $depo_res['status'];
                                    if ($status ==1){
                                        if ($rc == 200) {
                                            $data = $depo_res['data']['data'];
                                            ?>
                                            <table id="example" class="table table-striped text-white  responsive" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Metode</th>
                                                        <th>Nominal</th>
                                                        <th>Status</th>
                                                        <th>Updated</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                     $no = 1;
                                                    foreach ($data as $row) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $no ?></td>
                                                            <td><?php echo $row['metode_name'] ?></td>
                                                            <td><?php echo $app->idr($row['nominal']) ?></td>
                                                            <td><?php echo $app->status_general($row['status'])?></td>
                                                            <td><?php echo $app->tgl_indo($row['updated_at'])?></td>
                                                            <td>
                                                                <a href="/user/deposit-detail/<?php echo $row['id'] ?>" class="px-2 py-1 bg-fifth text-gray-300 text-sm rounded-lg" >Detail</a>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    $no++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        <?php
                                        } else {
                                        ?>
                                            <div class="text-lg p-4 rounded-lg bg-red-200 text-red-600"><?php echo $depo_res['message'] ?></div>

                                        <?php
                                        }
                                    }else{
                                        ?>
                                            <div class="text-lg p-4 rounded-lg bg-red-200 text-red-600"><?php echo $depo_res['error_msg'] ?></div>

                                        <?php
                                    }
                                } else {
                                    ?>
                                    <div class="text-lg text-gray-300 p-4 rounded-lg bg-red-400 text-red-600">Gagal menhubungi Api</div>

                                <?php
                                }
                                ?>

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once("_/general.php") ?>

    <script src="<?php echo $c_url ?>/assets/app.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
</body>

</html>