var imgPlus = new Image();
var imgMinus = new Image();

imgPlus.src = "images/goDown.gif";
imgMinus.src = "images/goTop.gif";

function getTableNodeName(Node){
	return "pane" + Node;
}

function getImgNodeName(Node){
	return "img" + Node;
}

function showNode(Node){
//alert();
	switch(window.navigator.family){
		case 'nn4':
			// Nav 4.x code fork...
			var oTable = document.layers[getTableNodeName(Node)];
			var oImg = document.layers[getImgNodeName(Node)];
			break;
		case 'ie4':
			// IE 4/5 code fork...
			var oTable = document.all[getTableNodeName(Node)];
			var oImg = document.all[getImgNodeName(Node)];
			break;
		case 'gecko':
			// Standards Compliant code fork...
			var oTable = document.getElementById(getTableNodeName(Node));
			var oImg = document.getElementById(getImgNodeName(Node));
			break;
		default:
			var oTable = document.getElementById(getTableNodeName(Node));
			var oImg = document.getElementById(getImgNodeName(Node));
	}

	oImg.src = imgMinus.src;
	oTable.style.display = "block";
}

function hideNode(Node){
	switch(window.navigator.family){
		case 'nn4':
			// Nav 4.x code fork...
			var oTable = document.layers[getTableNodeName(Node)];
			var oImg = document.layers[getImgNodeName(Node)];
			break;
		case 'ie4':
			// IE 4/5 code fork...
			var oTable = document.all[getTableNodeName(Node)];
			var oImg = document.all[getImgNodeName(Node)];
			break;
		case 'gecko':
			// Standards Compliant code fork...
			var oTable = document.getElementById(getTableNodeName(Node));
			var oImg = document.getElementById(getImgNodeName(Node));
			break;
		default :
			var oTable = document.getElementById(getTableNodeName(Node));
			var oImg = document.getElementById(getImgNodeName(Node));

	}
	oImg.src = imgPlus.src;
	oTable.style.display = "none";
}

function nodeIsVisible(Node){
	switch(window.navigator.family){
		case 'nn4':
			// Nav 4.x code fork...
			var oTable = document.layers[getTableNodeName(Node)];
			break;
		case 'ie4':
			// IE 4/5 code fork...
			var oTable = document.all[getTableNodeName(Node)];
			break;
		case 'gecko':
			// Standards Compliant code fork...
			var oTable = document.getElementById(getTableNodeName(Node));
			break;
		default:
			var oTable = document.getElementById(getTableNodeName(Node));
	}
	return (oTable && oTable.style.display == "block");
}

function toggleNodeVisibility(Node){

	if (nodeIsVisible(Node)){
		hideNode(Node);
	}else{
		showNode(Node);
	}
}