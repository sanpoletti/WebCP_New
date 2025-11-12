// Strip off any frame that may surround the page.

// if (self != top) window.open(self.location.href,"_top","",true);
// I would use the above code but Opera 8.51 goes into an infinite loop
// (fixed in Opera 9.0 Beta).  The following works even in Opera 8.51...
if (self != top) window.open("http://www.garrett.nildram.co.uk/calendar/jacs.htm","_top","",true);

// Set up address obfuscation variables
var	a='ma',		b='il',		c='to:',
	d='&#106;&#97;&#99;&#115;' + '&#102;&#101;&#101;&#100;&#98;&#97;&#99;&#107;',
	e='&#64;'+'&#116;&#97;&#114;&#114;&#103;&#101;&#116;'+
	  '&#46;'+'&#105;&#110;&#102;&#111;',
	f='?sub',	g='ject=';

function toggleStrict()
	{// Turn the Strict date processing option on or off

	 for (i=0;i<JACS.cals().length;i++)
		{var jacsCal = document.getElementById(JACS.cals()[i]);

		 jacsCal.strict = !jacsCal.strict;

		 document.getElementById('btnToggleStrict').value =
			'Turn o' + ((jacsCal.strict)?'ff':'n') + ' strict dates';
		}
	};

function toggleDrag()
	{// Turn the calendar dragging option on or off

	 for (var i=0;i<JACS.cals().length;i++)
		{var jacsCal = document.getElementById(JACS.cals()[i]);

		 jacsCal.allowDrag = !jacsCal.allowDrag;

		 document.getElementById('btnToggleDrag').value =
			'Turn o' + ((jacsCal.allowDrag)?'ff':'n') + ' dragging';
		}
	};

function toggleWeekNumbers()
	{// Turn the week number display on or off

	 for (var i=0;i<JACS.cals().length;i++)
		{var jacsCal = document.getElementById(JACS.cals()[i]);

		 jacsCal.weekNumberDisplay = !jacsCal.weekNumberDisplay;

		 document.getElementById('btnToggleWeeks').value =
				'Turn o' + ((jacsCal.weekNumberDisplay)?'ff':'n') + ' week numbering';

		 // Refresh any static calendars so that the change shows immediately.
		 if (!jacsCal.dynamic) JACS.show(jacsCal.ele,jacsCal.id,jacsCal.days);
		}
	};

function trivialAlert(alertText)
	{// Run this when the calendar closes
	 alert(alertText);
	};

function setLanguages(jacsLanguage)
	{// Set all calendars to the chosen language
	 for (var i=0;i<JACS.cals().length;i++)
		{var jacsCal = document.getElementById(JACS.cals()[i]);

		 jacsCal.language = jacsLanguage;
		 jacsSetLanguage(jacsCal);

		 // Refresh any static calendars so that the change shows immediately.
		 if (!jacsCal.dynamic) JACS.show(jacsCal.ele,jacsCal.id,jacsCal.days);
		}
	};

function show(section)
	{children = document.getElementById('contentinner').childNodes;
	 for (var i=0;i<children.length;i++)
		if (children[i].nodeType==1)
			children[i].style.display=(section==children[i].id)?'':'none';
	};

function setWeekDay(myEle)
	{// Set the displayed start day of the week

	 for (var i=0;i<JACS.cals().length;i++)
		{var jacsCal = document.getElementById(JACS.cals()[i]);

		 jacsCal.weekStart = parseInt(myEle.value,10);

		 for (var j=0;j<jacsCal.weekInits.length;j++)
			document.getElementById(jacsCal.id+'WeekInit'+j).innerHTML =
				jacsCal.weekInits[(j+parseInt(myEle.value,10))%jacsCal.weekInits.length];
		}

	 // Adjust the set days for the changed start day
	 setJACSdays();

	 for (var i=0;i<JACS.cals().length;i++)
		{var jacsCal = document.getElementById(JACS.cals()[i]);

		 // Refresh any static calendars so that the change shows immediately.
		 if (!jacsCal.dynamic) {JACS.show(jacsCal.ele,jacsCal.id,jacsCal.days);}
		}
	};

function setBaseDay(myEle)
	{// Set the week numbering Base day
	 for (var i=0;i<JACS.cals().length;i++)
		{var jacsCal = document.getElementById(JACS.cals()[i]);

		 jacsCal.weekNumberBaseDay = parseInt(myEle.value,10);

		 // Refresh any static calendars so that the change shows immediately.
		 if (!jacsCal.dynamic) {JACS.show(jacsCal.ele,jacsCal.id,jacsCal.days);}
		}
	};

function setDateBehaviour(choice)
	{for (var i=0;i<JACS.cals().length;i++)
		{var jacsCal = document.getElementById(JACS.cals()[i]);
		 jacsCal.valuesEnabled = choice;
		}
	
	 document.getElementById('dateSetting').innerHTML = (choice)?'en':'dis';

	 // Set any week days as selected in the demonstration options
	 setJACSdays();
	};

function setJACSdays()
	{// Set the day array to the pattern selected in the demonstration options.
	 for (var j=0;j<JACS.cals().length;j++)
		{var jacsCal = document.getElementById(JACS.cals()[j]);

		 jacsCal.days.length = 0;
		 
		 for (var i=0;i<7;i++) {if (document.getElementById('cbDay'+i).checked) {jacsCal.days.push(i);}}

		 // Refresh any static calendars so that the change shows immediately.
		 if (!jacsCal.dynamic) {JACS.show(jacsCal.ele,jacsCal.id,jacsCal.days);}
		}
	};

function easterDay(inYear)
	{// Determine Easter Day for a year

	 var a = inYear%19              +  1,
		 b = Math.floor(inYear/100) +  1,
		 c = Math.floor(3*b/4)      - 12,
		 d = (11*a+Math.floor((8*b+5)/25)+15-c)%30;

	 d += (d<0)?30:0;
	 d += (d==24||(d==25&&a>11))?1:0;

	 var e = 44-d+((d>23)?30:0);

	 return (new Date(inYear,2,(e+(7-((Math.floor(5*inYear/4)-10-c)+e)%7))));
	};

function writeEmailLink(h,i,j)
	{document.write('<a href="'+a+b+c+d+e+f+g+h+'" title="'+i+'">'+j.replace(/&lt;/g,'<').replace(/&gt;/g,'>')+'<\/a>');};

function formatString(a)	{return a.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&');};

function showDate()
	{var lastModDate = new Date(document.lastModified);

	 document.write(((lastModDate==''||isNaN(lastModDate))
						?'an&nbsp;unknown&nbsp;date'
						:formatDate(lastModDate)
				   )
				  );

	 function formatDate(rawDate)
		{var arrMonthNames = ['January',  'February', 'March',
							  'April',    'May',      'June',
							  'July',     'August',   'September',
							  'October',  'November', 'December'],
			 arrSuffices    = ['st','nd','rd']
			 thisDate       = rawDate.getDate();

		 return thisDate
				+ '<sup>'
				+   (((thisDate> 3 && thisDate<21) ||
					  (thisDate>23 && thisDate<31)
					 )?'th':arrSuffices[thisDate%10-1]
					)
				+ '</sup>&nbsp;'
				+ arrMonthNames[rawDate.getMonth()] + '&nbsp;'
				+ rawDate.getFullYear();
		};
	};

function disable1(calStr)
	{myCal1 = document.getElementById(disable1.JACSid);
	 myCal2 = document.getElementById(calStr);
	 if (myCal1.dateReturned) 
		{myCal2.dates[0] = [new Date(myCal2.baseYear,0,1),
							new Date(myCal1.outputDate.getFullYear(),
									 myCal1.outputDate.getMonth(),
									 myCal1.outputDate.getDate())];
		 if (myCal1.outputDate > myCal2.outputDate)
			{document.getElementById('date8').value = document.getElementById('date7').value;}
		}
	 JACS.show(document.getElementById('date8'),calStr);
	};

function disable2(calStr)
	{myCal1 = document.getElementById(calStr);
	 myCal2 = document.getElementById(disable2.JACSid);
	 if (myCal2.dateReturned) 
		{myCal1.dates[0] = [new Date(myCal2.outputDate.getFullYear(),
									 myCal2.outputDate.getMonth(),
									 myCal2.outputDate.getDate()), 
							new Date(myCal1.baseYear+myCal1.dropDownYears,0,0)];
		 if (myCal1.outputDate > myCal2.outputDate)
			{document.getElementById('date7').value = document.getElementById('date8').value;}
		}
	 JACS.show(document.getElementById('date7'),calStr);
	};

function onLoad()
	{if (new Date()<new Date(2010,9,10))
			{alert('Greetings,\n\n' +
						'Please note that this site has just changed host away from Opal who caused the\n' +
			      'recent extended down time. Thank you for your patience.\n\n'+
						'The URL http://www.tarrget.info/calendar/jacs.htm remains the correct address.\n\n' +
						'I am also in the process of creating a community support site at ' +
						'http://jscalendars.webs.com, I will be adding to it over time and ' +
						'I hope that people will both ask and answer questions in the fora there.\n\n' +
						'This message will self destruct on 10/10/2010.\n\n' +
						'Anthony');
			}
		
	 // Create the calendar structure explicitly now because this
	 // demonstration sets a lot of calendar attributes that may be
	 // chosen before the calendar would exist using the default
	 // behaviour (i.e. before the calendar is invoked for the
	 // first time).

	 JACS.make('jacs',true);

	 // Set demonstration option defaults

	 document.getElementById('btnToggleStrict').value  = 'Turn on strict dates';
	 document.getElementById('btnToggleDrag').value    = 'Turn on dragging';
	 document.getElementById('btnToggleWeeks').value   = 'Turn on week numbering';

	 document.getElementsByName('startDay')[1].checked = true;
	 document.getElementsByName('baseDay')[4].checked  = true;

	 // Set the language drop-down here purely because IE clears
	 // the selected value but fails to clear the drop-down on page refresh.
	 document.getElementById('ddLanguage').selectedIndex=0;

	 for (var i=0;i<JACS.cals().length;i++)
		{var tmpDate = new Date(), jacsCal = document.getElementById(JACS.cals()[i]);

		 // Disable a specific date

		 jacsCal.dates[0] =  new Date(tmpDate.getFullYear(),tmpDate.getMonth(),14);

		 // Disable a range of dates by specifying the start and end date

		 jacsCal.dates[1] = [new Date(tmpDate.getFullYear(),tmpDate.getMonth(),28),
							 new Date(tmpDate.getFullYear(),tmpDate.getMonth()+1,4)];

		 // Disable every Good Friday and Easter Monday in the calendar's range of years

		 for (var j=jacsCal.baseYear;j<(jacsCal.baseYear+jacsCal.dropDownYears);j++)
			{var dtEasterDay = easterDay(j);    // return Easter Sunday
			 jacsCal.dates[jacsCal.dates.length] = new Date(dtEasterDay.setDate(dtEasterDay.getDate()-2));
			 jacsCal.dates[jacsCal.dates.length] = new Date(dtEasterDay.setDate(dtEasterDay.getDate()+3));
			}
		}

	 // Set any week days as selected in the demonstration options
	 setJACSdays();

	 // Strict HTML method of setting TARGET = '_blank'

	 if (document.getElementsByTagName)
		{var anchors = document.getElementsByTagName('a');

		 for (var i=0;i<anchors.length;i++)
			if (anchors[i].getAttribute('href') && anchors[i].getAttribute('rel') == 'external') {anchors[i].target = '_blank';}
		}
	};