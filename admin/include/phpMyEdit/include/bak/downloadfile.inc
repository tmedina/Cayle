<?php
/******************************************************************************************
*
* downloadZipFile.php
*
* Variable expected from calling script: $filename (fullpath of zipped file)
*
* This script opens and reads a zip file ($filename) and sends 1 MB chunk at a time 
* to the browser which is necessary to allow large file download.
* This will force browser to open up a download dialog box instead of caching and 
* displaying content
*
* Performance testing: download 266 MB file from lnx715 to local drive 
*                      in 2 min 2 sec (transfer rate 2.18 MB/sec)
*
* When?           Who?       What?            
* ----------------------------------------------------------------------------------------  
* 09-27-2006      wdstar     Initial
*  
*******************************************************************************************/

/*************************************************************
* Function definitions   
*
*/
function readfile_chunked($filename,$retbytes=true)
{
   // set bytes size per chunk
   //
   $chunksize = 1*(1024*1024);
   
   // initialize our variables
   //
   $buffer = '';
   $cnt =0;
   
   // open the file in read mode
   //
   $handle = fopen($filename, 'rb');
   if ($handle === false)
   {
      return false;
   }
   
   // read the file until eof
   //
   while (!feof($handle))
   {
   
      //read a chunk
      //
      $buffer = fread($handle, $chunksize);
   
      //send the chunk to browser
      //
      echo $buffer;
   
      //flush/reset the chunk
      //
      flush();
   
      //increment the size read/sent
      //
      if ($retbytes)
      {
         $cnt += strlen($buffer);
      }
      
   } //while
   
   // close file
   //
   $status = fclose($handle);
   if ($retbytes && $status)
   {
      // return num. bytes delivered like readfile() does.   
      //
      return $cnt; 
   }

   return $status;
}


/*******************************************************************************************************
*
*  MAIN 
*
*******************************************************************************************************/

// Get file to be downloaded
//$filename = $_GET['file'];
//$filename = '/iodata/o07300/RM01/in/rmall.zip';

// Make sure filename is populated from calling script
//
if(!$filename)
{
   echo "ERROR: No filename specified. Please try again.";
}
// Make sure file exists and is readable
//
elseif (!is_readable( $filename ) )
{
   echo "ERROR: Unable to read file: $filename.";
}
else
{   
   // fix for IE caching or PHP bug issue
   header("Pragma: public");
   // set expiration time
   header("Expires: 0"); 
   // browser must download file from server instead of cache
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
   // force download dialog
   header("Content-Type: application/force-download");
   header("Content-Type: application/octet-stream");
   header("Content-Type: application/download");
   // use the Content-Disposition header to supply a recommended filename and
   // force the browser to display the save dialog.
   header("Content-Disposition: attachment; filename=" . basename($filename) . ";");
   header("Content-Transfer-Encoding: binary");
   header("Content-Length: " . filesize($filename));

   // set time limit to 0 to allow downloading big files
   //
   set_time_limit(0);
   
   // Send a chunk of files at a time to browser
   //
   readfile_chunked(urldecode($filename), false);

   //exit(); do not exit here to give choice to calling script to delete zip file or not
}

?>
