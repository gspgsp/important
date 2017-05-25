function setTab(name,cursel,n){
	for(i=1;i<=n;i++){
		var menu=document.getElementById(name+i);
		menu.className=i==cursel?"hover":"";
	}
}