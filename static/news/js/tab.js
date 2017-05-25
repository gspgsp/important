function setTab(name,cursel,n){
	for(i=1;i<=n;i++){
		var menu=document.getElementById(name+i);
		var con=document.getElementById("con-"+name+"-"+i);
		menu.className=i==cursel?"hover":"";
		con.style.display=i==cursel?"block":"none";
	}
}
function setTab1(name,cursel,n){
	for(i=1;i<=n;i++){
		var menu=document.getElementById(name+i);
		var con=document.getElementById("con-"+name+"-"+i);
		menu.className=i==cursel?"hover":"";
		con.style.display=i==cursel?"block":"none";
	}
	$('#zts').css({display: 'none'});
	$('#sydt').css({display: 'block'});
}
function setTab2(name,cursel,n,more){
	for(i=1;i<=n;i++){
		var menu=document.getElementById(name+i);
		var con=document.getElementById("con-"+name+"-"+i);
		var mo=document.getElementById(more+i);
		menu.className=i==cursel?"hover":"";
		con.style.display=i==cursel?"block":"none";
		mo.style.display=i==cursel?"block":"none";
	}
	
}