<?php
$url=Yii::app()->controller->createUrl('/site/suscribe');
Yii::app()->clientScript->registerScript
(
  'newsletter',
  '
  function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
  };
  
  $("#suscribe-action").click(
    function() {      
      if($("#news-suscribe").val()=="") return false;
      if(!isValidEmailAddress($("#news-suscribe").val())) {
        alert("Incorrect e-mail address");  
      } else
      {
      	 $.ajax({
      	   type: "POST",
      	   async : false,
      		 url: "'.$url.'",
      		 data : { 
      				"email" : $("#news-suscribe").val()
      		 },  					
      		 success: function (data) {
      		    $("#ns-box").fadeOut(400);              
      		    alert($("#news-suscribe").val()+" added to subscribe list");
              $("#news-suscribe").val("");      
      		 }
      	});      
      }      
      return false;
    }
  );   
  ',
	CClientScript::POS_READY 
);
?>
        
<div class="box-small">
<div class="box-small-top"></div>
<div class="box-small-mid">
  <h2><?php echo Yii::t('frontend','STR_Newsletter')?></h2>
  <form id="ns-block">
    <input type="text" value="" name="email" placeholder="<?php echo Yii::t('frontend','STR_Email')?>" id="news-suscribe"/>
    <a href="#" class="sign-up" id="suscribe-action"></a>
  </form>
</div>
<div class="box-small-bottom"></div>              
</div>        