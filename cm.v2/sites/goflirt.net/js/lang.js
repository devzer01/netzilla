
function setlang(lang){ 

	var xhr; 
    try { xhr = new XMLHttpRequest(); }                 
    catch(e) 
    {    
      xhr = new ActiveXObject("Msxml2.XMLHTTP.3.0");
    } 
 
    xhr.onreadystatechange  = function()
    { 
         if(xhr.readyState  == 4)
         {
             // alert(xhr.responseText);
         }
    }; 

   var url="modules/setlang.php?lang="+lang;
   url=url+"&sid="+Math.random();
   xhr.open("GET", url,  true); 
   xhr.send(null); 
}