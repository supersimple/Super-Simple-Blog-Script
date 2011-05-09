
/***************************************************
This creates the zebra tables by getting all TRs,
and colouring each even row (uses modulos)
***************************************************/
window.onload = function(){init();}

function init(){
var t;
	var tds = document.getElementsByTagName ("tr");
	for (var i = 0; i < tds.length; i ++) {
		t = tds[i];
		if(i % 2 == 0){
			t.style.backgroundColor = '#eee';
		}
	}
	
}