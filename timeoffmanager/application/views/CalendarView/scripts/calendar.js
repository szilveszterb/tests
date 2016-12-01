/**
 * Description of InitCalendar
 * @package Javascript helper fuction
 * @subpackage Calendar
 * @author Judit Alföldi
 * 
 * TheInitCalendar function implements all calendar functions:
 * - Add new agenda item
 * - Modify agenda item
 * - Drag and drop agenda item
 * - Delete agenda item
 * 
 * It defines different dialogs for Guest and Manager levels.
 * It calculates the usable holiday numbers on the fly and
 * controls the requestes gear to the calculated number.
 * 
 */
function InitCalendar(right, holidayNum, holidayPartNum)
{    
    var clickDate = "";
	var clickAgendaItem = "";
    var currHolidayNum = parseInt(holidayNum);
    var currHolidayPartNum = parseInt(holidayPartNum);
	
	/**
	 * Initializes calendar with current year & month
	 * specifies the callbacks for day click & agenda item click events
	 * then returns instance of plugin object
	 */
    if(getRightText(parseInt(right)) == "Guest")
    {       
        var jfcalplugin = $("#mycal").jFrontierCal({
            date: new Date(),
            agendaDropCallback: myAgendaDropHandler,
            agendaMouseoverCallback: myAgendaMouseoverHandler,
            applyAgendaTooltipCallback: myApplyTooltip,
            dragAndDropEnabled: false
        }).data("plugin");           
    } 
    else if(getRightText(parseInt(right)) == "Manager")
    {
        var jfcalplugin = $("#mycal").jFrontierCal({
            date: new Date(),
            agendaClickCallback: myAgendaClickHandler,
            agendaDropCallback: myAgendaDropHandler,
            agendaMouseoverCallback: myAgendaMouseoverHandler,
            applyAgendaTooltipCallback: myApplyTooltip,
            agendaDragStartCallback : myAgendaDragStart,
            agendaDragStopCallback : myAgendaDragStop,
            dragAndDropEnabled: false
        }).data("plugin");                    
    }
    else
    {
        var jfcalplugin = $("#mycal").jFrontierCal({
            date: new Date(),
            dayClickCallback: myDayClickHandler,
            agendaClickCallback: myAgendaClickHandler,
            agendaDropCallback: myAgendaDropHandler,
            agendaMouseoverCallback: myAgendaMouseoverHandler,
            applyAgendaTooltipCallback: myApplyTooltip,
            agendaDragStartCallback : myAgendaDragStart,
            agendaDragStopCallback : myAgendaDragStop,
            dragAndDropEnabled: true
        }).data("plugin");          
    }
    
    $.ajax(
    {
        type: "GET",
        url: encodeURI("http://localhost/timeoffmanager/Calendar/FillUpCalendar"),
        dataType: "json",
        cache: false,
        success: function(data)
        {                           
            if(getRightText(parseInt(right)) == "Operator")
            {
                $.each(data["holidayList"], function(index, element)
                {                      
                    var from = element["from"].split("-");
                    var to = element["to"].split("-"); 
                    var status = getHolidayStatusText(parseInt(element["status"]));

                    if(status != "Previous")
                    {
                        jfcalplugin.addAgendaItem(
                            "#mycal",
                            element["desc"],
                            new Date(from[0],from[1]-1,from[2],0,0,0,0),
                            new Date(to[0],to[1]-1,to[2],23,59,59,999),
                            true,
                            {
                                Identifier: element["id"],
                                Status: status,
                                Name: data["fullName"],
                                Email: data["email"]
                            },
                            {
                                backgroundColor: data["background"],
                                foregroundColor: data["foreground"]
                            }	
                        ); 
                    }
                });
            }
            else
            {
                $.each(data["operatorList"], function(index, elementData)
                {
                    $.each(elementData["holidayList"], function(index, element)
                    {                                
                        var from = element["from"].split("-");
                        var to = element["to"].split("-"); 
                        var status = getHolidayStatusText(parseInt(element["status"]));

                        if(status != "Previous")
                        {
                            jfcalplugin.addAgendaItem(
                                "#mycal",
                                element["desc"],
                                new Date(from[0],from[1]-1,from[2],0,0,0,0),
                                new Date(to[0],to[1]-1,to[2],23,59,59,999),
                                true,
                                {
                                    Identifier: element["id"],
                                    Status: status,
                                    Name: elementData["fullName"],
                                    Email: elementData["email"]
                                },
                                {
                                    backgroundColor: elementData["background"],
                                    foregroundColor: elementData["foreground"]
                                }	
                            ); 
                        }
                    });  
                });
            }
        }      
    });
    
    function getRightText(right)
    {
        switch(right) {
            case 1:
                return "Guest";
                break;
            case 2:
                return "Operator";
                break;          
            default:
                return "Manager";
        }     
    }    
    
    function getHolidayStatusText(status)
    {
        switch(status) {
            case 1:
                return "Required";
                break;
            case 2:
                return "Approved";
                break;
            case 3:
                return "Rejected";
                break;
            case 4:
                return "Pending";
                break;
            case 5:
                return "Expired";
                break;            
            default:
                return "Previous";
        }     
    }
    
    function getHolidayStatusNumber(status)
    {
        switch(status) {
            case "Required":
                return 1;
                break;
            case "Approved":
                return 2;
                break;
            case "Rejected":
                return 3;
                break;
            case "Pending":
                return 4;
                break;
            case "Expired":
                return 5;
                break;            
            default:
                return 0;
        }     
    }    
   
	/**
	 * Do something when dragging starts on agenda div
	 */
	function myAgendaDragStart(eventObj,divElm,agendaItem){
		// destroy our qtip tooltip
		if(divElm.data("qtip")){
			divElm.qtip("destroy");
		}	
	};
	
	/**
	 * Do something when dragging stops on agenda div
	 */
	function myAgendaDragStop(eventObj,divElm,agendaItem){
		//alert("drag stop");
	};
	
	/**
	 * Custom tooltip - use any tooltip library you want to display the agenda data.
	 * for this example we use qTip - http://craigsworks.com/projects/qtip/
	 *
	 * @param divElm - jquery object for agenda div element
	 * @param agendaItem - javascript object containing agenda data.
	 */
	function myApplyTooltip(divElm,agendaItem){

		// Destroy currrent tooltip if present
		if(divElm.data("qtip"))
        {
			divElm.qtip("destroy");
		}
		
		var displayData = "";
		
		var title = agendaItem.title;
		var startDate = agendaItem.startDate;
		var endDate = agendaItem.endDate;
		var allDay = agendaItem.allDay;
		var data = agendaItem.data;
		displayData += "<br><b>" + title+ "</b><br><br>";
		if(allDay)
        {
			displayData += "(All day event)<br><br>";
            displayData += "<b>Starts:</b> " + startDate + "<br>" + "<b>Ends:</b> " + endDate + "<br><br>";
		}
        else
        {
            displayData += "(All day event)<br><br>";
			displayData += "<b>Starts:</b> " + startDate + "<br>" + "<b>Ends:</b> " + endDate + "<br><br>";
		}
        
		for (var propertyName in data) {
			displayData += "<b>" + propertyName + ":</b> " + data[propertyName] + "<br>";
		}
		// use the user specified colors from the agenda item.
		var backgroundColor = agendaItem.displayProp.backgroundColor;
		var foregroundColor = agendaItem.displayProp.foregroundColor;
		var myStyle = {
			border: {
				width: 5,
				radius: 10
			},
			padding: 10, 
			textAlign: "left",
			tip: true,
			name: "dark" // other style properties are inherited from dark theme		
		};
		if(backgroundColor != null && backgroundColor != ""){
			myStyle["backgroundColor"] = backgroundColor;
		}
		if(foregroundColor != null && foregroundColor != ""){
			myStyle["color"] = foregroundColor;
		}
		// apply tooltip
		divElm.qtip({
			content: displayData,
			position: {
				corner: {
					tooltip: "bottomMiddle",
					target: "topMiddle"			
				},
				adjust: { 
					mouse: true,
					x: 0,
					y: -15
				},
				target: "mouse"
			},
			show: { 
				when: { 
					event: 'mouseover'
				}
			},
			style: myStyle
		});

	};

	/**
	 * Make the day cells roughly 3/4th as tall as they are wide. this makes our calendar wider than it is tall. 
	 */
	jfcalplugin.setAspectRatio("#mycal",0.75);

	/**
	 * Called when user clicks day cell
	 * use reference to plugin object to add agenda item
	 */
	function myDayClickHandler(eventObj){
		// Get the Date of the day that was clicked from the event object
		var date = eventObj.data.calDayDate;
		// store date in our global js variable for access later
        var startMonth = date.getMonth()+1;
        if(startMonth < 10)
        {
            startMonth = "0" + startMonth;
        }          
        var startDay = date.getDate();
        if(startDay < 10)
        {
            startDay = "0" + startDay;
        }         
		clickDate = date.getFullYear() + "-" + startMonth + "-" + startDay;
		// open our add event dialog
		$('#add-event-form').dialog('open');
	};
	
	/**
	 * Called when user clicks and agenda item
	 * use reference to plugin object to edit agenda item
	 */
	function myAgendaClickHandler(eventObj){
		// Get ID of the agenda item from the event object
		var agendaId = eventObj.data.agendaId;		
		// pull agenda item from calendar
		var agendaItem = jfcalplugin.getAgendaItemById("#mycal",agendaId);
		clickAgendaItem = agendaItem;
        if(getRightText(parseInt(right)) == "Operator")
        {
            $("#display-event-form").dialog('open');
        }
        else
        {
            if(clickAgendaItem.data.Status == "Required")
            {
                $("#modify-event-form-manager").dialog('open');
            }
            else
            {
                $("#display-event-form-manager").dialog('open');
            }
        }
	};
	
	/**
	 * Called when user drops an agenda item into a day cell.
	 */
	function myAgendaDropHandler(eventObj){
		// Get ID of the agenda item from the event object
		var agendaId = eventObj.data.agendaId;
		// date agenda item was dropped onto
		var date = eventObj.data.calDayDate;
		// Pull agenda item from calendar
		var agendaItem = jfcalplugin.getAgendaItemById("#mycal",agendaId);		
    
        var startMonth = parseInt(date.getMonth()) + 1;
        var startDay = date.getDate();
        if(startDay < 10)
        {
            startDay = "0" + startDay;
        } 
        
        var startDateObjToDb = date.getFullYear() + "-" + startMonth + "-" + startDay;

        $.ajax(
        {
            type: "POST",
            url: encodeURI("http://localhost/timeoffmanager/Calendar/DragAndDropCalendarItem"),
            data:{id : agendaItem.data.Identifier, desc: agendaItem.title, from : startDateObjToDb},
            dataType: "json",
            cache: false   
        });           
	};
	
	/**
	 * Called when a user mouses over an agenda item	
	 */
	function myAgendaMouseoverHandler(eventObj)
    {
		var agendaId = eventObj.data.agendaId;
		var agendaItem = jfcalplugin.getAgendaItemById("#mycal",agendaId);
	};
	/**
	 * Initialize jquery ui datepicker. set date format to yyyy-mm-dd for easy parsing
	 */
	$("#dateSelect").datepicker({
		showOtherMonths: true,
		selectOtherMonths: true,
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd'
	});
	
	/**
	 * Set datepicker to current date
	 */
	$("#dateSelect").datepicker('setDate', new Date());
	/**
	 * Use reference to plugin object to a specific year/month
	 */
	$("#dateSelect").bind('change', function() {
		var selectedDate = $("#dateSelect").val();
		var dtArray = selectedDate.split("-");
		var year = dtArray[0];
		// jquery datepicker months start at 1 (1=January)		
		var month = dtArray[1];
		// strip any preceeding 0's		
		month = month.replace(/^[0]+/g,"")		
		var day = dtArray[2];
		// plugin uses 0-based months so we subtrac 1
		jfcalplugin.showMonth("#mycal",year,parseInt(month-1).toString());
	});	
	/**
	 * Initialize previous month button
	 */
	$("#BtnPreviousMonth").button();
	$("#BtnPreviousMonth").click(function() {
		jfcalplugin.showPreviousMonth("#mycal");
		// update the jqeury datepicker value
		var calDate = jfcalplugin.getCurrentDate("#mycal"); // returns Date object
		var cyear = calDate.getFullYear();
		// Date month 0-based (0=January)
		var cmonth = calDate.getMonth();
		var cday = calDate.getDate();
		// jquery datepicker month starts at 1 (1=January) so we add 1
		$("#dateSelect").datepicker("setDate",cyear+"-"+(cmonth+1)+"-"+cday);
		return false;
	});
	/**
	 * Initialize next month button
	 */
	$("#BtnNextMonth").button();
	$("#BtnNextMonth").click(function() {
		jfcalplugin.showNextMonth("#mycal");
		// update the jqeury datepicker value
		var calDate = jfcalplugin.getCurrentDate("#mycal"); // returns Date object
		var cyear = calDate.getFullYear();
		// Date month 0-based (0=January)
		var cmonth = calDate.getMonth();
		var cday = calDate.getDate();
		// jquery datepicker month starts at 1 (1=January) so we add 1
		$("#dateSelect").datepicker("setDate",cyear+"-"+(cmonth+1)+"-"+cday);		
		return false;
	});	

	/**
	 * Initialize add event modal form
	 */
	$("#add-event-form").dialog({
		autoOpen: false,
		height: 400,
		width: 400,
		modal: true,
		buttons: {
			'Save': function() {

				var what = jQuery.trim($("#what").val());
			
				if(what == "")
                {
                    $("#warningDialog").html("Please enter a short day off description.");
                    $('#warningDialog').dialog('open');                    
				}
                else
                {				
					var startDate = $("#startDate").val();
                    var endDate = $("#endDate").val();
                    var startDateToDiff = new Date(startDate);
                    var endDateToDiff = new Date(endDate);
                    var diff = new Date(endDateToDiff - startDateToDiff);
                    var days = Math.floor(diff/1000/60/60/24);        
                    
                    if((days + 1)  <= currHolidayNum)
                    {
                        currHolidayNum = currHolidayNum - (days + 1);
                        var startDtArray = startDate.split("-");
                        var startYear = startDtArray[0];
                        // jquery datepicker months start at 1 (1=January)		
                        var startMonth = startDtArray[1];		
                        var startDay = startDtArray[2];


                        var endDtArray = endDate.split("-");
                        var endYear = endDtArray[0];
                        // jquery datepicker months start at 1 (1=January)		
                        var endMonth = endDtArray[1];		
                        var endDay = endDtArray[2];

                        // Dates use integers
                        var startDateObjToDb = startYear + "-" + startMonth + "-" + startDay;
                        var endDateObjToDb = endYear + "-" + endMonth + "-" + endDay;      
                        var startDateObj = new Date(parseInt(startYear),parseInt(startMonth)-1,parseInt(startDay),0,0,0,0);
                        var endDateObj = new Date(parseInt(endYear),parseInt(endMonth)-1,parseInt(endDay),23,59,59,999);


                        $.ajax(
                        {
                            type: "POST",
                            url: encodeURI("http://localhost/timeoffmanager/Calendar/AddCalendarItem"),
                            data:{desc: what, from : startDateObjToDb, to : endDateObjToDb},
                            dataType: "json",
                            cache: false,
                            success: function(data)
                            {     
                                var status = getHolidayStatusText(parseInt("1"));

                                jfcalplugin.addAgendaItem(
                                    "#mycal",
                                    what,                                    
                                    startDateObj,
                                    endDateObj,
                                    true,
                                    {
                                        Identifier: data["holidayList"][data["holidayList"].length-1]["id"],
                                        Status: status,
                                        Name: data["fullName"],
                                        Email: data["email"]
                                    },
                                    {
                                        backgroundColor: data["background"],
                                        foregroundColor: data["foreground"]
                                    }
                                );                            
                            }      
                        }); 
                    }
                    else
                    {
                        $("#warningDialog").html("You do not have enough days for the required holiday.");
                        $('#warningDialog').dialog('open');                         
                    }

					$(this).dialog('close');
				}
				
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		},
		open: function(event, ui){
			// initialize start date picker
			$("#startDate").datepicker({
				showOtherMonths: true,
				selectOtherMonths: true,
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true,
				dateFormat: 'yy-mm-dd'
			});
			// initialize end date picker
			$("#endDate").datepicker({
				showOtherMonths: true,
				selectOtherMonths: true,
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true,
				dateFormat: 'yy-mm-dd'
			});
			// initialize with the date that was clicked
			$("#startDate").val(clickDate);
			$("#endDate").val(clickDate);
			//$("#colorForeground").val("#ffffff");				
			// put focus on first form input element
			$("#what").focus();
		},
		close: function() {
			// reset form elements when we close so they are fresh when the dialog is opened again.
			$("#startDate").datepicker("destroy");
			$("#endDate").datepicker("destroy");
			$("#startDate").val("");
			$("#endDate").val("");			
			$("#what").val("");
			//$("#colorBackground").val("#1040b0");
			//$("#colorForeground").val("#ffffff");
		}
	});
    
	$("#modify-event-form").dialog({
		autoOpen: false,
		height: 400,
		width: 400,
		modal: true,
		buttons:
        {
			'Save': function()
            {                
				var what = jQuery.trim($("#reasonModify").val());			
				if(what == "")
                {
                    $("#warningDialog").html("Please enter a short day off description.");
                    $('#warningDialog').dialog('open');   
				}
                else
                {
					var startDate = $("#startDateModify").val();
                    var endDate = $("#endDateModify").val();
                    var startDateToDiff = new Date(startDate);
                    var endDateToDiff = new Date(endDate);
                    var newDiff = new Date(endDateToDiff - startDateToDiff);
                    var newDays = Math.floor(newDiff/1000/60/60/24);
                    
                    var oldDiff = new Date(clickAgendaItem.endDate - clickAgendaItem.startDate);
                    var oldDays = Math.floor(oldDiff/1000/60/60/24);                    
                    
                    if(((newDays + 1) < (oldDays + 1)) || (((newDays + 1) > (oldDays + 1)) && (((newDays + 1)-(oldDays + 1) <= currHolidayNum ))))
                    {                    
                        if((newDays + 1) < (oldDays + 1))
                        {
                            if((currHolidayNum + ((oldDays + 1) - (newDays + 1))) > currHolidayPartNum)
                            {
                                currHolidayNum = currHolidayPartNum;
                            }
                            else
                            {
                                currHolidayNum = currHolidayNum + ((oldDays + 1) - (newDays + 1));
                            }
                        }
                        else
                        {
                            currHolidayNum = currHolidayNum - ((newDays + 1) - (oldDays + 1));
                        }
                        
                        
                        var startDate = $("#startDateModify").val();
                        var startDtArray = startDate.split("-");
                        var startYear = startDtArray[0];
                        // jquery datepicker months start at 1 (1=January)		
                        var startMonth = startDtArray[1];		
                        var startDay = startDtArray[2];

                        var endDate = $("#endDateModify").val();
                        var endDtArray = endDate.split("-");
                        var endYear = endDtArray[0];
                        // jquery datepicker months start at 1 (1=January)		
                        var endMonth = endDtArray[1];		
                        var endDay = endDtArray[2];

                        //alert("Start time: " + startHour + ":" + startMin + " " + startMeridiem + ", End time: " + endHour + ":" + endMin + " " + endMeridiem);

                        // Dates use integers
                        var startDateObjToDb = startYear + "-" + startMonth + "-" + startDay;
                        var endDateObjToDb = endYear + "-" + endMonth + "-" + endDay;

                        $.ajax(
                        {
                            type: "POST",
                            url: encodeURI("http://localhost/timeoffmanager/Calendar/ModifyCalendarItem"),
                            data:{id : clickAgendaItem.data.Identifier, desc: what, from : startDateObjToDb, to : endDateObjToDb, status : getHolidayStatusNumber(clickAgendaItem.data.Status)},
                            dataType: "json",
                            cache: false,
                            success: function(data)
                            {     
                                jfcalplugin.addAgendaItem(
                                    "#mycal",
                                    clickAgendaItem.title,
                                    startDateToDiff,
                                    endDateToDiff,
                                    true,
                                    {
                                        Identifier: clickAgendaItem.data.Identifier,
                                        Status: clickAgendaItem.data.Status,
                                        Name: clickAgendaItem.data.Name,
                                        Email: clickAgendaItem.data.Email
                                    },
                                    {
                                        backgroundColor: clickAgendaItem.displayProp.backgroundColor,
                                        foregroundColor: clickAgendaItem.displayProp.foregroundColor
                                    }
                                );    
                                jfcalplugin.deleteAgendaItemById("#mycal",clickAgendaItem.agendaId);
                            }                         
                        });  
                    }
                    else
                    {
                        $("#warningDialog").html("You do not have enough days for the required holiday.");
                        $('#warningDialog').dialog('open');                         
                    }
                $(this).dialog('close');
				}				
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		},
		open: function(event, ui){
            if(clickAgendaItem != null){
                if(clickAgendaItem.data.Status == "Required")
                {
                    $("#display-event-form").dialog('close');
                     //initialize start date picker
                    $("#startDateModify").datepicker({
                        showOtherMonths: true,
                        selectOtherMonths: true,
                        changeMonth: true,
                        changeYear: true,
                        showButtonPanel: true,
                        dateFormat: 'yy-mm-dd'
                    });
                    // initialize end date picker
                    $("#endDateModify").datepicker({
                        showOtherMonths: true,
                        selectOtherMonths: true,
                        changeMonth: true,
                        changeYear: true,
                        showButtonPanel: true,
                        dateFormat: 'yy-mm-dd'
                    });
                    // initialize with the date that was clicked
                    
                    $("#reasonModify").val(clickAgendaItem.title);
                    var startMonth = parseInt(clickAgendaItem.startDate.getMonth()) + 1;
                    if(startMonth < 10)
                    {
                        startMonth = "0" + startMonth;
                    }                            
                    var endMonth = parseInt(clickAgendaItem.endDate.getMonth()) + 1;
                    var startDay = clickAgendaItem.startDate.getDate();
                    if(startDay < 10)
                    {
                        startDay = "0" + startDay;
                    }
                    var endDay = clickAgendaItem.endDate.getDate();
                    if(endDay < 10)
                    {
                        endDay = "0" + endDay;
                    }                    
                    $("#startDateModify").val(clickAgendaItem.startDate.getFullYear() + "-" + startMonth + "-" + startDay);
                    $("#endDateModify").val(clickAgendaItem.endDate.getFullYear() + "-" + endMonth + "-" + endDay);			
                    // put focus on first form input element
                    $("#reasonModify").focus();                
                }
                else
                {
                    $("#warningDialog").html("The day off is not editable because its status is not 'Required'.");
                    $('#warningDialog').dialog('open');                       
                }
            }                                                             
		},
		close: function()
        {
			$("#startDateModify").datepicker("destroy");
			$("#endDateModify").datepicker("destroy");
			$("#startDateModify").val("");
			$("#endDateModify").val("");			
			$("#reasonModify").val("");
		}
	});    
    
	$("#modify-event-form-manager").dialog({
		autoOpen: false,
		height: 500,
		width: 400,
		modal: true,
		buttons:
        {
			'Save': function()
            {                
				var what = $("#reasonModifyManager").val();                        
                var status = $( "#status option:selected" ).val(); 
                
                var startDate = $("#startDateModifyManager").val();
                var startDtArray = startDate.split("-");
                var startYear = startDtArray[0];
                // jquery datepicker months start at 1 (1=January)		
                var startMonth = startDtArray[1];		
                var startDay = startDtArray[2];

                var endDate = $("#endDateModifyManager").val();
                var endDtArray = endDate.split("-");
                var endYear = endDtArray[0];
                // jquery datepicker months start at 1 (1=January)		
                var endMonth = endDtArray[1];		
                var endDay = endDtArray[2];

                // Dates use integers
                var startDateObjToDb = startYear + "-" + startMonth + "-" + startDay;
                var endDateObjToDb = endYear + "-" + endMonth + "-" + endDay;
                //alertObject(clickAgendaItem);

                $.ajax(
                {
                    type: "POST",
                    url: encodeURI("http://localhost/timeoffmanager/Calendar/ModifyCalendarItem"),
                    data:{id : clickAgendaItem.data.Identifier, desc: what, from : startDateObjToDb, to : endDateObjToDb, status : status},
                    dataType: "json",
                    cache: false,
                    success: function(data)
                    {       
                        jfcalplugin.addAgendaItem(
                            "#mycal",
                            clickAgendaItem.title,
                            clickAgendaItem.startDate,
                            clickAgendaItem.endDate,
                            true,
                            {
                                Identifier: clickAgendaItem.data.Identifier,
                                Status: getHolidayStatusText(parseInt(status)),
                                Name: clickAgendaItem.data.Name,
                                Email: clickAgendaItem.data.Email
                            },
                            {
                                backgroundColor: clickAgendaItem.displayProp.backgroundColor,
                                foregroundColor: clickAgendaItem.displayProp.foregroundColor
                            }
                        );    
                        jfcalplugin.deleteAgendaItemById("#mycal",clickAgendaItem.agendaId);
                        $("#dayOffsTableDiv").load(encodeURI("../Calendar/CalendarTableDisplay"));
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown)
                    { 
                        jfcalplugin.addAgendaItem(
                            "#mycal",
                            clickAgendaItem.title,
                            clickAgendaItem.startDate,
                            clickAgendaItem.endDate,
                            true,
                            {
                                Identifier: clickAgendaItem.data.Identifier,
                                Status: getHolidayStatusText(parseInt(status)),
                                Name: clickAgendaItem.data.Name,
                                Email: clickAgendaItem.data.Email
                            },
                            {
                                backgroundColor: clickAgendaItem.displayProp.backgroundColor,
                                foregroundColor: clickAgendaItem.displayProp.foregroundColor
                            }
                        );    
                        jfcalplugin.deleteAgendaItemById("#mycal",clickAgendaItem.agendaId);
                        $("#dayOffsTableDiv").load(encodeURI("../Calendar/CalendarTableDisplay"));
                    }                     
                }); 
                $(this).dialog('close');				
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		},
		open: function(event, ui){
            if(clickAgendaItem != null)
            {
                // initialize with the date that was clicked

                $("#reasonModifyManager").val(clickAgendaItem.title);
                var startMonth = parseInt(clickAgendaItem.startDate.getMonth()) + 1;
                var endMonth = parseInt(clickAgendaItem.endDate.getMonth()) + 1;
                var startDay = clickAgendaItem.startDate.getDate();
                if(startDay < 10)
                {
                    startDay = "0" + startDay;
                }
                var endDay = clickAgendaItem.endDate.getDate();
                if(endDay < 10)
                {
                    endDay = "0" + endDay;
                }                    
                $("#startDateModifyManager").val(clickAgendaItem.startDate.getFullYear() + "-" + startMonth + "-" + startDay);
                $("#endDateModifyManager").val(clickAgendaItem.endDate.getFullYear() + "-" + endMonth + "-" + endDay);
                $("#currStatusModifyManager").val(clickAgendaItem.data.Status);
            }                                                             
		},
		close: function()
        {
			$("#startDateModifyManager").val("");
			$("#endDateModifyManager").val("");			
			$("#reasonModifyManager").val("");
		}
	});       
	
	/**
	 * Initialize display event form.
	 */
	$("#display-event-form").dialog({
		autoOpen: false,
		height: 400,
		width: 400,
		modal: true,
		buttons: {		
			'Edit': function() {
				$('#modify-event-form').dialog('open');
			},
			'Delete': function() {
					if(clickAgendaItem != null)
                    {
                        if(clickAgendaItem.data.Status == "Required")
                        {
                            $.ajax(
                            {
                                type: "POST",
                                url: encodeURI("http://localhost/timeoffmanager/Calendar/DeleteCalendarItem"),
                                data:{id : clickAgendaItem.data.Identifier},
                                dataType: "json",
                                cache: false,
                                success: function(data)
                                {       
                                    var oldDiff = new Date(clickAgendaItem.endDate - clickAgendaItem.startDate);
                                    var oldDays = Math.floor(oldDiff/1000/60/60/24);  
                                    
                                    if((currHolidayNum + (oldDays+1)) < currHolidayPartNum)
                                    {
                                        currHolidayNum = currHolidayNum + (oldDays+1);
                                    }
                                    else
                                    {
                                        currHolidayNum = currHolidayPartNum;
                                    }
                                    jfcalplugin.deleteAgendaItemById("#mycal",clickAgendaItem.agendaId);
                                }      
                            }); 
                        }
                        else
                        {
                            $("#warningDialog").html("The day off is not erasable because its status is not 'Required'.");
                            $('#warningDialog').dialog('open');                            
                        }
					}
					$(this).dialog('close');
			},
			Cancel: function() {
				$(this).dialog('close');
			}            
		},
		open: function(event, ui){
			if(clickAgendaItem != null){
				var title = clickAgendaItem.title;
				var startDate = clickAgendaItem.startDate;
				var endDate = clickAgendaItem.endDate;
				var allDay = clickAgendaItem.allDay;
				var data = clickAgendaItem.data;
				// in our example add agenda modal form we put some fake data in the agenda data. we can retrieve it here.
				$("#display-event-form").append(
					"<br><b>" + title+ "</b><br><br>"		
				);				
				if(allDay){
					$("#display-event-form").append(
                        "(All day event)<br><br>" +
						"<b>Starts:</b> " + startDate + "<br>" +
						"<b>Ends:</b> " + endDate + "<br><br>"			
					);				
				}else{
					$("#display-event-form").append(
                        "(All day event)<br><br>" +
						"<b>Starts:</b> " + startDate + "<br>" +
						"<b>Ends:</b> " + endDate + "<br><br>"				
					);				
				}
				for (var propertyName in data) {
					$("#display-event-form").append("<b>" + propertyName + ":</b> " + data[propertyName] + "<br>");
				}			
			}		
		},
		close: function() {
			// clear agenda data
			$("#display-event-form").html("");
		}
	});
    
	/**
	 * Initialize display event form.
	 */
	$("#display-event-form-manager").dialog({
		autoOpen: false,
		height: 400,
		width: 400,
		modal: true,
		buttons: {		
			Cancel: function() {
				$(this).dialog('close');
			}            
		},
		open: function(event, ui){
			if(clickAgendaItem != null){
				var title = clickAgendaItem.title;
				var startDate = clickAgendaItem.startDate;
				var endDate = clickAgendaItem.endDate;
				var allDay = clickAgendaItem.allDay;
				var data = clickAgendaItem.data;
				// in our example add agenda modal form we put some fake data in the agenda data. we can retrieve it here.
				$("#display-event-form-manager").append(
					"<br><b>" + title+ "</b><br><br>"		
				);				
				if(allDay){
					$("#display-event-form-manager").append(
                        "(All day event)<br><br>" +
						"<b>Starts:</b> " + startDate + "<br>" +
						"<b>Ends:</b> " + endDate + "<br><br>"			
					);				
				}else{
					$("#display-event-form-manager").append(
                        "(All day event)<br><br>" +
						"<b>Starts:</b> " + startDate + "<br>" +
						"<b>Ends:</b> " + endDate + "<br><br>"				
					);				
				}
				for (var propertyName in data) {
					$("#display-event-form-manager").append("<b>" + propertyName + ":</b> " + data[propertyName] + "<br>");
				}			
			}		
		},
		close: function() {
			// clear agenda data
			$("#display-event-form-manager").html("");
		}
	});    
    
    function alertObject(obj)
    {      
        for(var key in obj) 
        {
            alert('key: ' + key + '\n' + 'value: ' + obj[key]);
            if( typeof obj[key] === 'object' )
            {
                alertObject(obj[key]);
            }
        }
    }    
}

