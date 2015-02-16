<?php

/**
 * File downloader
 */
include('config.php');
include_once 'controller/user-controller.php';
include_once 'controller/product-controller.php';
include_once 'controller/database-controller.php';
include_once 'common/common-function.php';
include_once '../layout/header.php';
//error_reporting(0);
php_session_start();
ob_clean();

// get product info
$product        = new ProductController();
$product_info   = $product->get(array('token' => $_GET['token']));
$error          = false;
$makeZip        = false;

foreach ($product_info as $key => $value) 
{
    
    $filename   = '';
    $file       = '';
    $filename   = $product_info[$key]['download_link'];
    $file       = $config['uploads_folder'] . '/' . $filename;
    
    $ext        = pathinfo($filename, PATHINFO_EXTENSION);
    $zip_types  = explode('|', $config['zip_types']);

    if(in_array($ext, $zip_types)){
        
        $makeZip            = true;
        $zipname            = 'products'.  strtotime(date("Y-m-d")).'.zip';
        $downloadFilename   = $zipname;
        $zip                = createZip($zipname);
    }
    else{
        $zipname            = $file;
        $downloadFilename   = $filename;
    }
   
   
    chmod($file, 0777);
   
    if (headers_sent())
    {
       
        $error .= 'HTTP header already sent <br>';
    }
    else if(!file_exists($file))
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
        $error .=  'File not found  <br>';
    }
    else if (!is_readable($file))
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
        $error .=  'File not readable  <br>';
    }
    else if(!$fopen = @fopen($file,"rb")){
        $error .=  'Error occured while reading file  <br>';
    }

    if(!$error){
        
        if($makeZip){
            
            $zip->addFile($file);
            $zip->close();
        }
        //download file
        download($downloadFilename, $zipname, $makeZip, $fopen, $product_info);
        
    }

}


/*
 *  create zip of products
 */
function createZip($zipname){

    $zip = new ZipArchive;
    $zip->open($zipname, ZipArchive::CREATE);
    return $zip;
}

/*
 * Download file
 */
function download($downloadFilename, $zipname, $makeZip, $fopen, $product_info){
   
    $db = new DataBaseController();
    
    $db->update_downlaod_count(array(
        'product_id' => $product_info[0]['id'],
        'purchase_id' => $product_info[0]['purchase_id'],
        ));

//    header("Pragma: public");
//    header("Expires: 0");
//    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//    header("Cache-Control: public");
//    header("Content-Description: File Transfer");
//    header("Content-Type: application/force-download",FALSE);
//    header("Content-Type: application/x-zip-compressed",TRUE);
//    header("Content-Type: application/download", FALSE); 
//    header("Content-Disposition: attachment; filename=\"$downloadFilename");
//    header("Content-Transfer-Encoding: binary");
//    header("Content-Length: " . filesize($zipname) + 100);
     header("Content-Disposition: attachment; filename=\"$downloadFilename");
     header('Content-type: application/zip');
     header("Content-Length: " . filesize($zipname) + 100);
    
    
    if($makeZip){
        
        readfile($zipname);
        unlink($zipname);
    }
    else{
        
        fpassthru($fopen);			
        fclose($fopen);
    }
    
  //$product->redirect('index.php?page=payment_history');
    exit();
}

ob_clean();

if(!empty($error)){
    include_once 'layout/header.php';
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">File downloading: </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-8" style="display: inline">
                        <div class="row">
                            <p>File download page</p>
                        </div>
                        <div id="message"><?php echo $error;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } 
include_once 'layout/footer.php';
?>
