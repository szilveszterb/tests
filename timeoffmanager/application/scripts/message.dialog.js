function InitMessageDialogs()
{	
	try
	{
		$('#warningDialog').dialog({
			autoOpen: false,
			width: 400,
			modal: true,
			resizable: false,
			hide: { 
				effect: 'drop', 
				direction: "down" 
				},
			show: { 
				effect: 'drop', 
				direction: "up" 
				},	
			buttons: {
				"Ok": function() {		
					$(this).dialog("close");
				}
			}
		});        
	}
	catch(e)
	{
		alert("Message initialization exception: " + e.message);
	}
}