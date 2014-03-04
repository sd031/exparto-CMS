  <script type="text/javascript">
    var FileBrowserDialogue = {
      init: function() {
        // Here goes your code for setting your custom things onLoad.
      },
      mySubmit: function (URL) {
        var win = tinyMCEPopup.getWindowArg('window');
  
        // pass selected file path to TinyMCE
        win.document.getElementById(tinyMCEPopup.getWindowArg('input')).value = URL;
  
        // are we an image browser?
        if (typeof(win.ImageDialog) != 'undefined') {
          // update image dimensions
          if (win.ImageDialog.getImageData) {
            win.ImageDialog.getImageData();
          }
          // update preview if necessary
          if (win.ImageDialog.showPreviewImage) {
            win.ImageDialog.showPreviewImage(URL);
          }
        }
  
        // close popup window
        tinyMCEPopup.close();
      }
    }
  
    tinyMCEPopup.onInit.add(FileBrowserDialogue.init, FileBrowserDialogue);
  
    $().ready(function() {
      var elf = $('#elfinder').elfinder({
        // set your elFinder options here
        rememberLastDir:false ,
        url: "<?php echo $this->jsDir.'/elfinder2/php/connector.php?url='.Yii::app()->request->baseUrl ?>&dir=<?php echo $dir?>",  // connector URL
        getFileCallback: function(url) { // editor callback
          FileBrowserDialogue.mySubmit(url); // pass selected file path to TinyMCE 
        }
      }).elfinder('instance');      
    });
  </script>
  <div id="elfinder"></div>