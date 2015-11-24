var persisteduls=new Object()
var streemenu=new Object()

streemenu.closefolder="closed.gif" //set image path to "closed" folder image
streemenu.openfolder="open.gif" //set image path to "open" folder image

//////////No need to edit beyond here///////////////////////////

streemenu.createTree=function(treeid, enablepersist, persistdays){
	var ultags=document.getElementById(treeid).getElementsByTagName("ul")
	if (typeof persisteduls[treeid]=="undefined")
		persisteduls[treeid]=(enablepersist==true && streemenu.getCookie(treeid)!="")? streemenu.getCookie(treeid).split(",") : ""
	for (var i=0; i<ultags.length; i++)
		streemenu.buildSubTree(treeid, ultags[i], i)
	if (enablepersist==true){ //if enable persist feature
		var durationdays=(typeof persistdays=="undefined")? 1 : parseInt(persistdays)
		streemenu.dotask(window, function(){streemenu.rememberstate(treeid, durationdays)}, "unload") //save opened UL indexes on body unload
	}
}

streemenu.buildSubTree=function(treeid, ulelement, index)
{
	ulelement.parentNode.className="submenu"
	if (typeof persisteduls[treeid]=="object")
	{ //if cookie exists (persisteduls[treeid] is an array versus "" string)
		if (streemenu.searcharray(persisteduls[treeid], index))
		{
			ulelement.setAttribute("rel", "open")
			ulelement.style.display="block"
			ulelement.parentNode.style.backgroundImage="url("+streemenu.openfolder+")"
		}
		else
			ulelement.setAttribute("rel", "closed")
	} //end cookie persist code
	else if (ulelement.getAttribute("rel")==null || ulelement.getAttribute("rel")==false) //if no cookie and UL has NO rel attribute explicted added by user
		ulelement.setAttribute("rel", "closed")
	else if (ulelement.getAttribute("rel")=="open") //else if no cookie and this UL has an explicit rel value of "open"
		streemenu.expandSubTree(treeid, ulelement) //expand this UL plus all parent ULs (so the most inner UL is revealed!)
	
	ulelement.parentNode.onclick=function(e)
	{
		var submenu=this.getElementsByTagName("ul")[0]
		if (submenu.getAttribute("rel")=="closed")
		{
			submenu.style.display="block"
			submenu.setAttribute("rel", "open")
			ulelement.parentNode.style.backgroundImage="url("+streemenu.openfolder+")"
		}
		else if (submenu.getAttribute("rel")=="open")
		{
			submenu.style.display="none"
			submenu.setAttribute("rel", "closed")
			ulelement.parentNode.style.backgroundImage="url("+streemenu.closefolder+")"
		}
		streemenu.preventpropagate(e)
	}
	ulelement.onclick=function(e)
	{
		streemenu.preventpropagate(e)
	}
}

streemenu.expandSubTree=function(treeid, ulelement)
{ //expand a UL element and any of its parent ULs
	var rootnode=document.getElementById(treeid)
	var currentnode=ulelement
	currentnode.style.display="block"
	currentnode.parentNode.style.backgroundImage="url("+streemenu.openfolder+")"
	while (currentnode!=rootnode)
	{
		if (currentnode.tagName=="UL")
		{ //if parent node is a UL, expand it too
			currentnode.style.display="block"
			currentnode.setAttribute("rel", "open") //indicate it's open
			currentnode.parentNode.style.backgroundImage="url("+streemenu.openfolder+")"
		}
		currentnode=currentnode.parentNode
	}
}

streemenu.flatten=function(treeid, action)
{ //expand or contract all UL elements
	var ultags=document.getElementById(treeid).getElementsByTagName("ul")
	for (var i=0; i<ultags.length; i++){
		ultags[i].style.display=(action=="expand")? "block" : "none"
		var relvalue=(action=="expand")? "open" : "closed"
		ultags[i].setAttribute("rel", relvalue)
		ultags[i].parentNode.style.backgroundImage=(action=="expand")? "url("+streemenu.openfolder+")" : "url("+streemenu.closefolder+")"
	}
}

streemenu.rememberstate=function(treeid, durationdays)
{ //store index of opened ULs relative to other ULs in Tree into cookie
	var ultags=document.getElementById(treeid).getElementsByTagName("ul")
	var openuls=new Array()
	for (var i=0; i<ultags.length; i++)
	{
		if (ultags[i].getAttribute("rel")=="open")
			openuls[openuls.length]=i //save the index of the opened UL (relative to the entire list of ULs) as an array element
	}
	if (openuls.length==0) //if there are no opened ULs to save/persist
		openuls[0]="none open" //set array value to string to simply indicate all ULs should persist with state being closed
	streemenu.setCookie(treeid, openuls.join(","), durationdays) //populate cookie with value treeid=1,2,3 etc (where 1,2... are the indexes of the opened ULs)
}

////A few utility functions below//////////////////////

streemenu.getCookie=function(Name)
{ //get cookie value
	var re=new RegExp(Name+"=[^;]+", "i"); //construct RE to search for target name/value pair
	if (document.cookie.match(re)) //if cookie found
		return document.cookie.match(re)[0].split("=")[1] //return its value
	return ""
}

streemenu.setCookie=function(name, value, days)
{ //set cookei value
	var expireDate = new Date()
	//set "expstring" to either future or past date, to set or delete cookie, respectively
	var expstring=expireDate.setDate(expireDate.getDate()+parseInt(days))
	document.cookie = name+"="+value+"; expires="+expireDate.toGMTString()+"; path=/";
}

streemenu.searcharray=function(thearray, value)
{ //searches an array for the entered value. If found, delete value from array
	var isfound=false
	for (var i=0; i<thearray.length; i++)
	{
		if (thearray[i]==value)
		{
			isfound=true
			thearray.shift() //delete this element from array for efficiency sake
			break
		}
	}
	return isfound
}

streemenu.preventpropagate=function(e)
{ //prevent action from bubbling upwards
	if (typeof e!="undefined")
		e.stopPropagation()
	else
		event.cancelBubble=true
}

streemenu.dotask=function(target, functionref, tasktype)
{ //assign a function to execute to an event handler (ie: onunload)
	var tasktype=(window.addEventListener)? tasktype : "on"+tasktype
	if (target.addEventListener)
		target.addEventListener(tasktype, functionref, false)
	else if (target.attachEvent)
		target.attachEvent(tasktype, functionref)
}
