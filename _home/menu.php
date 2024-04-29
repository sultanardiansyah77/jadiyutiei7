<?php
$menu_home = array(
    array(
        "url" => "/user/home",
        "name" => "Home"
    ),
    array(
        "url" => "/user/transaksi",
        "name" => "Transaksi"
    ),
    array(
        "url" => "/user/deposit",
        "name" => "Deposit"
    ),
);

foreach ($menu_home as $row) {
?>
    <a href="<?php echo $row['url'] ?>">
        <div class="<?php echo $path == $row['name'] ? "bg-sky-800" : "bg-fifth" ?> p-4 text-gray-200 font-semibold rounded hover:bg-sky-800 delay-75 duration-200 "><?php echo $row['name'] ?></div>
    </a>
<?php
}
?>