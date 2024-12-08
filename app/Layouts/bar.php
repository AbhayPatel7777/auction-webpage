
<?php
use App\Exceptions\ClassException;
use App\Lib\Logger;
use App\Models\Category;

try{
    $catObjs = Category::all('cat');
} catch(ClassException $e) {
    Logger::getLogger()->critical ("No results returned: ",['exception'=>$e]);
    echo "No results returned";
    die();
}

?>
<h1>Categories</h1>
<ul>
    <li><a href='index.php'>View All</a></li>

    <?php
    foreach ($catObjs as $catobj): ?>
        <li>
            <a href='index.php?id=<?php echo $catobj->get('id')
            ?>'><?php echo $catobj->get('cat')?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>