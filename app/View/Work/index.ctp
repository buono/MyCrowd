<h1>送信フォームサンプル</h1>

<?php foreach ($results as $data):?>
<table>
    <tr>
        <td>
            <?php echo $data["Work"]["id"];?>
        </td>
        <td>
            <?php echo $data["Work"]["title"];?>
        </td>
        <td>
            <?php echo $data["Work"]["detail"];?>
        </td>
    </tr>
</table>
<?php endforeach;?>
