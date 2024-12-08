<!DOCTYPE html>
<html>
<head>
	<title>Reset Auction Notifications</title>
</head>
<body>
<?php
// Set the variables for the database access:
require_once(__DIR__ . "/../app/bootstrap.php");

$dbc = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);

if(isset($_POST['submit'])) {
	$query = "UPDATE `items` SET `notified` = '0' WHERE `notified` = '1';";
	$dbc->query($query);
}

?>

<h2 style="text-align: center">Reset Auction Notifications</h2>

<p>Click on the button below to reset the 'notified' column for all items to 0. This is useful if you wish to re-test the ProcessAuction script to test PayPal functionality.</p>

<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
	<input type="submit" name="submit" value="Reset Auction Notifications">
</form>

<br><br>

<table border="1" width="75%" cellspacing="2" cellpadding="2" align="center">
	<tr>
		<th>ID (auto_increment)</th>
		<th>User ID</th>
		<th>Cat ID</th>
		<th>Name</th>
		<th>Price</th>
		<th>Description</th>
		<th>Date</th>
		<th>Notified</th>
		<th>Image</th>
	</tr>

	<?php
	$query = "SELECT * from `items` ORDER BY id";
	$comments = $dbc->query($query)->fetchAll(PDO::FETCH_ASSOC);

	foreach($comments as $row) {
		$query = "SELECT * from `images` WHERE item_id = '{$row['id']}' LIMIT 1";
		$img = $dbc->query($query)->fetch(PDO::FETCH_ASSOC);

		?>
		<tr align="center" valign="top">
			<td align="center" valign="top"><?php echo $row['id'] ?></td>
			<td align="center" valign="top"><?php echo $row['user_id'] ?></td>
			<td align="center" valign="top"><?php echo $row['cat_id'] ?></td>
			<td align="center" valign="top"><?php echo $row['name'] ?></td>
			<td align="center" valign="top"><?php echo $row['price'] ?></td>
			<td align="center" valign="top"><?php echo $row['description'] ?></td>
			<td align="center" valign="top"><?php echo $row['date'] ?></td>
			<td align="center" valign="top"><?php echo $row['notified'] ?></td>
			<td align="center" valign="top"><img src="imgs/<?php echo $img['name'] ?>" width="145"
			                                     height="145"></td>
		</tr>
		<?php
	}
	?>
</table>

<br><br>

<?php
$dbc = null;
?>
</body>
</html>