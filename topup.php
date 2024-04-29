<?php
require_once("config.php");
require_once("_helper/helper.php");
if (isset($_COOKIE[$x_token])) {
    require_once("_helper/user_login.php");
}

if (isset($_POST['act'])) {
    $act = $_POST['act'];
    if ($act == "cek") {
        $tujuan = $_POST['tujuan'];
        $operator = $_POST['operator'];
        $data = array(
            'tujuan' => $tujuan,
            'operator' => $operator
        );
        $res = $app->curl_post_json("$api_url/trx/cek-username", $data);
        $res_dec = json_decode($res, true);
        if (isset($res_dec['status'])) {
            if ($res_dec['status'] == 1) {
                $out = array(
                    'status' => 1,
                    'data' => $res_dec['data']
                );
            } else {
                $out = array(
                    'status' => 0,
                    'error_msg' => $res_dec['error_msg']
                );
            }
        } else {
            $out = array(
                'status' => 0,
                'error_msg' => "Server down"
            );
        }
    } else if ($act == "order") {
        $wa = $_POST['hp'];
        $metode = $_POST['metode'];
        $produk = $_POST['produk'];
        $user_id = $_POST['user_id'];
        $server_id = $_POST['server_id'];
        $data = array(
            'hp' => $wa,
            'metode' => $metode,
            'produk' => $produk,
            'tujuan' => $user_id,
            'server_id' => $server_id
        );
        if (isset($_COOKIE[$x_token])) {
            //with auth
            $token = my_token();
            $res = $app->curl_post_json_with_auth("$api_url/trx/with-auth", $data, $token);
        } else {
            $res = $app->curl_post_json("$api_url/trx/proses", $data);
        }
        $res_dec = json_decode($res, true);
        if (isset($res_dec['status'])) {
            if ($res_dec['status'] == 1) {
                $rc = $res_dec['rc'];
                if ($rc == 200) {
                    $out = array(
                        'status' => 1,
                        'message' => $res_dec['message'],
                        'data' => $res_dec['data']
                    );
                } else {
                    $out = array(
                        'status' => 0,
                        'error_msg' => $res_dec['message']
                    );
                }
            } else {
                $out = array(
                    'status' => 0,
                    'error_msg' => $res_dec['error_msg']
                );
            }
        } else {
            $out = array(
                'status' => 0,
                'error_msg' => "Server down"
            );
        }
    } else {
        $out = array(
            'status' => 0,
            'error_msg' => "Action not supported"
        );
    }

    echo json_encode($out);
    exit;
}

if (!$_GET['slug']) {
    header("Location: /");
    exit;
}


$slug = $_GET['slug'];


$is_cek_user = 0;

$operator = $app->grab_data("$api_url/operator/detail/$slug");
$data_operator = json_decode($operator, true);
if ($data_operator['status']) {
    if ($data_operator['status'] == 1) {
        $data_op = $data_operator['data'];
        $op_id = $data_op['id'];
        $op_name = $data_op['name'];
        $op_image = $data_op['image'];
        $op_helper = $data_op['helper'];
        $op_tipe = $data_op['tipe'];
        $op_slug = $data_op['slug'];
        $op_petunjuk = $data_op['petunjuk'];
        $produk  = $app->curl_post_json("$api_url/produk/list-by-operator", array("id_operator" => $op_id));
        $metode = $app->grab_data("$api_url/topup/metode/list");
        // echo $op_name;
        if ($app->contains(strtolower($op_name), "mobile legend") or $app->contains(strtolower($op_name), "mobilelegend")  or $app->contains(strtolower($op_name), "free fire") or $app->contains(strtolower($op_name), "freefire")) {
            $is_cek_user = 1;
        } else {
            $is_cek_user = 0;
        }
    } else {
        $msg = $data_operator['error_msg'];
        echo $msg;
        exit;
    }
} else {
    echo "Server down";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require_once("_/head.php");
    ?>
    <title>Topup <?php echo $op_name ?> - Murah dan Terpercaya</title>
</head>

<body class="mbg-primary">
    <div class="flex flex-col justify-between h-full">
        <div>
            <?php require_once("_/header.php") ?>
            <div class=" sm:pt-[160px] pt-[70px]">
                <div class="container-xxl flex flex-col md:flex-row gap-8">
                    <div class="md:w-2/4" bis_skin_checked="1">
                        <div>
                            <div class=" p-4 text-white h-max m-shadow ">
                                <img src="<?php echo $op_image ?>" class="mx-auto rounded-lg h-28 mb-[10px]" alt="">
                                <div class="p-6">
                                    <!-- <h1 class="text-xl">Mobile Legends</h1>
                                    <p>Top Up Diamond Mobile Legends</p>
                                    <ul class="my-3 ml-5 text-sm">
                                        <li>1. Masukkan ID (SERVER)</li>
                                        <li>2. Pilih Nominal Diamond</li>
                                        <li>3. Pilih Metode Pembayaran</li>
                                        <li>4. Tulis nomor WhatsApp yg benar!</li>
                                        <li>5. Klik Order Now & lakukan Pembayaran</li>
                                        <li>6. Diamond masuk otomatis ke akun Anda.</li>
                                    </ul>
                                    <p class="text-center font-bold text-gold">Top Up Buka 24 Jam</p> -->
                                    <?php echo $op_helper  ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full flex flex-col gap-8" bis_skin_checked="1">
                        <!-- form akun  -->
                        <div class="m-shadow h-max mb-4">
                            <div class=" mb-2 p-4 shadow-md">
                                <p class="text-lg text-white">Masukkan Data Akun</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 p-4">
                                <div class="w-full">
                                    <label for="" class="text-white">ID</label>
                                    <input type="text" id="user_id" placeholder="Masukkan ID" class="form-input">
                                </div>
                                <div class="w-full <?php echo $op_tipe == 2 ? "flex flex-col" : "hidden"  ?>">
                                    <label for="" class="text-white">Server</label>
                                    <input type="text" id="server" placeholder="Masukkan Server" class="form-input">
                                </div>
                            </div>
                            <div class="px-4 pb-4">
                                <button class="bg-fifth rounded-lg px-4 py-1 text-white focus:outline-none hover:bg-sky-700  delay-75 duration-200 ease-in" onclick="show_modal('modalPetunjuk')">Petunjuk</button>
                            </div>
                        </div>
                        <!-- end form akun  -->

                        <!-- pilih nominal  -->
                        <div class=" h-max mb-4 m-shadow">
                            <div class="shadow-md mb-2 p-4">
                                <p class="text-lg text-white">Pilih Nominal</p>
                            </div>
                            <div class="">
                                <div class="grid grid-cols-2 md:grid-cols-3 py-6 px-4 gap-4">
                                    <!-- <template x-for="d in denom"> -->
                                    <?php
                                    if (isset($produk)) {
                                        $data_pr = json_decode($produk, true);
                                        if (isset($data_pr['status'])) {
                                            if ($data_pr['status'] == 1) {
                                                $data_row = $data_pr['data'];
                                                $i = 0;
                                                foreach ($data_row as $row) {
                                    ?>
                                                    <div class="col-lg-4 rounded-2xl" onclick="select_diamond(<?php echo $i ?>, <?php echo $row['price'] + $row['price_add'] ?>, <?php echo $row['id'] ?>,'<?php echo $row['name'] ?>' )">
                                                        <div id="produk-<?php echo $i ?>" class="h-100 m-shadow rounded-2xl  p-4 cursor-pointer flex flex-col text-color-fourth color-secondary">
                                                            <div class="row px-2">
                                                                <div class="col" bis_skin_checked="1">
                                                                    <div class="row" bis_skin_checked="1">
                                                                        <div class="col text-sm font-semibold" bis_skin_checked="1"> <?php echo $row['name'] ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" bis_skin_checked="1">
                                                                        <div class="col text-xs" bis_skin_checked="1"><?php echo $app->idr($row['price'] + $row['price_add']) ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                    <?php
                                                    $i++;
                                                }
                                            } else {
                                            }
                                        } else {
                                        }
                                    }
                                    ?>
                                    <!-- </template> -->
                                </div>
                            </div>
                        </div>
                        <!-- end pilih nominal  -->

                        <!-- pembayaran  -->
                        <div class=" shadow-md h-max mb-4">
                            <div class="m-shadow mb-2 p-4">
                                <p class="text-lg text-white">Pilih Metode Pembayaran</p>
                            </div>
                            <div class="p-4">
                                <div class="">
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
                                            } else {
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="hidden">
                                    <div class="accordion" id="accordionExample">
                                        <div class="accordion-item bg-white border border-gray-200">
                                            <h2 class="accordion-header mb-0" id="headingOne">
                                                <button class="
                                                            accordion-button
                                                            relative
                                                            flex
                                                            items-center
                                                            w-full
                                                            py-4
                                                            px-5
                                                            text-base text-gray-800 text-left
                                                            bg-white
                                                            border-0
                                                            rounded-none
                                                            transition
                                                            focus:outline-none
                                                        " type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                    Accordion Item #1
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body py-4 px-5">
                                                    <strong>This is the first item's accordion body.</strong> It is shown by default,
                                                    until the collapse plugin adds the appropriate classes that we use to style each
                                                    element. These classes control the overall appearance, as well as the showing and
                                                    hiding via CSS transitions. You can modify any of this with custom CSS or overriding
                                                    our default variables. It's also worth noting that just about any HTML can go within
                                                    the <code>.accordion-body</code>, though the transition does limit overflow.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end pembayaran  -->

                        <!-- no wa  -->
                        <div class=" shadow-md h-max mb-4">
                            <div class="m-shadow mb-2 p-4">
                                <p class="text-lg text-white">No WhatsApp</p>
                            </div>
                            <div class="p-4">
                                <div class="form-group mb-3" bis_skin_checked="1">
                                    <span class="text-red-500">* <i>wajib</i></span>
                                    <input class="form-input" placeholder="08xxxxxxxxxx (wajib)" type="number" name="no_wa" id="no_wa" required="" value="<?php echo isset($data_user['hp']) ? $data_user['hp'] : "" ?>" <?php echo isset($data_user['hp']) ? "disabled" : "" ?>>
                                </div>
                                <ul>
                                    <li><span class="text-red-500">* <i></i></span><small class="text-color-fourth"><i>Pastikan nomor yang di input benar. Nomor akan di hubungi jika terjadi masalah</i></small></li>
                                    <li><span class="text-red-500">* <i></i></span><small class="text-color-fourth">Inputkan nomor hp emoney jika memilih metode pembayaran emoney selain Qris</small></li>
                                </ul>
                            </div>
                        </div>
                        <!-- end no wa  -->

                        <button class="btn-me" id="btn_order">Order Sekarang</button>

                    </div>
                </div>
            </div>
        </div>

        <div class="mt-[100px]"></div>
        <?php
        require_once("_/general.php");
        require_once("_/footer.php");
        ?>
        <!-- modal  -->
        <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered relative w-auto pointer-events-none">
                <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                    <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                        <h5 class="text-xl font-medium leading-normal text-gray-800" id="exampleModalScrollableLabel">
                            Modal title
                        </h5>
                        <button type="button" class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body relative p-4">
                        <p>This is a vertically centered modal.</p>
                    </div>
                    <div class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md">
                        <button type="button" class="inline-block px-6 py-2.5 bg-purple-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-purple-700 hover:shadow-lg focus:bg-purple-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-purple-800 active:shadow-lg transition duration-150 ease-in-out" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out ml-1">
                            Save changes
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div id="modal_username" class="w-full md:w-[400px]  hidden md:left-[50%]  top-[30%]  p-6" role="alert" aria-live="assertive" data-bs-autohide="false" aria-atomic="true" bis_skin_checked="1">
            <div class="rounded-lg bg-sky-600">
                <div class=" p-4 m-shadow" bis_skin_checked="1">
                    <img src="" alt="" class="me-2" height="18">
                    <strong class="me-auto text-white">Informasi</strong>
                    <!-- <small class="text-gray-200">close after 3s</small> -->
                    <span class="me-2"><i class="bx bx-loader bx-spin font-size-16 align-middle me-2 btn-close" data-bs-dismiss="toast" aria-label="Close"></i></span>

                    <!-- <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button> -->
                </div>
                <div class="p-4 text-white " bis_skin_checked="1">
                    <div class="flex">
                        <!-- <h1 class="text-lg">Mohon menunggu</h1> -->
                        <table class="w-full">
                            <tbody class="divide-y divide-gray-300 align-center">
                                <tr class="items-center ">
                                    <td class="py-4 text-[13px] font-semibold" colspan="2">Detail pembayaran</td>
                                </tr>
                                <tr class="group">
                                    <td class="item-table text-right w-[50%]">
                                        Nick Name :
                                    </td>
                                    <td class="item-table text-left w-[50%]">
                                        <div class="text-sm ml_nick_name font-bold">

                                        </div>
                                    </td>
                                </tr>
                                <tr class="group">
                                    <td class="item-table text-right ">
                                        Nominal :
                                    </td>
                                    <td class="item-table text-left">
                                        <div class="text-sm ml_nominal">

                                        </div>
                                    </td>
                                </tr>
                                <tr class="group">
                                    <td class="item-table text-right ">
                                        Produk :
                                    </td>
                                    <td class="item-table text-left">
                                        <div class="text-sm ">
                                            <?php echo $op_name ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="group">
                                    <td class="item-table text-right ">
                                        Item :
                                    </td>
                                    <td class="item-table text-left">
                                        <div class="text-sm ml_item">

                                        </div>
                                    </td>
                                </tr>
                                <tr class="group">
                                    <td class="item-table text-right ">
                                        Metode Pembayaran :
                                    </td>
                                    <td class="item-table text-left">
                                        <div class="text-sm ml_metode">

                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex flex-row gap-2 mt-4">
                        <button class="bg-slate-500 px-4 py-2 text-white rounded-lg" onclick="hide_modal_username()">Close</button>
                        <button class="bg-sky-500 px-4 py-2 text-white rounded-lg" id="btn_order_username">Order</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- https://codepen.io/f7deat/pen/JjROpPv -->
        <div class="fixed z-10 top-1/4 bottom-0 w-full left-0 hidden" id="modalPetunjuk">
            <div class="flex items-center justify-center min-height-100vh pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed md:inset-0 top-25 transition-opacity">
                    <div class="absolute md:inset-0 bg-gray-900 opacity-75" />
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block align-center bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-2 sm:pb-4">
                        <img src="<?php echo $op_petunjuk ?>" class="w-full h-full" alt="">
                    </div>
                    <div class="bg-gray-200 px-4 py-3 text-right">
                        <button type="button" class="py-2 px-4 bg-gray-500 text-white rounded hover:bg-gray-700 mr-2" onclick="hide_modal('modalPetunjuk')"><i class="fas fa-times"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end modal  -->


    </div>

    <script src="<?php echo $c_url ?>/assets/app.js?v=<?php echo rand() ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/index.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var code = "";

        })
        var tmp_produk_id = 100;
        var tmp_method_id = 100;
        var sub_emoney = $(".sub-emoney")
        var harga = 0
        var tipe_cek = '<?php echo $is_cek_user ?>'
        var modal_username = $("#modal_username")
        var ml_nick_name = $(".ml_nick_name")
        var ml_nominal = $(".ml_nominal")
        var ml_item = $(".ml_item")
        var ml_metode = $(".ml_metode")
        var modalPetunjuk = $("#modalPetunjuk")

        //setup data form
        var metode_id = 0
        var produk_id = 0
        var user_id = $("#user_id")
        var server_id = $("#server")
        var wa = $("#no_wa")
        var btn_order = $("#btn_order")
        var btn_order_username = $("#btn_order_username")
        var file = '/topup/<?php echo $op_slug ?>'
        var produk_name
        var metode_name
        var produk_nominal = 0
        var is_order = 0
        var cookie_tujuan = '<?php echo "$key_cookie_tujuan-$op_id" ?>'
        var cookie_server = '<?php echo "$key_cookie_serverid-$op_id" ?>'

        function show_modal_username() {
            ml_item.html(produk_name)
            ml_metode.html(metode_name)
            ml_nominal.html(formatRupiah(produk_nominal.toString(), "Rp, "))
            modal_username.removeClass("hidden")
            modal_username.addClass("fixed");
        }

        var tmp_tujuan = getCookie(cookie_tujuan)
        var tmp_server = getCookie(cookie_server)
        user_id.val(tmp_tujuan)
        server_id.val(tmp_server)

        function hide_modal_username() {
            if (is_order == 1) {
                //sedang ada order
                toast("Sedang di proses. mohon menunggu ..")
            } else {
                modal_username.removeClass("fixed")
                modal_username.addClass("hidden");
                release_btn(btn_order, "Order")
            }

        }

        btn_order.on("click", function() {
            if (user_id.val() == "") {
                toast("Mohon inputkan UserID")
                return
            }
            if (tipe_cek == 2 && server_id.val() == "") {
                toast("Mohon inputkan ServerID")
                return
            }
            if (produk_id == 0) {
                toast("Mohon pilih nominal item")
                return
            }
            if (metode_id == 0) {
                toast("Mohon pilih metode pembayaran")
                return
            }
            if (wa.val() == "") {
                toast("Mohon inputkan No Wa")
                return
            }

            setCookie(cookie_tujuan, user_id.val(), 1000)
            setCookie(cookie_server, server_id.val(), 1000)

            // toast("hello")
            if (tipe_cek == 1) {
                //lakukan cek username
                loading_btn(btn_order)
                cek_username()
            } else {
                //langsung proses order
                // loading_btn(btn_order)
                order()
                // release_btn(btn_order, "Order")
            }
        })

        btn_order_username.on("click", function() {
            loading_btn(btn_order_username)
            order()

        })

        async function cek_username() {
            try {
                var res = await curl_post(file, {
                    operator: '<?php echo $op_name ?>',
                    tujuan: user_id.val() + server_id.val(),
                    act: "cek"
                })
                if (res.status == 1) {
                    ml_nick_name.html(res.data)
                    show_modal_username()
                } else {
                    var msg = res.error_msg
                    toast(msg)
                    release_btn(btn_order, "Order")
                }
            } catch (error) {
                // console.log(error)
                toast(error.statusText)
                release_btn(btn_order, "Order")
            }
        }

        async function order() {
            is_order = 1
            loading_btn(btn_order)
            try {
                var res = await curl_post(file, {
                    hp: wa.val(),
                    metode: metode_id,
                    produk: produk_id,
                    user_id: user_id.val(),
                    server_id: server_id.val(),
                    act: "order"
                })
                if (res.status == 1) {
                    toast(res.message)
                    var trxid = res.data
                    setTimeout(() => {
                        window.location.replace("/order/" + trxid);
                    }, 2000);
                } else {
                    var msg = res.error_msg
                    toast(msg)
                }
            } catch (error) {
                // console.log(error)
                toast(error.statusText)
            }
            is_order = 0
            release_btn(btn_order_username, "Order")
            release_btn(btn_order, "Order")
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

        function get_fee(sub) {
            var fee = sub.attr("data-fee")
            // console.log(fee)
            return fee
        }

        function select_diamond(id, nominal, id_produk, produk_name_as) {
            produk_id = id_produk
            produk_name = produk_name_as
            produk_nominal = nominal
            console.log(produk_name)
            var fee_emoney = parseInt(get_fee(sub_emoney))
            var sum_fee_emoney = fee_emoney + nominal
            sub_emoney.html(toIdr(String(sum_fee_emoney), "Rp, "))
            var selector_dm = $("#produk-" + id)
            if (tmp_produk_id != 100) {
                var tmp_selector_dm = $("#produk-" + tmp_produk_id)
                tmp_selector_dm.removeClass("m-shadow-active")
                tmp_selector_dm.addClass("m-shadow");
                tmp_produk_id = id
                selector_dm.removeClass("m-shadow")
                selector_dm.addClass("m-shadow-active");
            } else {
                tmp_produk_id = id
                // console.log(selector_dm)
                selector_dm.removeClass("m-shadow")
                selector_dm.addClass("m-shadow-active");
            }

        }

        function toIdr(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, "").toString(),
                split = number_string.split(","),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                var separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
            return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
        }
    </script>
</body>

</html>