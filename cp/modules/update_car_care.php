<?php
	require_once('../../classes/class.db.php');
	require_once('../vars.php');
	require_once('../functions/upload_image.php');

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$id = $_POST['prod-id'];
		$sub_cat_id = $_POST['sub-cat-id'];
		$prod_name = $_POST['prod-name'];
		$prod_price = $_POST['prod-price'];
		$prod_weight = $_POST['prod-weight'];
		$image_alt_text = $_POST['image_alt_text'];

		$prod_desc = mysqli_real_escape_string($GLOBALS['dbc'], trim($_POST['prod-desc']));
		$phy_desc = mysqli_real_escape_string($GLOBALS['dbc'], trim($_POST['phy-desc']));
		$img1 = $_FILES['img1']['name'];
		$img2 = $_FILES['img2']['name'];
		$img3 = $_FILES['img3']['name'];

		$page = $_POST['page'];

		$query = sprintf("UPDATE 
		                    car_care 
		                       SET 
		                       sub_cat_id = %d,
		                       name = '%s', 
		                       price = %d, 
		                       weight = '%s', 
		                       phy_desc = '%s',
		                       prd_desc = '%s',
		                       image_alt_text = '%s', 
		                       slug = '%s' ", 
			                   $sub_cat_id,
			                   $prod_name,
			                   $prod_price, 
			                   $prod_weight, 
			                   $phy_desc, 
			                   $prod_desc,
			                   $image_alt_text,
			                   $slug);

		if(!empty($img1)) {
			$img = str_replace(' ', '_', $img1);
            $query .= ", image1 = '$img'";
        }

        if(!empty($img2)) {
        	$img = str_replace(' ', '_', $img2);
            $query .= ", image2 = '$img'";
        }

        if(!empty($img3)) {
        	$img = str_replace(' ', '_', $img3);
            $query .= ", image3 = '$img'";
        }

        $query .= " WHERE id = $id LIMIT 1";

		$uploaded = mysqli_query($GLOBALS['dbc'], $query);
		

         $return_url = 'http://' . $_SERVER['HTTP_HOST'].'/cp/index.php?tbl=car_care&cat_id=4&page='.$page;
		if ($uploaded) {
			update_image($_FILES['img1'], $_FILES['img2'], $_FILES['img3']);

			$query = sprintf("UPDATE all_products SET product_name = '%s' WHERE product_id = %d AND table_name = '%s'", $prod_name, $id, 'car_care');

			$update_all_prod = mysqli_query($GLOBALS['dbc'], $query);

			if ($update_all_prod) {
				header('Location: ' . $return_url);
				//echo 'Products uploaded and updated';
			}
		}
	}
?>