<?php
require_once("config.php");
require_once("_helper/helper.php");

if (!isset($_GET['id'])) {
    header("Location: /user/deposit");
    exit;
}
$id = abs((int) $_GET['id']);

require_once("_helper/user_login.php");

$x_token = my_token();
$deposit = $app->grab_data_auth("$api_url/deposit/detail/$id", $x_token);
$deposit_res = json_decode($deposit, true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require_once("_/head.php");
    ?>
    <title>Deposit Detail - <?php echo $c_brand ?></title>
    <style>

    </style>
</head>

<body class="mbg-primary">
    <div class="flex flex-col justify-between h-full">
        <div>
            <?php require_once("_/header.php")
            ?>
             <div class="container-xxl mt-[100px] md:mt-[150px]">
                    <div class="m-shadow p-8 text-white text-center ">
                        <div class="md:w-[50%] w-full m-auto">
                        <?php
                        // echo $deposit;exit;
                        if (isset($deposit_res['status'])) {
                            $status = $deposit_res['status'];
                            $rc = $deposit_res['rc'];
                            if ($status == 1) {
                                if ($rc == 200) {
                                    $get_pay = true;
                                    $data = $deposit_res['data'];
                                    $status_pay = $data['status'];
                                    ?>
                                    <table class="table-auto w-full">
                                        <tbody class="divide-y divide-gray-300 align-center">
                                            <tr class="items-center">
                                                <td class="px-6 py-4 text-[13px] font-semibold" colspan="2">Detail Deposit</td>
                                            </tr>
                                            <tr class="group">
                                                <td class="item-table text-right ">
                                                    ID :
                                                </td>
                                                <td class="item-table text-left">
                                                    <div class="text-sm " onclick="copy_text('<?php echo $data['id'] ?>')">
                                                        #<?php echo $data['id'] ?>&nbsp; <i class="fa-solid fa-copy text-white"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="group">
                                                <td class="item-table text-right ">
                                                    Metode :
                                                </td>
                                                <td class="item-table text-left">
                                                    <div class="text-sm ">
                                                        <?php echo $data['metode_name'] ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="group">
                                                <td class="item-table text-right ">
                                                    Nominal :
                                                </td>
                                                <td class="item-table text-left">
                                                    <div class="text-sm ">
                                                        <?php echo $app->idr($data['nominal']) ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="group">
                                                <td class="item-table text-right ">
                                                    Total Bayar :
                                                </td>
                                                <td class="item-table text-left">
                                                    <div class="text-sm ">
                                                        <?php echo $app->idr($data['total_bayar']) ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="group">
                                                <td class="item-table text-right ">
                                                    Status :
                                                </td>
                                                <td class="item-table text-left">
                                                    <div class="text-sm ">
                                                        <?php echo $app->status_general($data['status']) ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="group">
                                                <td class="item-table text-right ">
                                                    Updated :
                                                </td>
                                                <td class="item-table text-left">
                                                    <div class="text-sm ">
                                                        <?php echo $app->tgl_indo($data['updated_at']) ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                        <?php
                                    } else {
                                        ?>
                                            <div class="text-l p-4 rounded-lg bg-red-400 text-red-600"><?php echo $deposit_res['error_msg'] ?> #err1.2</div>
                                    <?php
                                    }
                            }else{
                                ?>
                                <div class="text-l p-4 rounded-lg bg-red-500 text-red-100"><?php echo $deposit_res['error_msg'] ?> #err1.2</div>
                            <?php
                            }
                        } else {
                                ?>
                                <div class="text-l p-4 rounded-lg bg-red-400 text-red-600">Gagal menghubungi api #err1.1</div>
                            <?php
                        }

                        if (isset($get_pay)){
                            //grab payment
                            if ($status_pay == "Pending"){
                                $grab_pay = $app->grab_data_auth("$api_url/third-party/payment-deposit/$id", $x_token);
                                // echo $grab_pay;
                                $data_pay = json_decode($grab_pay, true);
                                // var_dump($data_pay);
                                if (isset($data_pay['status'])){
                                    if ($data_pay['status']==1){
                                        $data = $data_pay['data'];
                                        $tipe = $data['tipe'];
                                        $link = $data['data'];
                                        $va = $data['data'];
                                        $qr = $data['data'];
                                        $link_panduan = $data['link'];

                                        if ($tipe == "qr"){
                                            //QRcode::png($qr);
                                            ?>
                                            <div class="flex mt-20 flex-col gap-4 items-center">
                                            <img src="<?php echo "$c_url/qr.php?qr=$qr" ?>" alt="">
                                            <h2 class="text-white">Silahkan scan QR ini</h2>
                                            <?php
                                                if ($link != ""){
                                                    echo '<a target="_blank" class="px-4 py-2 bg-sky-600 text-white font-semibold rounded-lg" href="'.$link_panduan.'">Petunjuk Scan Qr</a>';
                                                }
                                            ?>
                                            </div>
                                            <?php
                                        }else if ($tipe == "link"){
                                            ?>
                                                <div class="flex mt-2 flex-col gap-4 items-center">
                                                 <a target="_blank" class="px-4 py-2 mt-20 bg-sky-600 text-white font-semibold rounded-lg" href="<?php echo $link_panduan ?>">Bayar</a>
                                                </div>
                                            <?php
                                        }else if ($tipe == "va"){
                                            // echo '<div  class="px-4 py-2 mt-20 bg-sky-600 text-white font-semibold rounded-lg" href="">'.$va.'</div>';
                                            ?>
                                                <div class="flex mt-2 flex-col gap-4 items-center">
                                                    <div>
                                                        <div onclick="copy_text('<?php echo $va ?>')" class="px-4 py-2 mt-20 bg-sky-600 text-white font-semibold rounded-lg cursor-pointer"><?php echo $va ?></div>
                                                    <small>Kode Virtual Account</small>
                                                    </div>
                                                    <a target="_blank" class="px-4 py-2 mt-2 bg-sky-600 text-white font-semibold rounded-lg" href="<?php echo $link_panduan ?>">Petunjuk Pembayaran</a>
                                                </div>
                                            <?php
                                        }else{
                                            ?>
                                            <h2 class="text-white mt-20">Tipe pembayaran belum di dukung !!</h2>
                                        <?php
                                        }
                                    }else{
                                        $msg = $data_pay['error_msg'];
                                        ?>
                                            <h2 class="text-white mt-20"><?php echo $msg ?></h2>
                                        <?php
                                    }
                                }else{
                                    $msg = "Gagal menghubungi payment gateway";
                                    ?>
                                    <h2 class="text-white mt-20"><?php echo $msg ?></h2>
                                <?php
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="md:mt-[80px] mt-[0px]"></div>
    <?php require_once("_/general.php") ?>
    <?php //require_once("_/footer.php") ?>

    <script src="<?php echo $c_url ?>/assets/app.js"></script>

</body>

</html>