<?php

  //frontend purpose data
  define('SITE_URL', 'http://localhost/vietchill/');
  define('ABOUT_IMG_PATH',SITE_URL.'images/about/');
  define('CAROUSEL_IMG_PATH',SITE_URL.'images/carousel/');
  define('FACILITIES_IMG_PATH',SITE_URL.'images/facilities/');
  define('ROOMS_IMG_PATH',SITE_URL.'images/rooms/');
  define('USERS_IMG_PATH',SITE_URL.'images/users/');
  define('SERVICES_IMG_PATH',SITE_URL.'images/services/');

  //backend upload process needs this data
  define('UPLOAD_IMAGE_PATH',$_SERVER['DOCUMENT_ROOT'].'/vietchill/images/');
  define('ABOUT_FOLDER','about/');
  define('CAROUSEL_FOLDER','carousel/');
  define('FACILITIES_FOLDER','facilities/');
  define('ROOMS_FOLDER','rooms/');
  define('USERS_FOLDER','users/');
  define('SERVICES_FOLDER','services/');

	function adminLogin() {
		session_start();
		if(!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)){
			echo"<script>window.location.href='index.php'</script>";
			exit;
		}
	}

	function redirect($url) {
		echo "<script>window.location.href='$url'</script>";
		exit;
	}

	function alert($type, $msg) {
		$bs_class = ($type == 'success') ? 'alert-success' : 'alert-danger';
		echo <<<alert
			<div class="alert $bs_class alert-dismissible fade show custom-alert" role="alert">
				<strong class="me-3">$msg</strong>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		alert;
	}

  function uploadImage($image, $folder) {
    $valid_mime = ['image/jpeg', 'image/png', 'image/webp'];
    $img_mime = $image['type'];

    if (!in_array($img_mime, $valid_mime)) {
      return 'Không hỗ trợ định dạng này!';
    } else if (($image['size'] / (1024 * 1024)) > 2) {
      return 'Vui lòng chọn hình ảnh dưới 2MB!';
    } else {
      $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
      $rname = 'IMG_'.random_int(11111,99999).".$ext";
      
      $img_path = UPLOAD_IMAGE_PATH . $folder . $rname;
      
      // Create directory if it doesn't exist
      $dir = UPLOAD_IMAGE_PATH . $folder;
      if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
      }
      
      if (move_uploaded_file($image['tmp_name'], $img_path)) {
        return $rname;
      } else {
        return 'Tải lên hình ảnh thất bại!';
      }
    }
  }

  function deleteImage($image, $folder) {
    if($image == 'default.jpg') {
      return true; // Don't delete default image
    }
    
    $img_path = UPLOAD_IMAGE_PATH.$folder.$image;
    if(file_exists($img_path) && unlink($img_path)){
      return true;
    } else {
      return false;
    }
  }

  function uploadSVGImage($image,$folder) {
    $valid_mime = ['image/svg+xml'];
    $img_mime = $image['type'];

    if(!in_array($img_mime,$valid_mime)){
      return 'Không hỗ trợ định dạng này!';
    }
    else if(($image['size']/(1024*1024))>1){
      return 'Vui lòng chọn hình ảnh dưới 1MB!';
    }
    else{
      $ext = pathinfo($image['name'],PATHINFO_EXTENSION);
      $rname = 'IMG_'.random_int(11111,99999).".$ext";

      $img_path = UPLOAD_IMAGE_PATH.$folder.$rname;
      
      // Create directory if it doesn't exist
      $dir = UPLOAD_IMAGE_PATH.$folder;
      if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
      }
      
      if(move_uploaded_file($image['tmp_name'],$img_path)){
        return $rname;
      }
      else{
        return 'Tải lên hình ảnh thất bại!';
      }
    }
  }

	function uploadUserImage($image) {
    $valid_mime = ['image/jpeg','image/png','image/webp'];
    $img_mime = $image['type'];

    if(!in_array($img_mime,$valid_mime)){
      return 'inv_img'; // invalid image mime or format
    }
    else if(($image['size']/(1024*1024)) > 2) {
      return 'inv_size'; // image too large
    }
    else {
      $ext = pathinfo($image['name'],PATHINFO_EXTENSION);
      $rname = 'IMG_'.random_int(11111,99999).".jpeg";

      $img_path = UPLOAD_IMAGE_PATH.USERS_FOLDER.$rname;

      // Create users directory if it doesn't exist
      $users_dir = UPLOAD_IMAGE_PATH.USERS_FOLDER;
      if (!is_dir($users_dir)) {
        if (!mkdir($users_dir, 0777, true)) {
          return 'upd_failed';
        }
      }

      // Create image resource based on file type
      $img = false;
      if($ext == 'png' || $ext == 'PNG') {
        $img = imagecreatefrompng($image['tmp_name']);
      }
      else if($ext == 'webp' || $ext == 'WEBP') {
        $img = imagecreatefromwebp($image['tmp_name']);
      }
      else if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'JPG' || $ext == 'JPEG') {
        $img = imagecreatefromjpeg($image['tmp_name']);
      }

      if($img === false) {
        return 'upd_failed';
      }

      // Convert to JPEG and save
      if(imagejpeg($img, $img_path, 75)){
        imagedestroy($img); // Free memory
        return $rname;
      }
      else{
        if($img) imagedestroy($img);
        return 'upd_failed';
      }
    }
  }

  // Function to create default user image if it doesn't exist
  function createDefaultUserImage() {
    $default_path = UPLOAD_IMAGE_PATH.USERS_FOLDER.'default.jpg';
    
    if (!file_exists($default_path)) {
      // Create users directory if it doesn't exist
      $users_dir = UPLOAD_IMAGE_PATH.USERS_FOLDER;
      if (!is_dir($users_dir)) {
        mkdir($users_dir, 0777, true);
      }
      
      // Create a simple default image (100x100 gray square)
      $img = imagecreate(100, 100);
      $bg = imagecolorallocate($img, 200, 200, 200);
      $text_color = imagecolorallocate($img, 100, 100, 100);
      
      // Add text
      imagestring($img, 3, 25, 40, 'USER', $text_color);
      
      // Save as JPEG
      imagejpeg($img, $default_path, 75);
      imagedestroy($img);
    }
  }

  // Call this function to ensure default image exists
  createDefaultUserImage();
?>