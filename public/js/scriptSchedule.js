jQuery(document).ready(function($){

	/* --------------------------------- AFFICHAGE DU SCHEDULE (CODEPEN) --------------------------------- */

	function SchedulePlan( element ) {
		this.element = element;
		this.timeline = this.element.find('.timeline');
		this.timelineItems = this.timeline.find('li');
		this.timelineItemsNumber = this.timelineItems.length;
		this.timelineStart = getScheduleTimestamp(this.timelineItems.eq(0).text());
		//besoin de stocker le delta (ici 30min) timestamp
		this.timelineUnitDuration = getScheduleTimestamp(this.timelineItems.eq(1).text()) - getScheduleTimestamp(this.timelineItems.eq(0).text());

		this.eventsWrapper = this.element.find('.events');
		this.eventsGroup = this.eventsWrapper.find('.events-group');
		this.singleEvents = this.eventsGroup.find('.single-event');
		this.eventSlotHeight = this.eventsGroup.eq(0).children('.top-info').outerHeight();

		this.initSchedule();
	}

	SchedulePlan.prototype.initSchedule = function() {
		this.scheduleReset();
		this.initEvents();
	};

	SchedulePlan.prototype.scheduleReset = function() {
		var mq = this.mq();
		if( mq == 'desktop' && !this.element.hasClass('js-full') ) {
			this.eventSlotHeight = this.eventsGroup.eq(0).children('.top-info').outerHeight();
			this.element.addClass('js-full');
			this.placeEvents();
		} else {
			this.element.removeClass('loading');
		}
	};

	SchedulePlan.prototype.initEvents = function() {
		var self = this;
		this.singleEvents.each(function(){
			//créer le .event-date element pour chaque element
			if($(this).attr('data-event') != 'event-0') {
				var durationLabel = '<span class="event-date">'+$(this).data('start')+' - '+$(this).data('end')+'</span>';
				$(this).prepend($(durationLabel));
			}
		});
	};

	SchedulePlan.prototype.placeEvents = function() {
		var self = this;
		this.singleEvents.each(function(){
			//place chaque element dans la grille -> besoin de set top position et height
			var start = getScheduleTimestamp($(this).attr('data-start')),
				duration = getScheduleTimestamp($(this).attr('data-end')) - start;

			var eventTop = self.eventSlotHeight*(start - self.timelineStart)/self.timelineUnitDuration,
				eventHeight = self.eventSlotHeight*duration/self.timelineUnitDuration;
			
			$(this).css({
				top: (eventTop -1) +'px',
				height: (eventHeight+1)+'px'
			});
		});

		this.element.removeClass('loading');
	};

	SchedulePlan.prototype.mq = function(){
		//récupérer valeur MQ ('desktop' ou 'mobile') 
		var self = this;
		return window.getComputedStyle(this.element.get(0), '::before').getPropertyValue('content').replace(/["']/g, '');
	};

	var schedules = $('.cd-schedule');
	var objSchedulesPlan = [],
		windowResize = false;
	
	if( schedules.length > 0 ) {
		schedules.each(function(){
			//cration objet SchedulePlan
			objSchedulesPlan.push(new SchedulePlan($(this)));
		});
	}

	function getScheduleTimestamp(time) {
		//accepts hh:mm format -> convert hh:mm pour timestamp
		time = time.replace(/ /g,'');
		var timeArray = time.split(':');
		var timeStamp = parseInt(timeArray[0])*60 + parseInt(timeArray[1]);
		return timeStamp;
	}





	/* --------------------------------- CODE PERSO --------------------------------- */

	/* -------------- Variable global -------------- */
	var weeknum = 1;
	var annee = 2022;

	var mondayDate;
	var tuesdayDate;
	var wednesdayDate;
	var thursdayDate;
	var fridayDate;
	var saturdayDate;
	var sundayDate;

	/* -------------- Prévisualisation meeting desired date -------------- */
	function displayDesiredDateOfMeeting() {
		var meetingWeekNum = $("#meetingDate").attr('data-weekNum');
		if(meetingWeekNum == weeknum) {
			var date = $("#meetingDate").attr('data-date').split("/");
			var meetingDate = date[2] + date[1] + date[0];
			
			switch (meetingDate) {
				case mondayDate:
					$('.events-group').eq(0).children('ul').children('#currentMeeting').fadeIn(200);
					break;
				case tuesdayDate:
					$('.events-group').eq(0).children('ul').children('#currentMeeting').fadeIn(200);
					break;
				case wednesdayDate:
					$('.events-group').eq(0).children('ul').children('#currentMeeting').fadeIn(200);
					break;
				case thursdayDate:
					$('.events-group').eq(0).children('ul').children('#currentMeeting').fadeIn(200);
					break;
				case fridayDate:
					$('.events-group').eq(0).children('ul').children('#currentMeeting').fadeIn(200);
					break;
				case saturdayDate:
					$('.events-group').eq(0).children('ul').children('#currentMeeting').fadeIn(200);
					break;
				case sundayDate:
					$('.events-group').eq(0).children('ul').children('#currentMeeting').fadeIn(200);
					break;
			}
		}
	}

	/* -------------- Cache tout les meetings -------------- */
	$('.single-event').each(function(){
		$(this).hide();
	});
	
	/* -------------- Display au lancement -------------- */
	var today = new Date(2022, 3, 3);
	weeknum = getWeekNum(today);
	annee = today.getFullYear();
	$('#weekNum').text("Semaine : " + weeknum);
	displayByWeekNum(weeknum, annee);
	displayDesiredDateOfMeeting();



	/* -------------- Trigger des arrows -------------- */
	$( "#leftArrowWeekNum" ).click(function() {
		weeknum -= 1;
		$('#weekNum').text("Semaine : " + weeknum);
		displayByWeekNum(weeknum, annee);
		displayDesiredDateOfMeeting();
	});

	$( "#rightArrowWeekNum" ).click(function() {
		weeknum += 1;
		$('#weekNum').text("Semaine : " + weeknum);
		displayByWeekNum(weeknum, annee);
		displayDesiredDateOfMeeting();
	});



	function getWeekNum(date) {
		date = getFirstDayOfWeek(date);
		var annee = date.getFullYear();
		var month = date.getMonth();

		var numberOfDays = 0;
		for (let i = month - 1; i >= 0; i--) {
			numberOfDays += new Date(annee, month - i, 0).getDate();
		}
		numberOfDays = numberOfDays - getFirstDayOfFirstWeekOfYear(annee).getDate() + date.getDate() + 7;
		var weekNum = numberOfDays / 7;
		return weekNum;
	}

	function getFirstDayOfWeek(date) {
		var numDayOfWeek = date.getDay(); //get day of week
		var numDayOfMonth = date.getDate(); //get day of month
		var firstDayNum;
		if(numDayOfWeek != 0) { firstDayNum = numDayOfMonth - numDayOfWeek + 1; }
		else { firstDayNum = numDayOfMonth - numDayOfWeek - 6; }
		date.setDate(firstDayNum);
		return date
	}



	function displayByWeekNum(weekNum, year) {
		/* -------------- Refresh les entetes du planinng -------------- */
		var firstDayOfFirstWeekOfYear = getFirstDayOfFirstWeekOfYear(year);
		var firstDayOfWeekNum = firstDayOfFirstWeekOfYear;
		firstDayOfWeekNum.setDate((weekNum - 1) * 7 + firstDayOfFirstWeekOfYear.getDate());
		var dayOfWeek = firstDayOfWeekNum;

		var topInfo = $('.top-info');
		for (var i = 0; i < topInfo.length; i++) {
			switch(i) {
				case 0:
					topInfo.eq(0).children().text("Monday " + dayOfWeek.toLocaleDateString());
					var date = dayOfWeek.toLocaleDateString().split("/");
					mondayDate = date[2] + date[1] + date[0];
				  	break;
				case 1:
					topInfo.eq(1).children().text("Tuesday " + dayOfWeek.toLocaleDateString());
					var date = dayOfWeek.toLocaleDateString().split("/");
					tuesdayDate = date[2] + date[1] + date[0];
				  	break;
				case 2:
					topInfo.eq(2).children().text("Wednesday " + dayOfWeek.toLocaleDateString());
					var date = dayOfWeek.toLocaleDateString().split("/");
					wednesdayDate = date[2] + date[1] + date[0];
				  	break;
				case 3:
					topInfo.eq(3).children().text("Thursday " + dayOfWeek.toLocaleDateString());
					var date = dayOfWeek.toLocaleDateString().split("/");
					thursdayDate = date[2] + date[1] + date[0];
				  	break;
				case 4:
					topInfo.eq(4).children().text("Friday " + dayOfWeek.toLocaleDateString());
					var date = dayOfWeek.toLocaleDateString().split("/");
					fridayDate = date[2] + date[1] + date[0];
				  	break;
				case 5:
					topInfo.eq(5).children().text("Saturday " + dayOfWeek.toLocaleDateString());
					var date = dayOfWeek.toLocaleDateString().split("/");
					saturdayDate = date[2] + date[1] + date[0];
				  	break;
				case 6:
					topInfo.eq(6).children().text("Sunday " + dayOfWeek.toLocaleDateString());
					var date = dayOfWeek.toLocaleDateString().split("/");
					sundayDate = date[2] + date[1] + date[0];
				  	break;
			}
			dayOfWeek.setDate(dayOfWeek.getDate() + 1);
		}

		/* -------------- Refresh les éléments du planning -------------- */
		$('.single-event').each(function(){
			var weekNumOfElement = $(this).attr('data-weekNum');
			if(weekNumOfElement != weeknum) {
				$(this).fadeOut(200);
			}
			else {
				$(this).fadeIn(200);
			}
		});
	}

	function getFirstDayOfFirstWeekOfYear(year) {
		var fourthDayOfYear = new Date(year, 0, 4)
		var firstDayOfFirstWeekOfYear = fourthDayOfYear;
		firstDayOfFirstWeekOfYear.setDate(fourthDayOfYear.getDate() - fourthDayOfYear.getDay() + 1);
		return firstDayOfFirstWeekOfYear
	}
});