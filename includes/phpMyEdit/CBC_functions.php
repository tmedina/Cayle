 <?php
/*
* Simple to use implementation of Blowfish encryption and decryption
* the cipher block chaining function I wrote implement a  very simple
* CBC which includes no XOR or other obfuscation or complication.
* There are three functions included Eencrypt, Edecrypt and maxi-pad.
* Eencrypt encrypts a string, Edecrypt decrypts the sting and maxi-pad
* pads the string before encryption to be sure each block to be encrypted
* is exactly 8 bytes long.
* This code while simple.. was written by Eric Westphall (icandothat@gmail.com)
* if you have questions please feel free to email me.
*/



  
include_once("Blowfish.php");



   function Eencrypt($cipher, $plaintext){
   /*
   * Function: Eencrypt
   * Arguments: obj $cipher, string $plaintext
   * Purpose: Encrypt a sting of any length
   * Return value: returns the encrypted string.
   */
      $ciphertext = "";

      $paddedtext = maxi_pad($plaintext);
      $strlen = strlen($paddedtext);
    
      for($x=0; $x< $strlen; $x+=8){
         $piece = substr($paddedtext,$x,8);
         $cipher_piece = $cipher->encrypt($piece);
         $encoded = base64_encode($cipher_piece); 
         $ciphertext = $ciphertext.$encoded;       
      }

   return $ciphertext;  


   }


   function Edecrypt($cipher,$ciphertext){
   /*
   * Function: Edecrypt
   * Arguments: obj $cipher, string $ciphertext
   * Purpose: decrypt an encrypted sting of any length
   * Return value: returns the decrypted string.
   */  
       $plaintext = "";

      $chunks = split("=",$ciphertext);
      
      $ending_value = count($chunks) ;

      for($counter=0 ; $counter < ($ending_value-1) ; $counter++)
      {
            $chunk = $chunks[$counter]."=";
            $decoded = base64_decode($chunk);
            
            $piece = $cipher->decrypt($decoded);
            
            $plaintext = $plaintext.$piece;

      }
      return $plaintext;

   }
  
  
  
  
   function maxi_pad($plaintext){
   /*
      * Function: maxi_pad
      * Arguments: string $plaintext
      * Purpose: add enough blank chars to the end of a string
      *          such that $srting%8 = 0
      * Return value: returns the padded string.
   */
      $str_len = count($plaintext);
      //plain text must be div by 8
      $pad_len = $str_len % 8;
      
      for($x=0; $x<$pad_len; $x++){
         $plaintext = $plaintext." ";
      }
      
      $str_len = count($plaintext);
      if($srt_len % 8){

         print "padding function is not working\n";
      }else{
         return $plaintext;
      }
      return (-1);

   }
?>
