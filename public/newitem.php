<?php

use App\Exceptions\ClassException;
use App\Lib\Logger;
use App\Models\Category;
use App\Models\Item;

require_once(__DIR__ . "/../app/bootstrap.php");

// (A) If the user is not logged in, return the user to the login page
if (!$session->isLoggedIn()) {
    header("Location: index.php");
    die();
}





if(isset($_POST['submit'])) {
	// (B) Create a new item object and create a new database record with the given information
    try {

        $user_id = $session->getUser()->get('id');

        $item = new Item(
            $user_id,
            $_POST['cat'],
            $_POST['name'],
            $_POST['price'],
            $_POST['description'],
            $_POST['date']
        );

        $item = $item->create();

    } catch (ClassException $e) {
        Logger::getLogger()->critical("Error creating item: ", ['exception' => $e]);
        header("Location: newitem.php?error=creation_failed");
        die();
    }

	header("Location: addimages.php?id=" . $item->get('id'));
	die();
} else {
	require(__DIR__ . "/../app/Layouts/header.php");
	?>
	<h1>Add a new item</h1>
	<strong>Step 1</strong> - Add your item details.
	<p>
		<?php

		if(isset($_GET['error'])) {
			try {
				$errorMsg = Item::displayError($_GET['error']);
			} catch(ClassException $e) {
				Logger::getLogger()->error("Invalid error code: ", ['exception' => $e]);
				die();
			}
			echo $errorMsg;
		}
		?>
	</p>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<table>
			<tr>
				<td>Category</td>
				<td>
					<select name="cat">
						<?php

						try {
						// (C) Retrieve all categories from the database and return them as an array of category objects
                            $categories = Category::all(null, 'cat');
						
						} catch(ClassException $e) {
							Logger::getLogger()->critical("No results returned: ", ['exception' => $e]);
							echo "No results returned";
							die();
						}
						//Display each categories properties
						/* @var $category \App\Models\Category */
						foreach($categories as $category) {
							echo "<option value='" . $category->get('id') . "'>" . $category->get('cat') . "</option>";
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Item name</td>
				<td><input type="text" name="name"></td>
			</tr>
			<tr>
				<td>Item description</td>
				<td><textarea name="description" rows="10" cols="50"></textarea></td>
			</tr>
			<tr>
				<td>Ending date</td>
				<td>
					Date:<input type="datetime-local" name="date"><br>
				</td>
			</tr>
			<tr>
				<td>Price</td>
				<td><?php echo CONFIG_CURRENCY; ?><input type="text" name="price"></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" name="submit" value="Post!"></td>
			</tr>
		</table>
	</form>

	<?php
}

require(__DIR__ . "/../app/Layouts/footer.php");

?>