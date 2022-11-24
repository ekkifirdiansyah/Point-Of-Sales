<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Barcode product <?=$row->barcode?></title>
    </head>
    <body>
        <?php
            $generator =new Picqer\Barcode\BarcodeGeneratorPNG();
            echo '<img src="data:image/png:base64,' . base64_encode($generator->getbarcode($row->barcode. $generator::TYPE_CODE_128)) . '" style="width:250px">';
        ?>
        <br><br>
        <?=$row->barcode?>
    </body>
</html>