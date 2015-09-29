<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if(!isset($laytoanbo)){
    array_shift($bandokhuvuc);
}
?>
<table style="width:100%;">
    <tr>
        <td>#</td>
        <td>Khu vực</td>
        <td>Mã vận đơn</td>
        <td>Địa chỉ </td> 
    </tr>
    <?php
        $i=0;
        foreach ($bandokhuvuc as $val){
            $i++;
    ?>
        <tr>
            <td><?=$i?></td>
            <td><?php
            if(isset($laytoanbo)){
                echo $val['khuvuc'];
            }else{
                echo $diachicum;
            }
            ?></td>
            <td><?=$val['idorder']?></td>
            <td><?=$val['address']?></td> 
        </tr>
    <?php
    }
    ?>
    
</table>
<style>
    td{
        border: 1px solid;
        padding: 5px;
    }
</style>