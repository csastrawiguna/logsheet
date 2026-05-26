document.addEventListener("DOMContentLoaded", function () {
	const baseUrl = "http://localhost:8080/logsheet/";
	// const baseUrl = "http://192.168.188.254/logsheet/";

	// calendar Leave
	var calendarEl = document.getElementById("calendar");
	var calendar = new FullCalendar.Calendar(calendarEl, {
		initialView: 'dayGridMonth',

		// header Toolbar
		customButtons: {
			myCustomButton: {
				text: "Add new",
				click: function () {
					openModal();
					$("#addEventModalLabel").html('Add Leave Proposal');
					$("#addLeaveType").val('');
					$("#addLeaveStartDate").val('');
					$("#addLeaveEndDate").val('');
					$("#addLeaveReason").val('');
					$("#addLeaveDescription").val('');
					$("#addLeaveStatus").val('');
					$("#addLeaveDatetime").val('');
					$("#buttonAddEventSubmit").show();
					$("#buttonAddEventPurge").hide();
					$("#buttonAddEventDelete").hide();
					$("#buttonAddEventUpdate").hide();
				},
			}			
		},

		headerToolbar: {
			left: "myCustomButton",
			center: "title",
			right: 'today prev,next',
		},

		editable: true,
		selectable: true,
		events: baseUrl + "leave/allCalendarData",

		//Click on date
		dateClick: function (evt) {
			const date = evt.dateStr;
			console.log(evt);
			$.ajax({
				url: baseUrl + "leave/getEventByDate",
				data: { date: date },
				method: "post",
				dataType: "json",
				success: function (data) {
					// console.log(data);					
					$("#cardLeaveByDay .card-header").html('Proposal on : <span class="text-danger">' + toDateLong(evt.dateStr) + "</span>");
					$("#tableCardLeaveByDay tbody").html("");
					$.each(data, function (i, d) {
						$("#tableCardLeaveByDay tbody").append(
							"<tr><td>" +
								(parseInt(i) + 1) +
								"</td><td>" +
								d.agent +
								"</td><td>" +
								toDatetime(d.created_at) +
								"</td></tr>"
						);
					});
				},
			});
		},

		// Click on event
		eventClick: function (info) {
			$("#buttonAddEventSubmit").hide();
			$("#buttonAddEventUpdate").show();
			$("#buttonAddEventPurge").show();
			$("#buttonAddEventDelete").show();
			$("#addEventModalLabel").html('View/Edit Leave Proposal');
			const id = info.event._def.publicId;
			$.ajax({
				url: baseUrl + "leave/getEventById",
				data: {
					id: id,
				},
				method: "post",
				dataType: "json",
				success: function (data) {
					$("#addLeaveId").val(data.id);
					$("#addLeaveType").val(data.permit_type);
					$("#addLeaveStartDate").val(data.start_date);
					$("#addLeaveEndDate").val(data.end_date);
					$("#addLeaveReason").val(data.reason);
					$("#addLeaveDescription").val(data.description);
					$("#addLeaveStatus").val(data.permit_status);
					$("#addLeaveDatetime").val(data.created_at);
				},
			});
			openModal();
		},

		// // Click on date
		// dateClick: function (evt) {
		// 	const date = evt.dateStr;
		// 	$.ajax({
		// 		url: baseUrl + "leave/getEventByDate",
		// 		data: { date: date },
		// 		method: "post",
		// 		dataType: "json",
		// 		success: function (data) {
		// 			// console.log(data);					
		// 			$("#cardLeaveByDay .card-header").html(
		// 				'Proposal on : <span class="text-danger">' +
		// 					toDateLong(evt.dateStr) +
		// 					"</span>"
		// 			);
		// 			$("#tableCardLeaveByDay tbody").html("");
		// 			$.each(data, function (i, d) {
		// 				$("#tableCardLeaveByDay tbody").append(
		// 					"<tr><td>" +
		// 						(parseInt(i) + 1) +
		// 						"</td><td>" +
		// 						d.agent +
		// 						"</td><td>" +
		// 						toDatetime(d.created_at) +
		// 						"</td></tr>"
		// 				);
		// 			});
		// 		},
		// 	});
		// },

		// // Click on event
		// eventClick: function (info) {
		// 	$("#buttonAddEventSubmit").hide();
		// 	$("#buttonAddEventUpdate").show();
		// 	$("#buttonAddEventDelete").show();
		// 	$("#addEventModalLabel").html('View/Edit Leave Proposal');
		// 	const id = info.event._def.publicId;
		// 	$.ajax({
		// 		url: baseUrl + "leave/getEventById",
		// 		data: {
		// 			id: id,
		// 		},
		// 		method: "post",
		// 		dataType: "json",
		// 		success: function (data) {
		// 			$("#addLeaveId").val(data.id);
		// 			$("#addLeaveType").val(data.permit_type);
		// 			$("#addLeaveStartDate").val(data.start_date);
		// 			$("#addLeaveEndDate").val(data.end_date);
		// 			$("#addLeaveReason").val(data.reason);
		// 			$("#addLeaveDescription").val(data.description);
		// 			$("#addLeaveStatus").val(data.permit_status);
		// 			$("#addLeaveDatetime").val(data.created_at);
		// 		},
		// 	});
		// 	openModal();
		// },
		eventColor: '#378006'
	});
	calendar.render();

	function openModal() {
		var locModal = $("addEventModal");
		var btnclose = document.getElementById("buttonClose");
		$("#addEventModal").modal("show");
	}

	function SwalConfirm(title, text, icon, confirmText, link) {
		Swal.fire({
			title: title,
			text: text,
			icon: icon,
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: confirmText,
		}).then((result) => {
			if (result.value) {
				window.location.href = link;
			}
		});
	}

	$("#buttonAddEventDelete").on("click", function () {
		const id = $("#addLeaveId").val();
		var title = "Please confirm!";
		var text = "Are you sure to cancel leave proposal!";
		var icon = "warning";
		var confirmText = "Yes, cancel";
		var link = baseUrl + "leave/dropEventById/" + id;
		SwalConfirm(title, text, link, confirmText, icon);
	});

	$("#buttonAddEventPurge").on("click", function () {
		const id = $("#addLeaveId").val();
		var title = "Please confirm!";
		var text = "Are you sure to delete leave proposal!";
		var icon = "warning";
		var confirmText = "Yes, cancel";
		var link = baseUrl + "leave/deleteEventById/" + id;
		SwalConfirm(title, text, link, confirmText, icon);
	});

	$("#buttonAddEventUpdate").on("click", function () {
		$("#formAddLeave").attr("action", baseUrl + "leave/updateEventById");
		$("#formAddLeave").submit();
	});	

	function fixPercentage(value, precision) {
		var multiplier = Math.pow(10, precision || 0);
		return Math.round(value * 100 * multiplier) / multiplier;
	}

	function toPeriod(data) {
		month = [
			"January",
			"February",
			"March",
			"April",
			"May",
			"June",
			"July",
			"August",
			"September",
			"October",
			"November",
			"December",
		];
		dateArray = data.substring(0, 10).split("-");
		if (dateArray[0] == "0000") {
			return "-";
		} else {
			period = month[parseInt(dateArray[1]) - 1] + " " + dateArray[0];
			return period;
		}
	}

	function toPeriodMin(data) {
		month = [
			"Jan",
			"Feb",
			"Mar",
			"Apr",
			"May",
			"Jun",
			"Jul",
			"Aug",
			"Sep",
			"Oct",
			"Nov",
			"Dec",
		];
		dateArray = data.substring(0, 10).split("-");
		if (dateArray[0] == "0000") {
			return "-";
		} else {
			period =
				month[parseInt(dateArray[1]) - 1] + "-" + dateArray[0].substr(2, 2);
			return period;
		}
	}

	function toDatetime(data) {
		month = [
			"Jan",
			"Feb",
			"Mar",
			"Apr",
			"May",
			"Jun",
			"Jul",
			"Aug",
			"Sep",
			"Oct",
			"Nov",
			"Dec",
		];
		dateArray = data.substring(0, 10).split("-");
		if (dateArray[0] == "0000") {
			return "-";
		} else {
			date =
				dateArray[2] +
				"-" +
				month[parseInt(dateArray[1]) - 1] +
				"-" +
				dateArray[0];
			time = data.substring(11);
			return date + " " + time;
		}
	}

	function toDateLong(data) {
		month = [
			"January",
			"February",
			"March",
			"April",
			"May",
			"June",
			"July",
			"August",
			"September",
			"October",
			"November",
			"December",
		];
		dateArray = data.substring(0, 10).split("-");
		if (dateArray[0] == "0000") {
			return "-";
		} else {
			date =
				dateArray[2] +
				" " +
				month[parseInt(dateArray[1]) - 1] +
				" " +
				dateArray[0];
			// time = data.substring(11);
			return date;
		}
	}

	// Sweat Alert for Flash Message
	const flashData = $(".flashmessage").html();
	const arr = flashData.split("|");
	const title = arr[0];
	const type = arr[1];
	const text = arr[2];
	
	if (flashData) {
		Swal.fire({
			title: title,
			text: text,
			icon: arr[1],
		});
	}

	// Confim Sweat Alert
	function SwalConfirm(title, text, link, confirmText, icon) {
		Swal.fire({
			title: title,
			text: text,
			icon: icon,
			showCancelButton: true,
			confirmButtonColor: "#007BFF",
			cancelButtonColor: "#DC3545",
			confirmButtonText: confirmText,
		}).then((result) => {
			if (result.value) {
				window.location.href = link;
			}
		});
	}

	// Sweat Alert Info
	function SwalInfo(title, text, icon) {
		Swal.fire({
			title: title,
			text: text,
			icon: icon
		});
	}
});
