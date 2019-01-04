/*
Ajax Dynamic ComboBox ver.1.0.1 for prototype.js
(c) 2010 Yerie Piscesa <yeriepiscesa@fbxphpindonesia.com>
released under GNU GENERAL PUBLIC LICENSE
*/
(function(bindObj){
  function cleanListBox(id,excludeFirstData){
    if (excludeFirstData){var first_label = $(id).options[0].text;var first_value = $(id).options[0].value;}
	while ($(id).options.length != 0) $(id).options[0]=null;if (excludeFirstData) $(id).options[0] = new Option(first_label,first_value);	
  }
  function fillListBox(id,data,value,label){$A(data).each(function(key,index){var newOpt = document.createElement("option");newOpt.text = key[label];newOpt.value = key[value];$(id).options.add(newOpt);});}
  var firstRun = true;
  function populate(evt,index){
    var params = {}; params[this.rootID] = $F(this.rootID);
	if(index==undefined){index=-1; $A(this.childs).each(function(key){cleanListBox(key,true)});} 
	else {
	  for(var x=index+1; x<this.childs.length;x++)cleanListBox(this.childs[x],true);
	  for(var y=0;y<index+1;y++)params[this.childs[y]] = $F(this.childs[y]);
	}
	var _ch = this.childs[index+1];var _el = this.options.elements[_ch];
	var loadingPanel = _ch+"_loader"; var loadingImage = this.options["loadingImage"]; var loadingText = this.options["loadingText"];
	var _img_ = ""; if (loadingImage) _img_ = '<img src="'+loadingImage+'"/>';
	var loader = '<span id="x__'+loadingPanel+'" style="position:absolute;margin-top:-6px;padding:4px;font-weight:bold;background-color:#FFF;border:0px solid #000000;z-index:5000"><table><tr><td>'+_img_+'</td><td>'+loadingText+'</td></tr></table></span>'
	$(loadingPanel).update(loader);
	var thisObj=this;
	new Ajax.Request(_el["url"],{
	  parameters:params,
	  onComplete:function(t){
        if(thisObj.options.debug) alert("Request URL: "+_el["url"]+"\n\nServer Response:\n"+t.responseText); 				
		var data = t.responseText.evalJSON();
		if(data){
		  fillListBox(_ch,data,_el.value,_el.label);		
		  if(_el['init'] && firstRun) {
		    $(_ch).value = _el['init'];
            if ((index+1) < thisObj.childs.length-1) (populate.bindAsEventListener(thisObj,index+1))();
			else firstRun = false;
		  }
		}
		if($("x__"+loadingPanel))$("x__"+loadingPanel).remove();
	  }
	});
  }
  var DynamiCombo = Class.create({
    version:'1.0.0',
	options:null,rootID:null,childs:[],
	initialize:function(rootID, options){
	  this.options = options || {};
	  this.rootID = rootID;
	  $(rootID).observe("change",populate.bindAsEventListener(this));
	  if(this.options.elements) {
	    var thisObj=this;
		var eLen = $H(this.options.elements).size();
		$H(this.options.elements).each(function(o,index){
		  thisObj.childs.push(o.key);
		  $(o.key).insert({after:'<span id="'+o.key+'_loader"></span>'});
		  if (index < eLen-1) $(o.key).observe("change",populate.bindAsEventListener(thisObj,index));
		});
		if($F(rootID)) (populate.bindAsEventListener(this))();
	  }
    }
  });
  Object.extend(bindObj,{DynamiCombo:DynamiCombo});  
})(window);