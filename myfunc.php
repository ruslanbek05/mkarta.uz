<?php
$my_yil = date("Y");
$my_oy = date('m');
$my_kun = date('d');
$my_timestamp = time();
		
		

function is_in_group($user_id,$group_name){

$db = JFactory::getDbo();
$query = $db
    ->getQuery(true)
    ->select('COUNT(*)')
    ->from($db->quoteName('#__user_usergroup_map','a'))
    ->join('LEFT', $db->quoteName('#__usergroups', 'b') . ' ON ' . $db->quoteName('a.group_id') . ' = ' . $db->quoteName('b.id'))
    ->where($db->quoteName('a.user_id') . " = " . $db->quote($user_id))
    ->where($db->quoteName('b.title') . " = " . $db->quote($group_name));

// Reset the query using our newly populated query object.
$db->setQuery($query);
$count = $db->loadResult();

if($count > 0){
	return TRUE;
}else{
	return FALSE;
}


/*

//check if user is a doctor
$user = Factory::getUser();
$groups = $user->get('groups');

	$doctormi = FALSE;
	$db_user_group_name = JFactory::getDbo();
	if($user->get('id') > 0){

		//signed in user
		foreach ($groups as $group)
		{
			$query_user_group_name = $db_user_group_name
			    ->getQuery(true)
			    ->select('title')
			    ->from($db_user_group_name->quoteName('#__usergroups'))
			    ->where($db_user_group_name->quoteName('id') . " = " . $db_user_group_name->quote($group));

			$db_user_group_name->setQuery($query_user_group_name);
			$result_user_group_name = $db_user_group_name->loadResult();
			if($result_user_group_name == "Doctor"){
				//echo "bu doctor";die;
				$doctormi = TRUE;
				return $doctormi;
			}
		}

	}
	
return $doctormi;
*/
}


		
//$file = 'images/themb/1/1/1/index.html';
//create_file_with_dir($file);

function create_file_with_dir_index_html($file){
	

$pathToFile = $file;
$fileName = basename($pathToFile);
$folders = explode('/', str_replace('/' . $fileName, '', $pathToFile));

$currentFolder = '';
foreach ($folders as $folder) {
    $currentFolder .= $folder . DIRECTORY_SEPARATOR;
    if (!file_exists($currentFolder)) {
        mkdir($currentFolder, 0755);
        file_put_contents($currentFolder . '/index.html', '1');
    }
}
//file_put_contents($pathToFile, '1');
return TRUE;
}


function create_file_with_dir($file){
	

$pathToFile = $file;
$fileName = basename($pathToFile);
$folders = explode('/', str_replace('/' . $fileName, '', $pathToFile));

$currentFolder = '';
foreach ($folders as $folder) {
    $currentFolder .= $folder . DIRECTORY_SEPARATOR;
    if (!file_exists($currentFolder)) {
        mkdir($currentFolder, 0755);
    }
}
file_put_contents($pathToFile, 'test');
return TRUE;
}
		
		
//echo "salom";die;
/**
 * Define the number of blocks that should be read from the source file for each chunk.
 * For 'AES-128-CBC' each block consist of 16 bytes.
 * So if we read 10,000 blocks we load 160kb into memory. You may adjust this value
 * to read/write shorter or longer chunks.
 */
define('FILE_ENCRYPTION_BLOCKS', 10000);






//$fileName = __DIR__.'/testfile.txt';
//$fileName = '../mkarta.uz_protected/images/2020/02/15/110/1581753665_IMG-20181229-174142.jpg';
//$key = 'rnn987654321+-';

//encryptFile($fileName, $key, '2' . $fileName . '');

//decryptFile($fileName . '', $key, '3'.$fileName . '');


//make_thumb($fileName, '1.jpg');







/*
//echo image
$filename = "2file.jpg";
$handle = fopen($filename, "rb");
$contents = fread($handle, filesize($filename));
fclose($handle);
 
header("content-type: image/jpeg");
 
echo $contents;
*/









/**
 * Encrypt the passed file and saves the result in a new file with ".enc" as suffix.
 * 
 * @param string $source Path to file that should be encrypted
 * @param string $key    The key used for the encryption
 * @param string $dest   File name where the encryped file should be written to.
 * @return string|false  Returns the file name that has been created or FALSE if an error occured
 */
function encryptFile($source, $key, $dest)
{
    $key = substr(sha1($key, true), 0, 16);
    $iv = openssl_random_pseudo_bytes(16);

    $error = false;
    if ($fpOut = fopen($dest, 'w')) {
        // Put the initialzation vector to the beginning of the file
        fwrite($fpOut, $iv);
        if ($fpIn = fopen($source, 'rb')) {
            while (!feof($fpIn)) {
                $plaintext = fread($fpIn, 16 * FILE_ENCRYPTION_BLOCKS);
                $ciphertext = openssl_encrypt($plaintext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                // Use the first 16 bytes of the ciphertext as the next initialization vector
                $iv = substr($ciphertext, 0, 16);
                fwrite($fpOut, $ciphertext);
            }
            fclose($fpIn);
        } else {
            $error = true;
        }
        fclose($fpOut);
    } else {
        $error = true;
    }

    return $error ? false : $dest;
}















/**
 * Dencrypt the passed file and saves the result in a new file, removing the
 * last 4 characters from file name.
 * 
 * @param string $source Path to file that should be decrypted
 * @param string $key    The key used for the decryption (must be the same as for encryption)
 * @param string $dest   File name where the decryped file should be written to.
 * @return string|false  Returns the file name that has been created or FALSE if an error occured
 */
function decryptFile($source, $key, $dest)
{
    $key = substr(sha1($key, true), 0, 16);

    $error = false;
    if ($fpOut = fopen($dest, 'w')) {
        if ($fpIn = fopen($source, 'rb')) {
            // Get the initialzation vector from the beginning of the file
            $iv = fread($fpIn, 16);
            while (!feof($fpIn)) {
                $ciphertext = fread($fpIn, 16 * (FILE_ENCRYPTION_BLOCKS + 1)); // we have to read one block more for decrypting than for encrypting
                $plaintext = openssl_decrypt($ciphertext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                // Use the first 16 bytes of the ciphertext as the next initialization vector
                $iv = substr($ciphertext, 0, 16);
                //header("content-type: image/jpeg");
 
                fwrite($fpOut, $plaintext);
                //echo $plaintext;
                
            }
            fclose($fpIn);
            
        } else {
            $error = true;
        }
        fclose($fpOut);
        
    } else {
        $error = true;
    }

    return $error ? false : $dest;
}











function make_thumb($src, $dest) {
	
	$desired_width = 100;
	
	//echo($src);die;

    /* read the source image */
    $source_image = imagecreatefromjpeg($src);
    $width = imagesx($source_image);
    $height = imagesy($source_image);

    /* find the "desired height" of this thumbnail, relative to the desired width  */
    $desired_height = floor($height * ($desired_width / $width));
    if($desired_height > 100){
		$desired_height = 100;
		$desired_width = floor($width * ($desired_height / $height));
	}

    /* create a new, "virtual" image */
    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

    /* copy source image at a resized size */
    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

    /* create the physical thumbnail image to its destination */
    imagejpeg($virtual_image, $dest);
}

