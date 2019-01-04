function login(){
				$("#load").button('loading');
		    var dataform = $('#frmlogin').serializeArray();	    
		    dataform.push({name: 'han', value: 'login'});
		    
		    $.ajax({
		            type: "POST",
		            url : "data.php",
		            data:dataform,
		            cache: false,
		            success: function(msg){
		            	if(msg==="success"){
		            		redirect('index');
		            	}else{
		            		msgbox(msg,'Information','error');
		            		$("#load").button('reset');
		            	}
		            }
		          });
}

function action(form){
	//var data = '{"error":"0","dataerror":[{"id":"idspk","error":"SPK Tidak Ada" }]}';
	//var data = '[{"id":"idspk","error":"SPK Tidak Ada"}]';
	// obj = JSON.parse(data);

	// if(obj.error > 0){
	// 	$.each(obj.dataerror, function(i, item) {
	// 		$("#"+item.id).css("border-color","#a94442");
	// 		$("#"+item.id).after('<div id="validasi" class="help-block with-errors"></div><ul class="list-unstyled" style="color:#a94442"><li>'+ item.error +'</li></ul>');
	// 	})		
	//  }


	$(".validasi").remove();
	var dataform = $("#"+form).serializeArray();
 	dataform.push({name: 'han', value: 'action'});
	  $.ajax({
	          type: "POST",
	          url : "data.php",
	          data:dataform,
	          cache: false,
	          dataType:'json',
	          success: function(msg){	          	
							obj = JSON.parse(msg);
							//console.log(obj);
							$.each(obj.dataresult, function(i, item) {
								if(obj.error > 0){
									$("#"+item.id).css("border-color","#a94442");
									$("#"+item.id).after('<div class="help-block with-errors validasi"></div><ul class="list-unstyled validasi" style="color:#a94442"><li>'+ item.result +'</li></ul>');											
								}else{
									viewaja(item.result,"badan");
								}
							})
	          }
	        });	
}


function view(param,id){
	$.ajax({
          type: "POST",
          url : "data.php",
          cache: false,
          data:{han:param},
          success: function(msg){
						document.getElementById(id).innerHTML = msg;
          }
        });
}

function viewaja(param,id){
	document.getElementById(id).innerHTML = '<span class="spinner"></span>';
	$.ajax({
          type: "POST",
          url : "data.php",
          cache: false,
          dataType:'json',
          data:{han:param},
          success: function(msg){
          	obj = JSON.parse(msg);
						document.getElementById(id).innerHTML = obj.result;
						$('.radio-onchange').change(function() {							
							var ket = $(this).attr('id').replace('pertanyaan','ket');

        			if(this.value=="Y"){
        				$("#"+ket).prop('disabled', false);	
								$("#"+ket).prop('required', true);	
        			}else{
        				$("#"+ket).prop('disabled', true);	
        				$("#"+ket).prop('required', false);	
        			}
        			
          	});						
          }
        });
}

function viewmodul(param,id){
	document.getElementById(id).innerHTML = '<span class="spinner"></span>';

	$.ajax({
          type: "POST",
          url : "data.php",
          cache: false,
          data:{han:param},
          success: function(msg){
						document.getElementById(id).innerHTML = msg;
						$('.radio-onchange').change(function() {							
							var ket = $(this).attr('id').replace('pertanyaan','ket');

        			if(this.value=="Y"){
        				$("#"+ket).prop('disabled', false);	
								$("#"+ket).prop('required', true);	
        			}else{
        				$("#"+ket).prop('disabled', true);	
        				$("#"+ket).prop('required', false);	
        			}
        			
          	});
          }
        });
}

function msgbox(psn,title="Information",type="info"){
			swal({
			title: title,
			text: psn,
			type: type,                            
			confirmButtonColor: "#DD6B55",                          
			showConfirmButton:true,
			timer: 2000
		}); 
}

function redirect(lokasi){
	window.location.href = lokasi+".php";
}

function logout(){
	$.ajax({
          type: "POST",
          url : "data.php",
          cache: false,
          data:{han:'logout'},
          success: function(msg){
						redirect('index');
          }
        });
}