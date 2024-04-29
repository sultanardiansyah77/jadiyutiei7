<?php
require_once("config.php");
require_once("_helper/helper.php");

if (isset($_POST['nominal'], $_POST['metode_id'])){
    $nominal = $_POST['nominal'];
    $nominal = str_replace(" ", "", $nominal);
    $nominal = str_replace("Rp", "", $nominal);
    $nominal = str_replace(".", "", $nominal);
    $metode_id = abs((int) $_POST['metode_id']);
    $xtoken = my_token();
    $data = array(
        'nominal' => $nominal,
        'metode_id' => $metode_id
    );
    $res = $app->curl_post_json_with_auth("$api_url/deposit/order", $data, $xtoken);
    $res_data = json_decode($res, true);
    if (isset($res_data['status'])){
        if($res_data['status']==1){
            $rc = $res_data['rc'];
            if ($rc == 200){
                $out = array(
                    'status' => 1,
                    'message' => $res_data['message'],
                    'data' => $res_data['data']
                );
            }else{
                $out = array(
                    'status' => 0,
                    'error_msg' => $res_data['message']
                );
            }
        }else{
            $out = array(
                'status' => 0,
                'error_msg' => $res_data['error_msg']
            );
        }
    }else{
        $out = array(
            'status' => 0,
            'error_msg' => "Server down"
        );
    }
    echo json_encode($out);exit;
}

require_once("_helper/user_login.php");

$x_token = my_token();
$metode = $app->grab_data("$api_url/topup/metode/list");
// echo $trx;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require_once("_/head.php");
    ?>
    <title>Order Deposit - <?php echo $c_brand ?></title>
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
                                <div class="text-lg text-gray-300 p-4 rounded-lg bg-fifth text-sky-100">Deposit saldo</div>
                            </div>
                            <div class="pt-[40px] w-full">
                                <div>
                                    <label for="nominal" class="text-gray-300 font-bold">Nominal</label>
                                    <input type="text" name="nominal" id="nominal" class="form-input">
                                </div>
                                <div class="mt-5">
                                    <label for="nominal" class="text-gray-300 font-bold">Metode Pembayaran</label>
                                    <?php
                                    if (isset($metode)) {
                                        $data_metode = json_decode($metode, true);
                                        if (isset($data_metode['status'])) {
                                            if ($data_metode['status'] == 1) {
                                                $data_m = $data_metode['data'];
                                                $no_kat = 1;
                                                $no_item = 1;
                                                if (is_array($data_m) && count($data_m) > 0) {
                                                    foreach ($data_m as $row) {
                                                        $kategori = $row['kategori'];
                                                        $metode_list = $row['metode'];
                                                        // echo $kategori;
                                                        if ($kategori['id'] != KATEGORI_SALDO) {
                                    ?>
                                                            <div class="p-4 flex gap-2 items-center">
                                                                <i class="fa-solid fa-credit-card  text-white"></i>
                                                                <p class="font-bold text-white"><?php echo $kategori['name'] ?></p>
                                                            </div>
                                                            <div id="collapse-<?php echo $no_kat ?>" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordion-<?php echo $no_kat ?>">
                                                                <div class="accordion-body py-4 px-5 flex-col md:flex-row gap-4">
                                                                    <div class="md:p-4 grid grid-cols-1 md:grid-cols-3 sm:grid-cols-2  md:gap-4 gap-4 relative pb-8">
                                                                        <?php
                                                                        if (is_array($metode_list) && count($metode_list) > 0) {
                                                                            foreach ($metode_list as $row) {
                                                                        ?>

                                                                                <!-- item      -->
                                                                                <div id="method-<?php echo $no_item ?>" class=" p-4 color-secondary m-shadow rounded-2xl cursor-pointer" onclick="select_method(<?php echo $no_item ?>, <?php echo $row['id'] ?>, ' <?php echo $row['name'] ?>')">
                                                                                    <div class="border-b border-b-gray-600  py-1 px-1 mb-2 bg-white w-max rounded-lg">
                                                                                        <img src="<?php echo $row['image'] ?>" class="h-5" alt="">
                                                                                    </div>
                                                                                    <hr>
                                                                                    <span class="text-xs py-2 px-2"><?php echo $row['name'] ?></span>
                                                                                    <small><?php echo $row['deskripsi'] ?></small>
                                                                                </div>
                                                                                <!-- end item  -->

                                                                        <?php
                                                                                $no_item++;
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="accordion m-shadow " id="accordion-<?php echo $no_kat ?>">
                                                                <div class="accordion-item bg-red">
                                                                    <h2 class="accordion-header mb-0" id="headingOne">
                                                                        <button class="accordion-button relative flex items-center w-full py-4 px-4 text-base text-gray-800 text-left bg-sky-600 rounded-none border-0 transition focus:outline-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $no_kat ?>" aria-expanded="false" aria-controls="collapse-<?php echo $no_kat ?>">
                                                                        </button>
                                                                </div>
                                                            </div>

                                    <?php
                                                            $no_kat++;
                                                        }
                                                    }
                                                }
                                            } else {
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                                <button class="btn-me mt-6" id="btn_deposit">Deposit</button>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="h-[40px]"></div>
    <?php require_once("_/general.php") ?>
    <?php //require_once("_/footer.php") ?>

    <script src="<?php echo $c_url ?>/assets/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/index.min.js"></script>

    <script>
        var tmp_method_id = 100;
        var metode_id = 0
        var metode_name
        var nominal = $("#nominal")
        var btn_deposit = $("#btn_deposit")
        var file = "/user/deposit-order";

        btn_deposit.on("click", function() {
            if (nominal.val() == "0" || nominal.val() == "") {
                toast("Mohon inputkan nominal")
                return
            }
            if (metode_id == 0) {
                toast("Mohon pilih metode pembayaran")
                return
            }

            deposit();
        })

        nominal.on("keyup change", function(e) {
            var fr = formatRupiah(nominal.val(), "Rp, ")
            console.log(fr)
            nominal.val(fr)
        })

        async function deposit(){
            loading_btn(btn_deposit)
            try {
                var res = await curl_post(file, {
                    metode_id: metode_id,
                    nominal: nominal.val()
                })
                console.log(res)
                if (res.status == 1) {
                    var data = res.data
                    var msg = res.message
                    toast(msg)
                    setTimeout(() => {
                        window.location.replace("/user/deposit-detail/"+data);
                    }, 2000);
                } else {
                    var msg = res.error_msg
                    toast(msg)
                    release_btn(btn_deposit, "Deposit")
                }
            } catch (error) {
                // console.log(error)
                toast(error.statusText)
                release_btn(btn_deposit, "Deposit")
            }
        }

        function select_method(id, id_metode, metode_name_as) {
            metode_id = id_metode
            metode_name = metode_name_as
            var selector_mt = $("#method-" + id)
            if (tmp_method_id != 100) {
                var tmp_selector_mt = $("#method-" + tmp_method_id)
                tmp_selector_mt.removeClass("m-shadow-active")
                tmp_selector_mt.addClass("m-shadow");
                tmp_method_id = id
                selector_mt.removeClass("m-shadow")
                selector_mt.addClass("m-shadow-active");
            } else {
                tmp_method_id = id
                // console.log(selector_mt)
                selector_mt.removeClass("m-shadow")
                selector_mt.addClass("m-shadow-active");
            }
        }
    </script>
</body>

</html>