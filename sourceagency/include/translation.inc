<?php
 class translation {
     var $tolang;

     function translation($lang = "English") {
          $this->tolang = $lang;
     }
   
     function translate($phrase) {
	  include($this->tolang."-lang.inc");
	  return($tmp);
     }
 }
?>
