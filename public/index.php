<?php
use App\Models\Item;
require_once(__DIR__ . '/../app/bootstrap.php');

if(isset($_GET['id'])){
    $validid = pf_validate_number($_GET['id'], "value", CONFIG_URL);
} else {
    $validid = 0;
}
//Load header.php file
require_once(__DIR__ .'/../app/Layouts/header.php');

if($validid == 0){
    $items = Item::find("date > NOW()");
} else {
    $items = Item::find("date > NOW() AND cat_id = $validid");
}
?>
    <h1>Items available</h1>
    <table cellpadding='5'>
        <tr>
            <th>Image</th>
            <th>Item</th>
            <th>Bids</th>
            <th>Price</th>
            <th>End Date For this Items</th>
        </tr>
<?php

if(!$items) {
    echo "<tr><td colspan='4'>No items!</td></tr>";
} else {
    foreach($items as $item){
        $item->getImages();
        echo "<tr>";
        if(!$item->get('imageObjs')) {
            echo "<td>No images</td>";
        } else {
            $img = $item->get('imageObjs');
            $firstImg = array_shift($img);
            echo "<td><img src='imgs/" . $firstImg->get('name') . "' width='100'></td>";
        }
        echo "<td>";
        echo "<a href='itemdetails.php?id= {$item->get('id')}'>{$item->get('name')}</a>";
        echo "</td>";

        echo "<td>";

        $item->getBids();

        if(!$item->get('bidObjs')) {
            echo "0";
        } else {
            echo count($item->get('bidObjs'));
        }
        echo "</td>";
        echo "<td>" . CONFIG_CURRENCY;
        if(!count($item->get('bidObjs'))){
            echo sprintf('%.2f', $item->get('price'));
        } else {
            $itemBids = $item->get('bidObjs');
            $highestBid = array_shift($itemBids);
            echo sprintf('%.2f', $highestBid->get('amount'));
        }
        echo "</td>";

        echo "<td>" . date("D js F Y g.iA", strtotime($item->get('date'))) . "</td>";
        echo "</tr>";
    }
}

echo "</table>";



App\Lib\Database::getConnection();

// Include the code to require the FOOTER.PHP here
require_once(__DIR__ .'/../app/Layouts/footer.php');
?>