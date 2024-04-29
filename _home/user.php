<div class="md:w-2/4 w-full flex flex-col p-8 m-shadow gap gap-4 h-max">
    <div class="flex flex-row justify-between w-full ">
        <div class="">
            <p class="text-slate-400 text-lg">Saldo</p>
            <h2 class="text-gray-300 text-2xl font-bold"> <?php echo $app->idr($data_user['saldo']) ?></h2>
            <a href="/user/deposit-order" >
                <div class="px-2 py-1 bg-fifth mt-2 text-gray-200 text-lg rounded-lg hover:bg-sky-700  delay-75 duration-200 ease-in">
                    Deposit saldo
                </div>
            </a>
        </div>
    </div>
    <div class="pt-8">
        <table class="table-auto w-full">
            <tbody class="">
                <tr class="items-center">
                    <td class="font-semibold text-xl text-gray-300" colspan="2">Informasi User</td>
                </tr>
                <tr class="group border-b-2 border-gray-600">
                    <td class="text-gray-400 pb-4 pt-4 font-bold">
                        Nama :
                    </td>
                    <td class="text-gray-400 pb-4 pt-4">
                        <?php echo $data_user['nama'] ?>
                    </td>
                </tr>
                <tr class="group border-b-2 border-gray-600 ">
                    <td class="text-gray-400 pb-4 pt-4 font-bold">
                        Email :
                    </td>
                    <td class="text-gray-400 pb-4 pt-4">
                    <?php echo $data_user['email'] ?>
                    </td>
                </tr>
                <tr class="group border-b-2 border-gray-600 p-4 ">
                    <td class="text-gray-400 pb-4 pt-4 font-bold">
                        Hp :
                    </td>
                    <td class="text-gray-400 pb-4 pt-4">
                    <?php echo $data_user['hp'] ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>