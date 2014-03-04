<?php     

$this->title=Yii::t('backend', 'STR_CONTENT');
$this->pageTitle=$this->title.' - '.Yii::app()->name;

Yii::app()->clientScript->registerScriptFile($this->jsDir.'/jsTree/jquery.jstree.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile($this->jsDir.'/jquery.ba-hashchange.min.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerScript
(
  'getTypeMod',
  '
  function getTypeMod(type) {
    var mods = '.CJavaScript::encode($contentTypes->getModulesTypesArr()).';
    return mods[type]; 
  } 
  ',
	CClientScript::POS_END 
);

//language tabs init
if(count($langs>0))
Yii::app()->clientScript->registerScript
(
  'lang-tabs',
  '
    var open_state=[];
    var select_state=[];
      
    $("#lang-tabs").tabs();
      
    tmp = $.cookie("lang_tab");
    if(tmp >= 0) {$("#lang-tabs").tabs().tabs("select", parseInt(tmp));};
    
    $("#lang-tabs").bind("tabsshow", function(event, ui) {    
      $("#content-tree ul").html("<li><a href=# class=jstree-loading><ins class=jstree-icon>&nbsp;</ins></a></li>"); 
      $.jstree._reference("#content-tree").data.core.to_open=open_state[$("#lang-tabs .ui-tabs-active a").attr("id")]; 
      $.jstree._reference("#content-tree").data.ui.to_select=select_state[$("#lang-tabs .ui-tabs-active a").attr("id")];          
      $.jstree._reference("#content-tree").load_node(-1, function () {$.jstree._reference("#content-tree").reopen();});
      
      $.cookie("lang_tab", ui.index, {});
    });
       
    $("#lang-tabs").bind("tabsselect", function(event, ui) {    
       $.jstree._reference("#content-tree").save_opened();      
       open_state[$("#lang-tabs .ui-tabs-active a").attr("id")]=$.jstree._reference("#content-tree").data.core.to_open;
       select_state[$("#lang-tabs .ui-tabs-active a").attr("id")]=$.jstree._reference("#content-tree").data.ui.to_select;         
    });     
    
    $("#lang-tabs").show();
  ',
	CClientScript::POS_READY 
);

if(isset($_GET['id']) && $_GET['id']>0)
Yii::app()->clientScript->registerScript
(
  'autoLoadGET',
  ' 
  $("#auto-load").val('.$_GET['id'].');      
  ',
	CClientScript::POS_LOAD 
);
 
Yii::app()->clientScript->registerScript
(
  'autoLoad',
  ' 
  var qtree=$.jstree._reference("#content-tree");
  var hash=window.location.hash;
  $("#auto-load").val(hash.replace("#",""));
  
  //$("#checkHash").val(1); 
  
  function autoLoad(hash,r) {
    $("#auto-load").val(hash.replace("#",""));     
    if(r==true)
      qtree.refresh(-1);
  }
  
  if(window.location.hash.replace("#","")>0) {
    autoLoad(window.location.hash,false);
  }
    
  $(window).hashchange( function(){
      //if($("#checkHash").val()==1)
        autoLoad(window.location.hash,true);   
  
  })
    
  ',
	CClientScript::POS_LOAD 
);  


/*
Yii::app()->clientScript->registerScript
(
  'loadTreeDetail',
  '
  function loadTreeDetails(obj) {
  	 $.ajax({
  	   type: "POST",
  	   async : false,
  		 url: "'.$this->createUrl('loadDetails').'",
  		 data : { 
  				"id" : obj.attr("id")
  		 }, 
  	 	 beforeSend:function(){$("#structure-loader").addClass("loading-blue");},
       complete:function(){$("#structure-loader").removeClass("loading-blue");},   					
  		 success: function (data) {
              $("#main-column").hide();
              $("#main-column").html(data);
              $("#main-column").fadeIn(300);
  			}
  	});
  } 
  ',
	CClientScript::POS_LOAD 
);
*/

Yii::app()->clientScript->registerScript
(
  'content_delete',
  ' 
  function contentDelete()
  {
      var obj=$.jstree._reference("#content-tree").get_selected();
      var conf="";       
      if($(obj).hasClass("jstree-leaf")==false) {
        conf="<br /><br /<br /><input class=\"field checkbox\" type=\"checkbox\" id=\"confirm-del\"><label class=\"choice\" for=\"confirm-del\">'.Yii::t('backend','STR_CONFIRM').'</label>";   
      }    
      var del="<div>'.Yii::t('backend','QST_DELETE').'"+conf+"</div>";      
      $(del).dialog({modal:true,resizable:false,title:"'.Yii::t('backend','STR_CONFIRM').'",
      buttons:{
      "'.Yii::t('backend','STR_NO').'":function(){$(this).dialog("close");},
      "'.Yii::t('backend','STR_YES').'":function()
      {     
        $(this).dialog("close");                
        $("#tree-disable,#menu-disable,#main-column,#small-column").fadeOut(400);
        if(!$(this).find("#confirm-del").is(":checked") && (obj).hasClass("jstree-leaf")==false) return false;                     
        $.jstree._reference("#content-tree").remove();
        notice("'.Yii::t('backend','STR_DELETED_NODE').'");
      }
      }
      ,
      close: function(ev, ui) {$(this).remove();}                    
      });    
  } 
  ',
	CClientScript::POS_READY
);

Yii::app()->clientScript->registerScript
(
  'errorMsg',
  '
  function errorMsg(title,text) {
    var msg="<div>"+text+"</div>";    
    //$("#main-column").html(msg);
    $(msg).dialog({title:title,width:600,buttons:{"'.Yii::t('backend','STR_CLOSE').'":function(){$(this).dialog("close");}}});   
  } 
  ',
	CClientScript::POS_END
);

Yii::app()->clientScript->registerScript
(
  'loadTypeContent',
  '
  function treeBoxCollapse()
  {
    $(".three-col-mid,.three-column").removeClass("three-column-expand"); 
    $(".col2").removeClass("col2-expand");
    $(".col1,.col3").show();  
  }
  
  function treeBoxExpand()
  {
    $(".col1,.col3").hide();
    $(".three-col-mid,.three-column").addClass("three-column-expand"); 
    $(".col2").addClass("col2-expand");      
  }  
  
  function loadTypeContent(obj,sel_type,edit) {
    $("#structure-loader").addClass("loading-blue");         
    $("#main-column").empty();
    $("#small-column").empty();
    treeBoxCollapse();
    $("html,body").animate({scrollTop: $("#page-content-wrapper").offset().top}, 200);       
  	 $.ajax({
  	   type: "POST",
  		 url: "'.Yii::app()->request->getBaseUrl().'/"+getTypeMod(sel_type)+"/admin/type"+sel_type,
  		 dataType:"json",
  		 async: false,
  		 data: { 
          "id": $(obj).attr("id")?$(obj).attr("id"):-1,	
          "root_id": $(obj).attr("root_id")?$(obj).attr("root_id"):-1,		
          "type": sel_type, 
          '.((count($langs)>0)?'lng: $("#lang-tabs .ui-tabs-active a").attr("id").replace("lang-", ""),':'').'           
          "edit": edit       
  		 }, 
  	 	 beforeSend:function(){},
       complete:function(){},  					
  		 success: function (data) {  
					if(data.status==1) {
					  $("#main-column,#small-column").hide();
            $("#main-column").html(data.main);  
            $("#small-column").html(data.small); 	
            $("#main-column,#small-column").fadeIn(300,function(){$("#structure-loader").removeClass("loading-blue");});  			
					}
					else {
            $("#tree-disable,#menu-disable").fadeOut(300);				
						$.jstree._reference("#content-tree").refresh(obj);
						$("#structure-loader").removeClass("loading-blue");
						errorMsg("Load content data error", "Status 0");
					}					           
  		},
  		error: function (xhr, ajaxOptions, thrownError) {
          $("#tree-disable,#menu-disable").fadeOut(300);				
					$.jstree._reference("#content-tree").refresh(obj); 
          $("#structure-loader").removeClass("loading-blue"); 
          errorMsg(xhr.status+" "+thrownError,xhr.responseText);      
      }                         
  	});
  } 

  ',
	CClientScript::POS_READY 
);

Yii::app()->clientScript->registerScript
(
  'typeContentAction',
  '
  function typeContentAction(action,sel_type,reload) {
      var submit=false;
      var obj = $.jstree._reference("#content-tree").get_selected().eq(0);
      var parent= $.jstree._reference("#content-tree")._get_parent(obj);
      
      $("html,body").animate({scrollTop: $("#page-content-wrapper").offset().top}, 200);
      jQuery.ajax({
        type: "POST",
  		  dataType:"json",  
        data: {
            "main":$("#main-column form").serialize(),
            "small":$("#small-column form").serialize(),
            "type":sel_type,
            "id":$(obj).attr("id")                  
        },   
    	 	beforeSend:function(){$("#structure-loader").addClass("loading-blue");$("#small-column,#main-column").fadeOut(300);},
        complete:function(){$("#structure-loader").removeClass("loading-blue");},          
        url:"'.Yii::app()->request->getBaseUrl().'/"+getTypeMod(sel_type)+"/admin/"+"type"+sel_type+"/"+action,
        cache:false,
    		success: function (data) {  
  					if(data.status==1) {
              $("#tree-disable,#menu-disable").fadeOut(300, 
                function() {                  
                  $("#main-column,#small-column").empty();
                  
                  if(reload==true) {
                    $("#auto-load").val(data.id);
                    $.jstree._reference("#content-tree").refresh(parent); 
                    if(action=="create")
                      notice("'.Yii::t('backend','STR_CREATED_NODE').'"); 
                    else
                      notice("'.Yii::t('backend','STR_UPDATED_NODE').'"); 
                  }
                                                          
                  treeBoxExpand();                                                       
                }
              );                 
              
              if(reload==false) {
              if(action=="create")
              {
                $.jstree._reference("#content-tree").refresh(obj);
                notice("'.Yii::t('backend','STR_CREATED_NODE').'"); 
              }
              else
              {                                                      
                $.jstree._reference("#content-tree").refresh(parent);
                notice("'.Yii::t('backend','STR_UPDATED_NODE').'"); 
              }   
              }                  
              submit=false;    
                         				
  					}
  					else {
              $("#main-column,#small-column").fadeIn(300);		
   
              submit=true;  
              //errorMsg("Content action data error", "Status 0");
  					}
    		},
    		error: function (xhr, ajaxOptions, thrownError) {  
          $("#tree-disable,#menu-disable").fadeOut(300, 
          function() {
    		    $.jstree._reference("#content-tree").refresh(parent);
          });     
          errorMsg(xhr.status+" "+thrownError,xhr.responseText);             
    		}    		
        });        
        return submit;
  } 
  ',
	CClientScript::POS_READY 
);

Yii::app()->clientScript->registerScript
(
  'contentControls',
  '
    $("body").delegate(
    "#content-create",
    "click",
    function(){
      var sel_type=$("#types-list option:selected").val();
      $("#sub-frm-btn").click();      
      return typeContentAction("create",sel_type,true);        
    }              
    );
    
    $("body").delegate(
    "#content-create-close",
    "click",
    function(){
      var sel_type=$("#types-list option:selected").val();
      $("#sub-frm-btn").click();      
      return typeContentAction("create",sel_type,false);        
    }              
    );    

    $("body").delegate(
    "#content-update",
    "click",
    function(){
      var obj=$.jstree._reference("#content-tree").get_selected().eq(0);
      $("#sub-frm-btn").click();
      return typeContentAction("update",obj.attr("rel"),true);
    }              
    );

    $("body").delegate(
    "#content-update-close",
    "click",
    function(){
      var obj=$.jstree._reference("#content-tree").get_selected().eq(0);
      $("#sub-frm-btn").click();     
      return typeContentAction("update",obj.attr("rel"),false);
    }              
    );

    $("body").delegate(
    "#content-delete",
    "click",
    function(){
      contentDelete();      
      treeBoxExpand();
      return false; 
    }              
    );
    
    $("body").delegate(
    "#content-cancel,#content-cancel-win",
    "click",
    function(){
      $("#tree-disable,#menu-disable,#main-column,#small-column").fadeOut(300);
      var obj=$.jstree._reference("#content-tree").get_selected().eq(0);      
      if($(obj).attr("rel")!="structure")
        $.jstree._reference("#content-tree").refresh(obj);
      else         
        $.jstree._reference("#content-tree").refresh(-1);    
        
      treeBoxExpand();     
      return false;          
    }
    );      
  ',
	CClientScript::POS_READY 
);

Yii::app()->clientScript->registerScript
(
  'tree_menu_init',
  '
  $("body").delegate(
    "#tree-menu #cancel-new-node",
    "click",
    function(){
      $("#tree-disable,#menu-disable").fadeOut(300);
      $("#new-node-type").hide(300);
      return false;
    }              
  );      
      
  $("body").delegate(
    "#tree-menu #ok-new-node",
    "click",
    function(){
      $("#structure-loader").addClass("loading-blue"); 
      $("#new-node-type").hide(300,function(){   
        var sel_type=$("#types-list option:selected").val();                
        var obj = $.jstree._reference("#content-tree").get_selected().eq(0);    
        if(sel_type=="structure")    
          $.jstree._reference("#content-tree").create(false, "last", { "attr" : { "rel" : sel_type}, "data":"'.Yii::t('backend','STR_NEW_NODE').'"},null,true);
        else    
          $.jstree._reference("#content-tree").create(null, "last", { "attr" : { "rel" : sel_type}, "data":"'.Yii::t('backend','STR_NEW_NODE').'"},null,true); 
        $("#structure-loader").removeClass("loading-blue");     
        loadTypeContent(obj,sel_type,0);
      });
      return false;
    }              
  );   
  $("body").delegate(
    "#tree-menu #edit-node",
    "click",
    function(){
      $("#structure-loader").addClass("loading-blue");    
      var obj = $.jstree._reference("#content-tree").get_selected();
      if(obj.attr("mod")=="false") return false;
      $("#menu-disable").fadeTo(100,0.2);      
      $("#tree-disable").fadeTo(300,0.2, 
      function(){           
          $("#structure-loader").removeClass("loading-blue");                        
          loadTypeContent(obj,obj.attr("rel"),1);
        }
      );
      return false;      
    }  
  );  
  $("body").delegate(
    "#tree-menu #new-node",
    "click",
    function(){
      var obj = $.jstree._reference("#content-tree").get_selected().eq(0);
      $("#tree-disable,#menu-disable").fadeTo(300,0.2);
      $("#new-node-type").show(300); 
      $("#ok-new-node").hide();
      $("#types-list").empty();
      jQuery.ajax({
        type: "POST",
  		  dataType:"json",        
        data: {
           "id":obj.attr("id") ? obj.attr("id") : -1                 
        },   
        beforeSend:function(){$("#types-list").addClass("loading-blue");},
        complete:function(){$("#types-list").removeClass("loading-blue");},
        url:"'.$this->createUrl('loadListOptions').'",
        cache:false,
    		success: function (data) {  
  					if(data.status==1) {
              $("#types-list").html(data.options);       
              $("#ok-new-node").show();                                                           				
  					}
    		},
    		error: function (xhr, ajaxOptions, thrownError) {  
          $("#tree-disable,#menu-disable,#new-node-type").fadeOut(300);
          errorMsg(xhr.status+" "+thrownError,xhr.responseText); 
    		}    		
      });       

      return false;
    }              
  );  
  $("body").delegate(
    "#tree-menu #close-all",
    "click",
    function(){
      var obj = $.jstree._reference("#content-tree").get_selected();
      if(obj.attr("id")>-1) {
        $.jstree._reference("#content-tree").close_all(obj);
      } 
      return false;
    }              
  );   
  $("body").delegate(
    "#tree-menu #open-all",
    "click",
    function(){
      var obj = $.jstree._reference("#content-tree").get_selected();
      if(obj.attr("id")>-1) {
        $.jstree._reference("#content-tree").open_all(obj);
      } 
      return false;
    }              
  );   
  $("body").delegate(
    "#tree-menu #delete-node",
    "click",
    function(){
      var obj = $.jstree._reference("#content-tree").get_selected();
      //if ($.jstree._reference("#content-tree")._get_parent(obj)!=-1) {
        contentDelete();
      //} 
      return false;
    }              
  );  
  $("body").delegate(
    "#tree-menu #move-up",
    "click",
    function(){
      var obj = $.jstree._reference("#content-tree").get_selected().eq(0);
      var prev =  obj.prev();
      if (prev.length && $.jstree._reference("#content-tree")._get_parent(obj)!=-1) {
        $.jstree._reference("#content-tree").move_node(obj, prev, "before");

      }
      return false;
    }              
  );  
  $("body").delegate(
    "#tree-menu #move-down",
    "click",
    function(){
      var obj = $.jstree._reference("#content-tree").get_selected().eq(0);
      var next =  obj.next();
      if (next.length && $.jstree._reference("#content-tree")._get_parent(obj)!=-1) {
        $.jstree._reference("#content-tree").move_node(obj, next, "after");

      }
      return false;
    }              
  );     
  $("body").delegate(
    "#tree-menu #refresh-tree",
    "click",
    function(){
      $.jstree._reference("#content-tree").refresh(-1);
      return false;
    }              
  )       
  ',
	CClientScript::POS_READY 
);  

//content tree
Yii::app()->clientScript->registerScript
(
  'tree_init',
  '
	$("#content-tree")
		.jstree({ 
			plugins : [ "themes", "json_data", "crrm", "dnd", "types", "ui", "cookies"],
			json_data : { 
				ajax : {
					url : "'.$this->createUrl('renderTree').'",
					data : function (n) {                              
            return { 
                id : n.attr ? n.attr("id") : 0, 
                '.((count($langs)>0)?'lng: $("#lang-tabs .ui-tabs-active a").attr("id").replace("lang-", ""),':'').'            
                sec: n.attr ? n.attr("sec") : "",
                auto: $("#auto-load").val()>0 ? $("#auto-load").val() : ""  
            };
					}				
				}
			},
      '.(Yii::app()->user->checkAccess('Cms_content.Default.MoveNode')?'':'
      crrm : {
        "move" : {
          "check_move" : function (m) {
            return false;
          }
        }
      },    
      ').'  
      themes: {
  			theme : "apple",
  			dots : true,
  			icons : true        
      },
		  '.$contentTypes->getTreeSettings($this->cssDir.'/icons').'
			ui : {
			   disable_selecting_children:true,
         select_limit:1     
			},
			core : {  
        '.($defaultRoot?'"initially_open":["'.$defaultRoot.'"],':'').'  
				animation: 200,
				load_open: true,
        html_titles : true,
			  strings: {"loading":"'.Yii::t('backend', 'STR_LOADING').'...", "new_node" : "'.Yii::t('backend', 'STR_NEW_PAGE_TITLE').'" }
			}
		})
		/*bind("create.jstree", function (e, data) { 
			$.post(
				"'.$this->createUrl('newNode').'", 
				{ 
				  data : { 
  					id : data.rslt.parent.attr("id"), 
  					position : data.rslt.position,
  					title : data.rslt.name,
  					type : data.rslt.obj.attr("rel"),				
					}
				}, 
				function (r) {     
					if(r.status) {
						$(data.rslt.obj).attr("id", r.id);
						$(data.rslt.obj).attr("rel", r.type);
		        $.jstree._reference("#content-tree").set_text(data.rslt.obj, r.title); 						
					}
					else {
						$.jstree.rollback(data.rlbk);
					}
				},"json"
			);			
		})*/
		.bind("remove.jstree", function (e, data) {
  			data.rslt.obj.each(function () {
  			  var parent=data.inst._get_parent(this);
  				$.ajax({
  					async : false,
  					type: "POST",
  					url: "'.$this->createUrl('removeNode').'",
  					data : { 
  						id : this.id
  					}, 
  					success : function (r) {
  						if(!r.status) {
  							data.inst.refresh(parent);
  						}
  					}
  				});
  			});
		})
		.bind("rename.jstree", function (e, data) {
			$.post(
				"'.$this->createUrl('renameNode').'", 
				{ 
					id : data.rslt.obj.attr("id"),
					title : data.rslt.new_name
				}, 
				function (r) {
					if(!r.status) {
						$.jstree.rollback(data.rlbk);
					}
				}
				,"json"
			);
		})
		.bind("move_node.jstree", function (e, data) {
			data.rslt.o.each(function (i) {
  			$.post(
  				"'.$this->createUrl('moveNode').'", 
  				{ 
  				  data : { 
  						id : data.rslt.o.attr("id"), 
  						ref : data.rslt.r.attr("id"), 
  						position : data.rslt.cp + i,
  						title : data.rslt.name,
  						pos : data.rslt.p,
  						copy : data.rslt.cy ? 1 : 0, 
  						type : $(this).attr("rel")			
  					}
  				}, 
  				function (r) {
						if(!r.status) {
							$.jstree.rollback(data.rlbk);
						}
						else {
							//$(data.rslt.oc).attr("id", r.id);
							//if(data.rslt.cy && $(data.rslt.oc).children("UL").length) {
							//	data.inst.refresh(data.inst._get_parent(data.rslt.oc));
							//}
              notice("'.Yii::t('backend','STR_CHANGED_POSITION').'");
						}
  				}
          ,"json"
  			);	
  		});	
		})
    .bind("dblclick.jstree", function (event) {
      $("#tree-menu #edit-node").click();
    }) 
    .bind("load_node.jstree", function(e, data){
        if(data.rslt.obj.attr && $("#auto-load").val()>0){
            var a = []; 
            data.inst._get_children(data.rslt.obj).each(function ()
            { 
              //a.push(this.id);
              if(this.id==$("#auto-load").val())
              {
                $.jstree._reference("#content-tree").deselect_all();
                $.jstree._reference("#content-tree").select_node(this); 
              } 
            });            
        }      
    })          
		.bind("select_node.jstree", function (e, data) {
		  //loadTreeDetails(data.rslt.obj);
		  //var path=$.jstree._reference("#content-tree").get_path();
		  //$("#content-tree-path").html(path.toString().replace(","," > "));
      $("#content-tree-status").html("'.Yii::t('backend', 'STR_ID').': <span id=\"node-id\">"+data.rslt.obj.attr("id")+"</span>, '
                                       .Yii::t('backend', 'STR_TYPE').': <span id=\"node-type\">"+data.rslt.obj.attr("type-name")+"</span>");
      
      var obj = $.jstree._reference("#content-tree").get_selected();
      if(obj.attr("id")>-1) {
        $.jstree._reference("#content-tree").open_node(obj);
        if(obj.attr("id")==$("#auto-load").val()) {          
          $("#tree-menu #edit-node").click(); 
          $("#auto-load").val(""); 
        }
      }                                     	                                       	
		});
			
  ',
	CClientScript::POS_READY
);

?>


<!--<div class="inner-page-title">
					<h2><?php echo Yii::t('backend', 'STR_CONTENT'); ?></h2>
          <span>  </span>
</div>-->

				<!--<div class="content-box">-->
					<div class="three-column three-column-expand">
						<div class="three-col-mid three-column-expand">					
							<div class="column col1" id="content-loading" style="display:none"><div id="main-column"></div></div>
							<div class="column col2 col2-expand">
                <div class="column-content-box">
      						<div class="content-box content-box-header ui-corner-all">
      							<div class="content-box-wrapper">    
      								<h3>
                        <?php echo Yii::t('backend', 'STR_STRUCTURE') ?><span style="float: right;width:15px;height:20px" id="structure-loader"></span>                        
                      </h3>
                      <div id="tree-menu">
                        <div style="position:relative">
                          <ul class="icons ui-widget ui-helper-clearfix"  style="float:left">            
                            <li id="edit-node" title="<?php echo Yii::t('backend', 'STR_EDIT'); ?>" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-wrench"></span></li>                                
                            <?php if(Yii::app()->user->checkAccess('Cms_content.Default.NewNode')):?>
                            <li id="new-node" title="<?php echo Yii::t('backend', 'STR_NEW'); ?>" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-document"></span></li>
                            <?php endif;?>
                            <?php if(Yii::app()->user->checkAccess('Cms_content.Default.RemoveNode')):?>
                            <li id="delete-node" title="<?php echo Yii::t('backend', 'STR_DELETE'); ?>" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-trash"></span></li>
                            <?php endif;?>
                            <?php if(Yii::app()->user->checkAccess('Cms_content.Default.MoveNode')):?>
                            <li id="move-up" title="<?php echo Yii::t('backend', 'STR_MOVE_UP'); ?>" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-arrow-n"></span></li>                            
                            <li id="move-down" title="<?php echo Yii::t('backend', 'STR_MOVE_DOWN'); ?>" class="btn ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-arrow-s"></span></li>
                            <?php endif;?>
                          </ul>
                          <ul class="icons ui-widget ui-helper-clearfix" style="float:right">
                            <li id="open-all" title="<?php echo Yii::t('backend', 'STR_EXPAND_ALL'); ?>" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-folder-open"></span></li>                        
                            <li id="close-all" title="<?php echo Yii::t('backend', 'STR_COLLAPSE_ALL'); ?>" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-folder-collapsed"></span></li>
                            <li id="refresh-tree" title="<?php echo Yii::t('backend', 'STR_REFRESH'); ?>" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-refresh"></span></li>                             
                          </ul>
                          <div class="clear"></div>                           
                          <div id="menu-disable" class="ui-widget-overlay" style="display:none"></div>
                        </div> 
                        <div id="new-node-type" style="padding:3px 0 7px 7px;border:1px dotted #CCCCCC;margin:3px;display:none">       
            								<label class="desc">
            									<?php echo Yii::t('backend', 'STR_TYPE'); ?>
            								</label>                								
                            <?php echo CHtml::dropDownList('types-list','',array(),array('class'=>'field select small','style'=>'float:left')) ?>                    											
            								<ul class="icons ui-widget ui-helper-clearfix" style="display:inline;float:left;padding-left:5px">
                                <li id="ok-new-node" title="<?php echo Yii::t('backend', 'STR_OK'); ?>" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-check"></span></li>
                                <li id="cancel-new-node" title="<?php echo Yii::t('backend', 'STR_CANCEL'); ?>" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-closethick"></span></li>
            								</ul> 
                            <div class="clear"></div> 					    
        								</div>                                          
                      </div>        
                      <div style="position:relative">                 
                        <br />
                        <?php if(count($langs)>0):?>     
                        <div id="lang-tabs" style="display:none">                             
                          <ul> 
                            <?php foreach($langs as $lng):?>
                            <li><a href="#lang-tab" id="lang-<?php echo $lng->lang_code;?>"><?php echo $lng->short_name;?></a></li>
                            <?php endforeach;?>
                          </ul>                         
                          <div id="lang-tab" style="padding:0.5em">
                         <?php endif;?>  
                            <div id="content-tree-path"></div>
                            <div id="content-tree" class="content-tree"></div>
                            <br />
                            <div id="content-tree-status"></div>
                         <?php if(count($langs)>0):?>     
                          </div>  
                        </div>
                        <?php endif;?> 
                        <div id="tree-disable" class="ui-widget-overlay" style="display:none"></div>
                        <input type="hidden" id="auto-load" value="">	
                      </div>
      							</div>
      						</div>
      					</div>					
							</div>
							<div class="column col3" style="display:none"><div id="small-column"></div></div>
						</div>
					</div>
					<div class="clear"></div>     
				<!--</div>-->
		
