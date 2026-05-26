$(function () {
	$(".preloader").fadeOut();
	const baseUrl = jsVar.baseUrl;

	// ----------------------------------------------------------------------
	// SCRIPT ON EACH MENU

	// DASHBOARD
	// Sholat Time
	var sholatContainer = $("#sholatTimeContainer");
	$.ajax({
        url: baseUrl + "dashboard/get_sholat_session",
        type: "GET",
        dataType: "json",
        success: function(res) {
            if (!res) {
                // 2. Mun session KOSONG, nembak API Luar
                console.log("Session kosong, nembak API luar...");
                ambilJadwalApiLuar();
            } else {
                // 3. Mun session AYA, langsung jalankeun panginget
                console.log("Jadwal dicokot tina session.");
                mulaiPelingSholat(res);
                updateTampilanTabel(res);
            }
        }
    });

	// Fungsi nembak API MyQuran
	function ambilJadwalApiLuar() {
	    const kotaId = "1210"; // Karawang
	    const tgl = jsVar.tglHariIni;

	    $.get(`https://api.myquran.com/v2/sholat/jadwal/${kotaId}/${tgl}`, function(apiRes) {
	        if (apiRes.status) {
	            const jadwal = apiRes.data.jadwal;

	            // Simpen hasil API ka Session PHP via AJAX
	            $.post(baseUrl + "dashboard/set_sholat_session", {
	                jadwal: jadwal
	            }, function() {
	                mulaiPelingSholat(jadwal);
	                updateTampilanTabel(jadwal);
	            });
	        }
	    });
	}

	function updateTampilanTabel(jadwal) {
	    const d = new Date();
	    const jamSekarang = d.getHours().toString().padStart(2, '0') + ":" + 
	                        d.getMinutes().toString().padStart(2, '0');

	    // 1. Daptar Id jeung Waktuna
	    const mapping = {
	        '#sholatTimeImsak': jadwal.imsak,
	        '#sholatTimeSubuh': jadwal.subuh,
	        '#sholatTimeTerbit': jadwal.terbit,
	        '#sholatTimeDhuha': jadwal.dhuha,
	        '#sholatTimeZhuhur': jadwal.dzuhur,
	        '#sholatTimeAshar': jadwal.ashar,
	        '#sholatTimeMaghrib': jadwal.maghrib,
	        '#sholatTimeIsya': jadwal.isya
	    };

	    // Bersihkeun heula class aktif bilih aya nu nempel tina menit saencana
	    $('.cardEachSholatTime').removeClass('sholat-aktif');

	    // 2. Eusi angka jeung teangan mana nu kudu di-highlight
	    let idAktif = "";
	    let waktuSorted = Object.entries(mapping).sort((a, b) => a[1].localeCompare(b[1]));

	    for (let i = 0; i < waktuSorted.length; i++) {
	        const [id, waktu] = waktuSorted[i];
	        $(id).find('.text-bold').text(waktu);

	        // Logika: Lamun jam ayeuna geus ngaliwatan waktu sholat ieu...
	        if (jamSekarang >= waktu) {
	            idAktif = id; // Tandai ieu nu keur jalan
	        }
	    }

	    // 3. Pasang class hurungna
	    if (idAktif) {
	        $(idAktif).addClass('sholat-aktif');
	    }
	}

	// Fungsi Notifikasi Toastr
	let sholatTerakhir = ""; 

	function mulaiPelingSholat(jadwal) {
	    // Settingan dasar Toastr
	    toastr.options = {
	        "closeButton": true,
	        "progressBar": true,
	        "positionClass": "toast-top-right",
	        "timeOut": "30000" // Muncul 30 detik
	    };

	    setInterval(function() {
	        const d = new Date();
	        // Ambil Jam:Menit (conto: 12:05)
	        const waktuSekarang = d.getHours().toString().padStart(2, '0') + ":" + 
	                            d.getMinutes().toString().padStart(2, '0');

	        const daftarSholat = {
	            'Subuh': jadwal.subuh,
	            'Dzuhur': jadwal.dzuhur,
	            'Ashar': jadwal.ashar,
	            'Maghrib': jadwal.maghrib,
	            'Isya': jadwal.isya
	        };

	        // Cek naha jam ayeuna sarua jeung salah sahiji waktu sholat
	        for (const [nama, waktu] of Object.entries(daftarSholat)) {
	            if (waktuSekarang === waktu && sholatTerakhir !== nama) {
	                
	                // Tampilkeun Toastr
	                toastr.info(
	                    `Tos lebet waktu Sholat ${nama} Wilayah Karawang dan sekitarnya.`, 
	                    'Reminder Sholat'
	                );

	                if (nama === 'Maghrib') {
	                	// Sora haaa
	                	// let audio = new Audio('https://www.soundjay.com/misc_c2026/sounds/bell-ringing-03a.mp3');
	                	// Sora alarm/bel
	                	// let audio = new Audio(baseUrl + 'assets/audio/notification/compressed_soundreality-alarm-471496.mp3');
	                	// Sora adzan
		                let audio = new Audio(baseUrl + 'assets/audio/notification/compressed_adzan_fajr_128_44_64kbps.mp3');
		                audio.play();
	                }

	                // TAMBAHAN: Update warna card unggal menit
        			updateTampilanTabel(daftarSholat);

	                sholatTerakhir = nama; // Tandai geus notif sangkan teu gandeng terus dina menit nu sarua
	                break;
	            }
	        }
	    }, 30000); // Cek unggal 30 detik
	}

	// table for leave data
	$("#dashboardTableLeaveBalance").DataTable({
		"autoWidth" : false,
		"searching" : false,
		"lengthChange" : false,
		"info" : false
	});	

	$(".btnQueue").on("click", function(){
		var agent = this.dataset.agent;
		location.href = baseUrl + 'dashboard/addqueue/' + agent; 
	});

	$(".btnQueueAgain").on("click", function(){
		var agent = this.dataset.agent;
		var link = baseUrl + 'dashboard/addqueue/' + agent; 
		const title = "Yakin antri lagi?";
		const text = ".....";
		SwalConfirm(title, text, link, "Of course", "warning"); 
	});

	$(".btnFinish").on("click", function(){
		var agent = this.dataset.agent;
		var link = baseUrl + 'dashboard/addfinish/' + agent; 
		const title = "Cek Lagi";
		const text = "Yakin sudah selesai MCU?";
		SwalConfirm(title, text, link, "Yes, finish", "warning");
	});

	$(".btnReset").on("click", function(){
		var agent = this.dataset.agent;
		var link = baseUrl + 'dashboard/toreset/' + agent;
		const title = "Yakin Reset?";
		const text = "Kalau di-reset harus kembali antri";
		SwalConfirm(title, text, link, "Reset", "warning"); 
	});

	// General Voting

	$(".container-fluid").on("click", ".buttonSubmitVote", function(){
		var id = this.dataset.id;
		// ajax append vote id
		$.ajax({
			url : baseUrl + 'dashboard/getVoteById',
			data : {id : id},
			method : "post",
			dataType : "json",
			success : function(data) {
				$("#submitVoteId").val(data.id);
				$("#modalGeneralVoteTitle").html(data.vote_name);
			}
		});
		// ajax show vote list
		$.ajax({
			url : baseUrl + 'dashboard/getVoteByAgent',
			data : {id : id},
			method : "post",
			dataType : "html",
			success : function(data) {
				$("#dataListContainer").html("");
				$("#dataListContainer").append(data);
			}
		})
	});

	$(".container-fluid").on("click", ".buttonViewResult", function(){
		var id = this.dataset.id;
		// ajax append vote id
		$.ajax({
			url : baseUrl + 'dashboard/getVoteById',
			data : {id : id},
			method : "post",
			dataType : "json",
			success : function(data) {
				$("#modalResultVoteTitle").html(data.vote_name);
			}
		});
		// ajax summary Vote
		$.ajax({
			url : baseUrl + 'dashboard/summaryVote',
			data : {id : id},
			method : "post",
			dataType : "html",
			success : function(data) {
				$("#containerVoteSummary").html("");
				$("#containerVoteSummary").append(data);
			}
		});
		// ajax show detail vote list
		$.ajax({
			url : baseUrl + 'dashboard/detailResultVote',
			data : {id : id},
			method : "post",
			dataType : "html",
			success : function(data) {
				$("#containerVoteDetailResult").html("");
				$("#containerVoteDetailResult").append(data);
			}
		});
	});

	// Lebaran Operation
	// Summernote for add new complaint
	$('#addLebaranReportComplaintUrgentDetail').summernote({
		toolbar: [
			['para', ['ul', 'ol', 'paragraph']],
		    ['style', ['bold', 'italic', 'underline', 'clear']],
		    ['view', ['codeview']]
		],
		placeholder: 'Detail keluhan urgent',
		disableDragAndDrop : true,
		height: "200px",
		callbacks: {
			onPaste: function (e) {
				var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');

				e.preventDefault();

				// Firefox fix
				setTimeout(function () {
					document.execCommand('insertText', false, bufferText);
				}, 10);
			}
		}
	});

	$('#addLebaranReportComplaintRemark').summernote({
		toolbar: [
		    ['para', ['ul', 'ol', 'paragraph']],
		    ['view', ['codeview']]
		],
		placeholder: 'Catatan jika ada error sistem, listrik padam, dsb.',
		disableDragAndDrop : true,
		height: "100px",
		callbacks: {
			onPaste: function (e) {
				var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
				e.preventDefault();

				// Firefox fix
				setTimeout(function () {
					document.execCommand('insertText', false, bufferText);
				}, 10);
			}
		}
	});

	$("#buttonAddLebaranOperationReport").on("click", function(){
		$("#modalLebaranOperationReport form").attr("action", baseUrl + "dashboard/addLebaranReport");
		$("#modalLebaranOperationReportLabel").html("Add Lebaran Operation Report");
		$("#buttonAddNewLebaranOperationData").html('<i class="fas fa-save"></i> Save');
		$("#addLebaranReportId").val("");
        $("#addLebaranReportCallInbound").val(0);
        $("#addLebaranReportCallAcd").val(0);
        $("#addLebaranReportCallCar").val(0);
        $("#addLebaranReportWhatsappResolved").val(0);
        $("#addLebaranReportWhatsappOngoing").val(0);
        $("#addLebaranReportEmailReplied").val(0);
        $("#addLebaranReportFollowup").val(0);
        $("#addLebaranReportComplaintReguler").val(0);
        $("#addLebaranReportComplaintUrgentQty").val(0);
        $("#addLebaranReportComplaintUrgentDetail").html("");
        $("#addLebaranReportComplaintRemark").html("");
	});

	$(".buttonEditLebaranOperationData").on("click", function(e){
		e.preventDefault();
		$("#modalLebaranOperationReport form").attr("action", baseUrl + "dashboard/editLebaranReport");
		$("#modalLebaranOperationReportLabel").html("Edit Lebaran Operation Report");
		$("#buttonAddNewLebaranOperationData").html('<i class="fas fa-save"></i> Update');
		
		var id = this.dataset.id;
		$.ajax({
			url : baseUrl + "dashboard/getSingleLebaranOperationData",
			data : {id : id},
			method : "post",
			dataType : "json",
			success : function(data) {
				$("#addLebaranReportId").val(data.id);
				$("#addLebaranReportDate").val(data.date);
				$("#addLebaranReportCallInbound").val(data.inbound);
		        $("#addLebaranReportCallAcd").val(data.acd);
		        $("#addLebaranReportCallCar").val(data.car);
		        $("#addLebaranReportWhatsappResolved").val(data.wa_resolved);
		        $("#addLebaranReportWhatsappOngoing").val(data.wa_ongoing);
		        $("#addLebaranReportEmailReplied").val(data.email_replied);
		        $("#addLebaranReportFollowup").val(data.followup);
		        $("#addLebaranReportComplaintReguler").val(data.complaint_reguler);
		        $("#addLebaranReportComplaintUrgentQty").val(data.complaint_urgent_qty);
		        $("#addLebaranReportComplaintUrgentDetail").summernote('reset');
		        $("#addLebaranReportComplaintRemark").summernote('reset');
		        $("#addLebaranReportComplaintUrgentDetail").summernote('pasteHTML', data.complaint_urgent_detail);
		        $("#addLebaranReportComplaintRemark").summernote('pasteHTML', data.remark);
			}
		});
	});

	// ===================================================================

	// ABSENCE

	$("#tableAbsenceByAgentDetail").DataTable({		
		"autoWidth" : false
	});

	$("#tableAbsenceDetail").DataTable({
		"autoWidth" : false
	});

	// delete absent data
	$(".container-fluid").on("click", ".buttonAbsentDataDelete",function(e){
		e.preventDefault();
		const id = this.dataset.id;
		var title = "Are you sure to delete?";
		var text = "You won't be able to revert this Absence data";
		var link = baseUrl + "absence/deleteAbsenceById/" + id;
		SwalConfirm(title, text, link, "Delete", "warning");		
	});

	// edit absent data
	$(".container-fluid").on("click", ".buttonAbsentDataEdit",function(e){
		e.preventDefault();
		const id = this.dataset.id;
		$.ajax({
			url: baseUrl + "absence/absentById",
			method: "post",
			data: {id : id},
			dataType: "json",			
			success : function(data) {
				$("#absentAdd form").attr("action", baseUrl + "absence/editAbsentById");
				$("#absentAddLabel").html("Edit Absent Data");
				$("#absentAddDateid").val(data.absent_id);
				$("#absentAddDate").val(data.absent_date);
				$("#absentAddAgent").val(data.cti_id);
				$("#absentAddAgent").trigger('change');
				$("#absentAddPermitType").val(data.permit_type);
				$("#absentAddPermitType").trigger('change');
				$("#absentAddReason").val(data.permit_reason);
				$("#absentAddRemark").val(data.permit_remark);
				$("#absentAddSubmit").css("display", "none");
				$("#absentAddUpdate").show();
			}
		});
	});

	$("#buttonAbsentAdd").on("click", function(){
		$("#absentAddLabel").html("Add Absent Data");
		$("#absentAddDate").val('');
		$("#absentAddAgent").val('');
		$("#absentAddPermitType").val('');
		$("#absentAddReason").val('');
		$("#absentAddRemark").val('');
		$("#absentAddSubmit").show();
		$("#absentAddUpdate").css("display", "none");
	});

	// export to Excel absence detail 
	$("#buttonToExcelAbsentDetail").on("click", function(){
		var startPeriod = $("#absentDetailDateStart").val();
		var endPeriod = $("#absentDetailDateEnd").val();
		location.href = baseUrl + 'absence/toExcelAbsentDetail/' + startPeriod + '/' + endPeriod;
	});

	// var ticksStyle = {
	// 	fontColor: '#495057',
	// 	fontStyle: 'bold'
	// };

	// var mode = 'index';
	// var intersect = true;

	// var $visitorsChart = $('#visitors-chart');
	// var visitorsChart  = new Chart($visitorsChart, {
	// 	data   : {
	// 	labels  : ['Apr-20', 'May-20', 'Jun-20', 'Jul-20', 'Aug-20', 'Sep-20'],
	// 	datasets: [{
	// 		type : 'line',
	// 		data : [100, 120, 170, 167, 180, 177],
	// 		backgroundColor : 'transparent',
	// 		borderColor : '#dc3545',
	// 		pointBorderColor : '#dc3545',
	// 		pointBackgroundColor : '#dc3545',
	// 		fill : false
	// 		// pointHoverBackgroundColor: '#007bff',
	// 		// pointHoverBorderColor    : '#007bff'
	// 	},
	// 	{
	// 		type : 'line',
	// 		data : [60, 80, 70, 67, 80, 77],
	// 		backgroundColor : 'tansparent',
	// 		borderColor : '#ced4da',
	// 		pointBorderColor : '#ced4da',
	// 		pointBackgroundColor : '#ced4da',
	// 		fill : false
	// 		// pointHoverBackgroundColor: '#ced4da',
	// 		// pointHoverBorderColor    : '#ced4da'
	// 	}]
	// 	},
	// 	options: {
	// 		maintainAspectRatio: false,
	// 		tooltips : {
	// 			mode : mode,
	// 			intersect: intersect
	// 		},
	// 		hover : {
	// 			mode : mode,
	// 			intersect: intersect
	// 		},
	// 		legend : {
	// 			display: false
	// 		},
	// 		scales : {
	// 			yAxes: [{
	// 				// display: false,
	// 				gridLines: {
	// 					display : true,
	// 					lineWidth : '4px',
	// 					color : 'rgba(0, 0, 0, .2)',
	// 					zeroLineColor : 'transparent'
	// 				},
	// 				ticks : $.extend({
	// 					beginAtZero : true,
	// 					suggestedMax: 200
	// 				}, ticksStyle)
	// 			}],
	// 			xAxes: [{
	// 				display : true,
	// 				gridLines: {
	// 					display: false
	// 				},
	// 				ticks : ticksStyle
	// 			}]
	// 		}
	// 	}
	// });

	// // chart By Agent
	// var $salesChart = $('#sales-chartX');
	// var salesChart  = new Chart($salesChart, {
	// 	type   : 'bar',
	// 	data   : {
	// 		labels  : ['Apr-20', 'May-20', 'Jun-20', 'Jul-20', 'Aug-20', 'Sep-20'],
	// 		datasets: [
	// 			{
	// 				backgroundColor: '#dc3545',
	// 				borderColor    : '#dc3545',
	// 				data           : [2, 2, 3, 3, 5, 2]
	// 			},
	// 			{
	// 				backgroundColor: '#dcd4da',
	// 				borderColor    : '#dcd4da',
	// 				data           : [7, 2, 3, 2, 1, 0]
	// 			}
	// 		]
	// 	},		
	// });

	// // Load data for chart
	// var agent = $("#absenceByAgentSelectAgent").val();
	// var startPeriod = $("#absenceByAgentDateStart").val();
	// var endPeriod = $("#absenceByAgentDateEnd").val();
	// $.ajax({
	// 	url: baseUrl + "absence/absentDataForChart",
	// 	method: "post",
	// 	data: {
	// 		agent: agent,
	// 		startPeriod: startPeriod,
	// 		endPeriod: endPeriod
	// 	},
	// 	dataType: "json",
	// 	success: function(data){
	// 		var $salesChart = $('#sales-chart');
	// 		var salesChart  = new Chart($salesChart, {
	// 			type   : 'bar',
	// 			data   : {
	// 				labels  : data.labels,
	// 				datasets: [
	// 					{
	// 						backgroundColor: '#dc3545',
	// 						borderColor    : '#dc3545',
	// 						data           : data.sick
	// 					},
	// 					{
	// 						backgroundColor: '#dcd4da',
	// 						borderColor    : '#dcd4da',
	// 						data           : data.unpaid_leave
	// 					}
	// 				]
	// 			},
	// 			options: {
	// 				maintainAspectRatio: false,
	// 				tooltips : {
	// 					mode : mode,
	// 					intersect: intersect
	// 				},
	// 				hover : {
	// 					mode : mode,
	// 					intersect: intersect
	// 				},
	// 				legend : {
	// 					display: false
	// 				},
	// 				scales : {
	// 					yAxes: [{
	// 						// display: false,
	// 						gridLines: {
	// 							display      : true,
	// 							lineWidth    : '4px',
	// 							color        : 'rgba(0, 0, 0, .2)',
	// 							zeroLineColor: 'transparent'
	// 						},
	// 						ticks    : $.extend({
	// 							beginAtZero: true,								
	// 						}, ticksStyle)
	// 					}],
	// 					xAxes: [{
	// 						display : true,
	// 						gridLines : {
	// 							display : false
	// 						},
	// 						ticks : ticksStyle
	// 					}]
	// 				}
	// 			}
	// 		});
	// 	}
	// });

	// ===================================================

	// AUX
	$("#tableSummaryAuxMonthly").DataTable({
		"info" : false,
	});

	// ===================================================

	// BLACKBOOK
	$("#tableBlackbookDetail").DataTable();
	$("#tableBlackbookByAgent").DataTable();
	$("#tableSummaryBlackbook").DataTable({
		"pageLength" : 100,
		"info" : false,
		"searching" : false,
		"paging" : false
	});	

	// delete blaknote data
	$(".container-fluid").on("click", ".buttonBlackbookDataDelete", function(e){
		e.preventDefault();
		const id = this.dataset.id;
		var title = "Are you sure to delete?";
		var text = "You won't be able to revert this Black note";
		var link = $(this).attr("href");
		SwalConfirm(title, text, link, "Delete it", "warning");
	});

	$("#blackBookAddButton").on("click", function(){
		$("#blackBookAddLabel").html("Add new agent's blacknote finding");
		$("#blackbookAddId").val("");
		$("#blackbookAddAgent").val("");
		$("#blackbookAddDate").val("");
		$("#blackbookAddSinType").val("");
		$("#blackbookAddDetail").val("");
		$("#blackbookAddRemark").val("");
		$("#blackbookAddVoicelink").val("");
		$("#blackbookAddReset").show();	
		$("#blackbookAddSubmit").html('<i class="fas fa-save"></i> Save');
	});

	$(".container-fluid").on("click", ".buttonBlackbookDataEdit", function(e){
		e.preventDefault();
		$("#blackBookAddLabel").html("Edit blackbook data");
		$("#blackbookAddReset").hide();	
		$("#blackbookAddSubmit").html('<i class="fas fa-check"></i> Update');
		$("#blackBookAdd .modal-dialog .modal-content form").attr("action", baseUrl + "blackbook/editBlackbookById");
		const id = this.dataset.id;
		$.ajax({
			url : baseUrl + "blackbook/blackbookById/",
			method: "post",
			data : {
				id : id
			},
			dataType : "json",
			success : function(data) {
				console.log(data);
				$("#blackbookAddId").val(data.id);
				$("#blackbookAddAgent").val(data.agent);
				$("#blackbookAddDate").val(data.date);
				$("#blackbookAddSinType").val(data.type);
				$("#blackbookAddDetail").val(data.detail);
				$("#blackbookAddRemark").val(data.remark);
				$("#blackbookAddVoicelink").val(data.voice_link);
			}
		});
	});

	$(".container-fluid").on("click", ".buttonRepeatQuestionDelete", function(e){
		e.preventDefault();
		const id = this.dataset.id;
		var title = "Are you sure to delete?";
		var text = "You won't be able to revert this Black note";
		var link = $(this).attr("href");
		SwalConfirm(title, text, link, "Delete it", "warning");
	});

	$("#buttonToExcelBlackbookDetail").on("click", function(){
		var startPeriod = $("#blackbookDetailDateStart").val();
		var endPeriod = $("#blackbookDetailDateEnd").val();
		location.href = baseUrl + 'blackbook/toExcelBlackbookDetail/' + startPeriod + '/' + endPeriod;
	});

	// chart summary blackbook
	$("#selectSummaryBlackbookStart").ready(function(){
		$.ajax({
			url: baseUrl + "blackbook/getSummary",
			method: "post",
			data: {
				startPeriod: $("#selectSummaryBlackbookStart").val(),
				endPeriod: $("#selectSummaryBlackbookEnd").val()
			},
			dataType: "json",
			success: function(data){
				var $blackbookSummaryChart = $('#blackbookSummaryChart');
				var blackbookSummaryChart  = new Chart($blackbookSummaryChart, {
					type   : 'pie',
					data   : {
						labels  : data.labels,
						datasets: [
							{
								backgroundColor: ["#488f31", "#63b179", "#aed987", "#ffff9d", "#fcc267", "#ef8250", "#de425b", "#A09CD8"],
								data           : data.blackbookSummary,							
							}
						]
					},
					options: {
						legend:{
							display: true,
							position: 'right'
						}					
					}					
				});
			}
		});
	});

	$("#buttonAddBlackbookScoringItem").on("click", function(e){
		e.preventDefault();
		$("#editBlackbookScoringScoreModal form").attr("action", "");
		$("#blackbookScoringAddLabel").html("Add Blackbook Item Scoring");
		$("#blackbookScoringAddId").val("");
		$("#blackbookScoringAddType").val("")
		$("#blackbookScoringAddLevelLow").prop("checked", false);
		$("#blackbookScoringAddLevelMedium").prop("checked", false);
		$("#blackbookScoringAddLevelHigh").prop("checked", false);
		$("#blackbookScoringAddBahasaIsactiveDiv").hide();
		$("#blackbookScoringAddDelete").hide();
	});

	$(".container-fluid").on("click", ".buttonEditBlackbookScoringSingle", function(){
		$("#editBlackbookScoringScoreModal form").attr("action", baseUrl + "blackbook/updatesinglerowscoring");
		$("#blackbookScoringAddLabel").html("Edit Blackbook Item Score");
		$("#blackbookScoringAddBahasaIsactiveDiv").show();
		$("#blackbookScoringAddDelete").show();
		var id = this.dataset.id;
		console.log(id);
		$.ajax({
			url : baseUrl + "blackbook/singlescoring",
			data : {id : id},
			method : "post",
			dataType : "json",
			success : function(data) {
				// console.table(data);
				$("#blackbookScoringAddId").val(data.id);
				$("#blackbookScoringAddType").val(data.type);
				$("#blackbookScoringAddBahasa").val(data.bahasa);
				if (data.level == 'low') {
					$("#blackbookScoringAddLevelLow").prop("checked", true);
				} else if (data.level == 'medium') {
					$("#blackbookScoringAddLevelMedium").prop("checked", true);
				} else {
					$("#blackbookScoringAddLevelHigh").prop("checked", true);
				}
				if (data.is_active == 1) {
					$("#blackbookScoringAddBahasaIsactiveTrue").prop("checked", true);
				} else {
					$("#blackbookScoringAddBahasaIsactiveFalse").prop("checked", true);
				}
			}
		});
	});

	$("#blackbookScoringAddDelete").on("click", function(){
		var id = $("#blackbookScoringAddId").val();
		var link = baseUrl + "blackbook/deletescoringsingle/" + id;
		SwalConfirm('Sure to Delete?', 'After deleted data cannot being recovered!', link, "Delete it", "warning");
	});

	// Repeat Question
	$("#repeatQuestionkAddButton").on("click", function(){
		$("#repeatQuestionAddLabel").html("Add new agent's Repeat Question");
		$("#repeatQuestionAddId").val("");
		$("#repeatQuestionAddAgent").val("");
		$("#repeatQuestionAddDate").val("");
		$("#repeatQuestionAddCategory").val("");
		$("#repeatQuestionAddDetail").val("");
		$("#repeatQuestionAddRemark").val("");
		$("#repeatQuestionAddReset").show();	
		$("#repeatQuestionAddSubmit").html("Save");
	});

	$(".container-fluid").on("click", ".buttonRepeatQuestionEdit", function(e){
		e.preventDefault();
		$("#repeatQuestionAddLabel").html("Edit Repeat Question data");
		$("#repeatQuestionAddReset").hide();	
		$("#repeatQuestionAddSubmit").html("Update");
		$("#repeatQuestionkAdd .modal-dialog .modal-content form").attr("action", baseUrl + "blackbook/editrepeatquestion");
		const id = this.dataset.id;
		$.ajax({
			url : baseUrl + "blackbook/repeatquestionById/",
			method: "post",
			data : {
				id : id
			},
			dataType : "json",
			success : function(data) {
				// console.log(data);
				$("#repeatQuestionAddId").val(data.id);
				$("#repeatQuestionAddAgent").val(data.agent);
				$("#repeatQuestionAddDate").val(data.date);
				$("#repeatQuestionAddCategory").val(data.category);
				$("#repeatQuestionAddDetail").val(data.detail);
				$("#repeatQuestionAddRemark").val(data.remark);
			}
		})
		// SwalInfo('We are sorry','This function still not available yet','error');
	});

	$(".classFunctionNotAvailableYet").on("click", function(){
		Swal.fire({
			title: 'Unvailable yet!',
			html: '<p>We are sorry, this function still on development progress.</p>',
			icon: 'info',
			confirmButtonColor: "#007BFF",
			confirmButtonText: '<i class="fa fa-check"></i> OK'
		});
	});

	// edit cashless info
	$("#buttonCashlessInfoMonitoringAdd").on("click", function(){
		$("#cashlessInfoMonitoringAddLabel").html("Add Cashless Info Monitoring");
		// $("#cashlessInfoMonitoringDate").val("");
		// $("#cashlessInfoMonitoringAgent").val("");
		// $("#cashlessInfoMonitoringSource").val("");
		// $("#cashlessInfoMonitoringCustomerData").val("");
		// $("#cashlessInfoMonitoringAgentDone").val(0);
		$("#cashlessInfoMonitoringSubmit").html('<i class="fas fa-save"></i> Save');
	});

	// $(".container-fluid").on("click", ".buttoncashlessInfoMonitoringEdit", function(e){
	// 	// e.preventDefault();
	// 	// var id = this.dataset.id;
	// 	// SwalInfo('Unavailable', 'This function temporary unavailable', 'error');
	// 	// $.ajax({
	// 	// 	url : baseUrl + 'blackbook/agentCashlessMonitoringById',
	// 	// 	dataType : "json",
	// 	// 	data : {
	// 	// 		id : id
	// 	// 	}, 
	// 	// 	method : "post",
	// 	// 	success : function(data) {
	// 	// 		// console.table(data);
	// 	// 		$("#cashlessInfoMonitoringAddLabel").html("Edit Cashless Info Monitoring data");
	// 	// 		$("#cashlessInfoMonitoringId").val(data.id);
	// 	// 		$("#cashlessInfoMonitoringDate").val(data.date);
	// 	// 		$("#cashlessInfoMonitoringAgent").val(data.agent);
	// 	// 		$("#cashlessInfoMonitoringSource").val(data.source);
	// 	// 		$("#cashlessInfoMonitoringCustomerData").val(data.customer_data);
	// 	// 		$("#cashlessInfoMonitoringAgentDone").val(data.done_by_agent);
	// 	// 		$("#cashlessInfoMonitoringSubmit").html('<i class="fas fa-check"></i> Update');
	// 	// 	}
	// 	// });
	// });

	$(".container-fluid").on("click", ".buttoncashlessInfoMonitoringDelete", function(e){
		const id = this.dataset.id;
		var title = "Are you sure to delete?";
		var text = "You won't be able to revert this data";
		var link = baseUrl + 'blackbook/deleteAgentCashlessMonitoring/' + id;
		SwalConfirm(title, text, link, "Delete it", "warning");
	});

	// ----------------------------------------------------------------------

	// CS INDEX
	$("#tableSurveyData").DataTable();

	$("#tableManageCSindexData").DataTable({

	});
	
	$(".container-fluid").on("click", "#buttonSelectSurveyPeriod", function () {
		ajaxSelectSurveyPeriod();
	});

	$(".container-fluid").on("click", ".buttonActionSurvey", function () {
		$("#actionSurveyLabel").html("CS Index Survey");
		$(".modal-body form").attr("action", baseUrl + "csindex/index");
		$("#actionSurveySubmit").html("Submit");
		$("#actionSurveySubmitDelete").hide();

		var id = this.dataset.id;
		$.ajax({
			url: baseUrl + "csindex/getSurveyById",
			method: "post",
			dataType: "json",
			data: { id: id },
			success: function (data) {
				// console.log(id);
				var period = toPeriod(data.period);
				$("#actionSurveyLabel").html(
					'CS Index Survey - <span class="bg-danger"> ' + period + " 	</span>"
				);
				$("#surveyId").val(data.id);
				$("#doSurveyCallDateTime").val(data.data_datetime);
				$("#doSurveyAgent").val(data.agent);
				$("#doSurveyModel").val(data.data_model);
				$("#doSurveyCustomerName").val(data.customer_name);
				$("#doSurveyCustomerPhone").val(data.customer_phone);
				$("#doSurveyCustomerCity").val(data.customer_city);
				$("#doSurveyIDetail").val(data.i_detail);
				$("#doSurveyActionDetail").val(data.action_detail);
				$("#actionSurvey form input[name=doSurveyQ1]").prop("checked", false);
				$("#actionSurvey form input[name=doSurveyQ2]").prop("checked", false);
			},
		});
	});

	$(".container-fluid").on("click", ".buttonViewSurvey", function () {
		$("#actionSurveyLabel").html("CS Index Survey");
		$(".modal-body form").attr("action", baseUrl + "csindex/index");
		$("#actionSurveySubmit").html("Update");
		$("#actionSurveySubmitDelete").show();

		var id = this.dataset.id;
		$.ajax({
			url: baseUrl + "csindex/getSurveyById",
			method: "post",
			dataType: "json",
			data: { id: id },
			success: function (data) {
				var period = toPeriod(data.period);
				$("#actionSurveyLabel").html(
					'CS Index Survey - <span class="bg-danger	"> ' + period + " 	</span>"
				);
				$("#surveyId").val(data.id);
				$("#doSurveyCallDateTime").val(data.data_datetime);
				$("#doSurveyAgent").val(data.agent);
				$("#doSurveyModel").val(data.data_model);
				$("#doSurveyCustomerName").val(data.customer_name);
				$("#doSurveyCustomerPhone").val(data.customer_phone);
				$("#doSurveyCustomerCity").val(data.customer_city);
				$("#doSurveyIDetail").val(data.i_detail);
				$("#doSurveyActionDetail").val(data.action_detail);
				$(
					"#actionSurvey form input[name=doSurveyQ1][value=" +
						data.questioner_1 +
						"]"
				).prop("checked", true);
				$(
					"#actionSurvey form input[name=doSurveyQ2][value=" +
						data.questioner_2 +
						"]"
				).prop("checked", true);
			},
		});
	});

	$(".container-fluid").on("click", ".buttonActionSurveyDelete", function (e) {
		e.preventDefault();
		var title = "Are you sure to delete?";
		var text = "You won't be able to revert this Survey data";
		var link = $(this).attr("href");
		SwalConfirm(title, text, link, "Yes, delete it", "warning");
	});

	$("#actionSurveySubmitDelete").on("click", function () {
		// console.log($("#surveyId").val());
		$("#actionSurvey form").attr(
			"action",
			baseUrl + "csindex/deleteSurveyById"
		);
		Swal.fire({
			title: "Delete Survey Result",
			text: "Are you sure to delete this data?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Delete",
		}).then((result) => {
			if (result.value) {
				$(this).attr("type", "submit");
				$("#actionSurvey .modal-body form").submit();
			}
		});
	});

	$("#resultAgent").on("change", function () {
		getCsindexResultByAgentByPeriod();
	});

	$("#resultPeriodStart").on("change", function () {
		getCsindexResultByAgentByPeriod();
	});

	$("#resultPeriodEnd").on("change", function () {
		getCsindexResultByAgentByPeriod();
	});

	$("#buttonSelectCsindexResultByAgent").on("click", function () {
		getCsindexResultByAgentByPeriod();
	});

	// Submit Survey
	$("#actionSurveySubmit").on("click", function (e) {
		e.preventDefault();
		Swal.fire({
			title: "Survey Result",
			text: "Are you sure to save this result?",
			icon: "question",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Submit",
		}).then((result) => {
			if (result.value) {
				$("#actionSurvey .modal-body form").submit();
			}
		});
	});

	// Select Detail Result by Period
	$("#buttonSelectDetailResultPeriod").on("click", function () {
		const period = $("#selectDetailResultPeriod").val();
		$.ajax({
			url: baseUrl + "csindex/getResultDetailByPeriod",
			method: "post",
			dataType: "json",
			data: { period: period },
			success: function (data) {
				$("#csindexDetailResultQ1 tbody").html("");
				$("#csindexDetailResultQ2 tbody").html("");
				$("#csindexDetailResultAll tbody").html("");
				for (let i = 0; i < data.length; i++) {
					$("#csindexDetailResultQ1 tbody").append(
						"<tr><td>" +
							data[i].agent +
							'</td><td class="text-center">' +
							data[i].q1_3 +
							'</td><td class="text-center">' +
							data[i].q1_2 +
							'</td><td class="text-center">' +
							data[i].q1_1 +
							'</td><td class="text-center">' +
							data[i].q1__1 +
							'</td><td class="text-center">' +
							data[i].q1__2 +
							'</td><td class="text-center">' +
							data[i].q1_point +
							"</td></tr>"
					);
					$("#csindexDetailResultQ2 tbody").append(
						"<tr><td>" +
							data[i].agent +
							'</td><td class="text-center">' +
							data[i].q2_3 +
							'</td><td class="text-center">' +
							data[i].q2_2 +
							'</td><td class="text-center">' +
							data[i].q2_1 +
							'</td><td class="text-center">' +
							data[i].q2__1 +
							'</td><td class="text-center">' +
							data[i].q2__2 +
							'</td><td class="text-center">' +
							data[i].q2_point +
							"</td></tr>"
					);
					$("#csindexDetailResultAll tbody").append(
						'<tr><td class="text-center">' +
							data[i].qty_agent +
							'</td><td class="text-center text-bold">' +
							data[i].total_point +
							'</td><td class="text-center text-bold">' +
							(parseFloat(data[i].cs_ratio)*100).toFixed(1) +'%'+
							"</td></tr>"
					);
				}
			},
		});
		$.ajax({
			url: baseUrl + "csindex/getCsareaResultByPeriod",
			method: "post",
			dataType: "json",
			data: { period: period },
			success: function (data) {
				const tableRow = $("#tableCsindexCsareaResult tbody tr");
				console.log(tableRow);
				tableRow[0].children[1].innerHTML = data.q1_3;
				tableRow[0].children[2].innerHTML = fixPercentage(data.p_q1_3, 1) + "%";
				tableRow[0].children[3].innerHTML = data.q2_3;
				tableRow[0].children[4].innerHTML = fixPercentage(data.p_q2_3, 1) + "%";
				tableRow[1].children[1].innerHTML = data.q1_2;
				tableRow[1].children[2].innerHTML = fixPercentage(data.p_q1_2, 1) + "%";
				tableRow[1].children[3].innerHTML = data.q2_2;
				tableRow[1].children[4].innerHTML = fixPercentage(data.p_q2_2, 1) + "%";
				tableRow[2].children[1].innerHTML = data.q1_1;
				tableRow[2].children[2].innerHTML = fixPercentage(data.p_q1_1, 1) + "%";
				tableRow[2].children[3].innerHTML = data.q2_1;
				tableRow[2].children[4].innerHTML = fixPercentage(data.p_q2_1, 1) + "%";
				tableRow[3].children[1].innerHTML = data.q1__1;
				tableRow[3].children[2].innerHTML = fixPercentage(data.p_q1__1, 1) + "%";
				tableRow[3].children[3].innerHTML = data.q2__1;
				tableRow[3].children[4].innerHTML = fixPercentage(data.p_q2__1, 1) + "%";
				tableRow[4].children[1].innerHTML = data.q1__2;
				tableRow[4].children[2].innerHTML = fixPercentage(data.p_q1__2, 1) + "%";
				tableRow[4].children[3].innerHTML = data.q2__2;
				tableRow[4].children[4].innerHTML = fixPercentage(data.p_q2__2, 1) + "%";
				tableRow[5].children[1].innerHTML = data.q1_qty;
				tableRow[5].children[2].innerHTML = fixPercentage(data.p_q1, 1) + "%";
				tableRow[5].children[3].innerHTML = data.q2_qty;
				tableRow[5].children[4].innerHTML = fixPercentage(data.p_q2, 1) + "%";
				tableRow[6].children[1].innerHTML = data.q1_csarea;
				tableRow[6].children[2].innerHTML = fixPercentage(data.p_q1_csarea, 1) + "%";
				tableRow[6].children[3].innerHTML = data.q2_csarea;
				tableRow[6].children[4].innerHTML = fixPercentage(data.p_q2_csarea, 1) + "%";
				tableRow[7].children[1].innerHTML = fixPercentage(data.q1_result, 1) + "%";
				tableRow[7].children[2].innerHTML = fixPercentage(data.q2_result, 1) + "%";
				tableRow[8].children[0].innerHTML = fixPercentage(data.total_result, 1) + "%";
			},
		});
	});

	// CS Index survey to Excel
	$("#buttonToExcelSelectedSurveyPeriod").on("click", function (e) {
		e.preventDefault();
		const csindexPeriod = $("#selectSurveyPeriod").val();
		window.location.href =
			baseUrl + "csindex/downloadCsIndexByPeriod/" + csindexPeriod;
	});

	$(".container-fluid").on("click", ".btnDeleteSurveyData", function(){
		var period = this.dataset.period;
		var title = "Please Confirm!";
		var text = "Sure to delete CS Index Result?!";
		var icon = "warning";
		var confirmText = "Delete";
		var link = baseUrl + "csindex/deleteSurveyByPeriod/" + period;		
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
	});

	$("#buttonCsindexTransition").on("click", function(){
		var startPeriod = $("#csindexTransitionStart").val();
		var endPeriod = $("#csindexTransitionEnd").val();
		$.ajax({
			url: baseUrl + "csindex/getTransition",
			data: {
				startPeriod: startPeriod,
				endPeriod: endPeriod
			},
			method: "post",
			dataType: "json",
			success: function(data){
				newData = JSON.parse(JSON.stringify(data));
				var cols = Object.keys(data[0]);
				$("#tableCsindexTransition tbody").html("");
				var tableHead = '<tr class="bg-light"><th>User ID</th>';
				for (let x = 1; x < cols.length; x++) {
					tableHead += "<th>" + toPeriodMin(cols[x]) + "</th>";
				}
				tableHead += "<th><target (times)</th></tr>";
				$("#tableCsindexTransition").html(tableHead);
				var allStringRow = "";
				var tableRowValue = document.createElement("tbody");
				for (let y = 0; y < newData.length; y++) {
					var dataRow = document.createElement("tr");
					var stringRow = "";
					var value = "";
					var ave = 0;
					var ttl = 0;
					$.each(data[y], function (i, d) {
						if (Number.parseFloat(d)) {
							value = '<td class="text-center">' + (parseFloat(d) * 100).toFixed(1) +"%" + "</td>";
							ttl += parseFloat(d);
						} else if (d == null) {
							value = "<td> - </td>";
						} else {
							value = "<td>" + d + "</td>";
						}
						ave = ttl / (cols.length - 1);
						stringRow += value;
					});
					$(dataRow).append(stringRow);
					// var addAveTd = document.createElement("td");
					// var addAveTdValue = document.createTextNode(ave.toFixed(1));
					// addAveTd.appendChild(addAveTdValue);
					// addAveTd.className = "text-center text-indigo";
					// $(dataRow).append(addAveTd);
					$(tableRowValue).append(dataRow);
				}
				$("#tableCsindexTransition").append(tableRowValue);
			}
		});
	});	
	
	var startPeriod = $("#csindexSummaryStartPeriod").val();
	var endPeriod = $("#csindexSummaryEndPeriod").val();
	$.ajax({
		url: baseUrl + "csindex/getSummary/" + startPeriod + "/" + endPeriod,			
		dataType: "json",
		success: function(data){
			var $csindexSummaryChart = $('#csindexSummaryChart');
			var csindexSummaryChart  = new Chart($csindexSummaryChart, {
				type   : 'bar',
				data   : {
					labels  : data.labels,
					datasets: [
						{
							backgroundColor: 'rgba(40, 167, 69, 1)',
							borderColor    : '#dc3545',
							data           : data.csindex,							
						}
					]
				},
				options: {
					legend:{
						display: false
					},												
					scales : {
						yAxes:[{
							ticks:{
								suggestedMin: 84,
							},							
						}],				
						xAxes: [{
							display: true,
							gridLines: {
								display      : true,
								lineWidth    : '4px',
								color        : 'rgba(0, 0, 0, .2)',
								zeroLineColor: 'transparent'
							}
						}]						
					}
				}
			});
		}
	});

	function ajaxSelectSurveyPeriod() {
		const csindexPeriod = $("#selectSurveyPeriod").val();
		$.ajax({
			url: baseUrl + "csindex/surveyDataByPeriod",
			method: "post",
			data: {
				period: csindexPeriod,
			},
			dataType: "json",
			success: function (data) {
				$("#tableSurveyData tbody").html("");

				var tbodyValue = "";
				for (let i = 0; i < data.length; i++) {
					if (data[i].is_done == 0) {
						var buttonActionSurvey =
							'<button class="btn buttonActionSurvey" data-toggle="modal" data-target="#actionSurvey" data-id=' +
							data[i].id +
							'><span class="lnr lnr-pencil"></span></></button>';
						var buttonActionSurveyDelete =
							'<a class="buttonActionSurveyDelete" href=' +
							baseUrl +
							"csindex/deletesurveybyid/" +
							data[i].id +
							' title="Delete data"><span class="lnr lnr-trash text-danger"></span></a>';
						const stringRow =
							'<tr><td class="text-center">' +
							(parseInt(i) + 1) +
							"</td><td>" +
							data[i].agent +
							"</td><td>" +
							data[i].customer_name +
							"</td><td>" +
							data[i].customer_phone +
							"</td><td>" +
							data[i].customer_city +
							'</td><td class="text-center">' +
							data[i].questioner_1 +
							'</td><td class="text-center">' +
							data[i].questioner_2 +
							'</td><td class="text-center">' +
							"-" +
							'</td><td class="text-center">' +
							data[i].survey_by +
							'</td><td class="text-center">' +
							buttonActionSurvey +
							buttonActionSurveyDelete +
							"</td></tr>";
						tbodyValue += stringRow;
					} else {
						var buttonViewSurvey =
							'<button class="btn buttonViewSurvey text-primary" data-toggle="modal" data-target="#actionSurvey" data-id=' +
							data[i].id +
							'><span class="lnr lnr-chevron-right-circle"></span></button>';
						const stringRow =
							'<tr class="bg-light"><td class="text-center text-primary">' +
							(parseInt(i) + 1) +
							'</td><td class="text-primary">' +
							data[i].agent +
							'</td><td class="text-primary">' +
							data[i].customer_name +
							'</td><td class="text-primary">' +
							data[i].customer_phone +
							'</td><td class="text-primary">' +
							data[i].customer_city +
							'</td><td class="text-center text-primary">' +
							data[i].questioner_1 +
							'</td><td class="text-center text-primary">' +
							data[i].questioner_2 +
							'</td><td class="text-center text-primary">' +
							data[i].survey_datetime +
							'</td><td class="text-center text-primary">' +
							data[i].survey_by +
							'</td><td class="text-center text-primary">' +
							buttonViewSurvey +
							"</td></tr>";
						tbodyValue += stringRow;
					}
				}
				$("#tableSurveyData tbody").append(tbodyValue);
			},
		});
	}

	function getCsindexResultByAgentByPeriod() {
		var agent = $("#resultAgent").val();
		var startPeriod = $("#resultPeriodStart").val();
		var endPeriod = $("#resultPeriodEnd").val();
		var tableResult = $("#tableCsindexResultByAgent tbody");

		$.ajax({
			url: baseUrl + "csindex/resultByAgentById",
			method: "post",
			data: {
				agent: agent,
				startPeriod: startPeriod,
				endPeriod: endPeriod,
			},
			dataType: "json",
			success: function (data) {
				// console.log(data);
				tableResult.html("");
				for (let i = 0; i < data.length; i++) {
					tableResult.append(
						'<tr><td class="text-center">' +
							parseInt(i + 1) +
							'</td><td class="text-center">' +
							toPeriod(data[i].period) +
							'</td><td class="text-center">' +
							data[i].qty +
							'</td><td class="text-center">' +
							data[i].questioner_1 +
							'</td><td class="text-center">' +
							data[i].questioner_2 +
							'</td><td class="text-center">' +
							data[i].total_point +
							'</td><td class="text-center">' +
							(parseFloat(data[i].cs_ratio)*100).toFixed(1) +'%' + 
							"</td></tr>"
					);
				}
			},
		});
	}

	// ----------------------------------------------------------------------

	//ELEARNING
	//Add Elearning Category
	$(".buttonAdd").on("click", function () {
		$("#categoryModalLabel").html("Add new Elearning");
		$("#categoryPeriod").val("");
		$("#categoryName").val("");
		$("#startdate").val("");
		$("#enddate").val("");
		$("#status").val("");
	});

	// delete Elearning category
	$(".buttonDeleteElearning").on("click", function (e) {
		e.preventDefault();
		const title = "Sure to Delete?";
		const text = "You won't be able to revert this Elearning";
		const link = $(this).attr("href");
		SwalConfirm(title, text, link, "Delete", "warning");

	});

	// edit elearning category
	$(".buttonEdit").on("click", function () {
		$("#categoryModalLabel").html("Edit Elearning category");
		$(".modal-body form").attr("action", baseUrl + "elearning/edit_elearning");

		const id = $(this).data("id");

		$.ajax({
			url: baseUrl + "elearning/get_edit",
			data: { id: id },
			method: "post",
			dataType: "json",
			success: function (data) {
				$("#categoryId").val(data.id);
				$("#categoryPeriod").val(data.period);
				$("#categoryName").val(data.name);
				$("#startdate").val(data.startdate);
				$("#enddate").val(data.enddate);
				$("#questionQty").val(data.question_qty);
				$("#testDuration").val(data.test_duration);
				$("#passingScore").val(data.passing_score);
				$("#posttestAttemp").val(data.posttest_attemp);
				$("#questionPretest").val(data.pretest);
				// $("#material").val(data.material);
			},
		});
	});

	//Edit Questionaire
	$("#btnAddQuestionaire").on("click", function () {
		$("#questionaireModalLabel").html("Add new questionaire");
		$("#categoryPeriod").val("");
		$("#categoryName").val("");
		$("#startdate").val("");
		$("#enddate").val("");
		$("#status").val("");
	});

	// Edit Questioner
	$(".btnEditQuestionaire").on("click", function () {
		$("#questionaireModalLabel").html("Edit questionaire");
		$("#formElearning_id").parent().parent().hide();
		$(".modal-body form").attr(
			"action",
			baseUrl + "elearning/edit_questionaire"
		);
		const qid = $(this).data("qid");

		$.ajax({
			url: baseUrl + "elearning/get_questionaire",
			data: { qid: qid },
			method: "post",
			dataType: "json",
			success: function (data) {
				
				const formQuestionairePicture = $("#formQuestionairePicture");
				$("#selectQuestionaireCategory").val(data.category);
				$("#formQuestionPeriod").val(data.period);
				$("#formQid").val(data.id);
				$("#formQuestion").val(data.question);
				$("#formOptionA").val(data.option_a);
				$("#formOptionB").val(data.option_b);
				$("#formOptionC").val(data.option_c);
				$("#formOptionD").val(data.option_d);
				$("#formOptionE").val(data.option_e);
				$("#formCorrect_key").val(data.correct_key).prop("selected", true);
				// console.log((formQuestionairePicture.value = data.picture_link));
			},
		});
	});

	// Delete questioner
	$(".buttonDeleteElearningQuestionaire").on("click", function(e){
		e.preventDefault();
		const questionaire_id = this.dataset.qid;
		const link = this.href;
		const title = 'Sure delete question?';
		const text = "You won't be able to revert it!";
		const icon = "warning";
		const confirmText = "Delete";
		SwalConfirm(title, text, link, confirmText, icon);
	})

	$("#colSelectEl1").on("change", function () {
		id = $("#colSelectEl option:selected").val();
		$.ajax({
			url: baseUrl + "elearning/getAssignedByElearning",
			data: {
				elearning_id: id,
			},
			method: "post",
			dataType: "json",
			success: function (data) {
				$("div .card-body table tbody").html("");
				for (i = 0; i < data.length; i++) {
					if (data[i].is_done == 0) {
						isDone = "-";
						xScore = "-";
						unassignBtn =
							'<button class="btn badge badge-danger unassignedUser" data-userid="' +
							data[i].user_id +
							'" data-elearningid="' +
							data[i].elearning_id +
							'"><i class="fas fa-trash-alt text-white"></i></button>';
					} else {
						isDone = '<i class="fas fa-check-circle text-primary"></i>';
						xScore = data[i].score;
						unassignBtn =
							'<button class="btn badge badge-danger unassignedUser" data-userid="' +
							data[i].user_id +
							'" data-elearningid="' +
							data[i].elearning_id +
							'"><i class="fas fa-trash-alt text-white"></i></button>' +
							' <button class="btn badge badge-warning resetAssignment" data-userid="' +
							data[i].user_id +
							'" data-elearningid="' +
							data[i].elearning_id +
							'"><i class="fas fa-undo-alt"></i></button>';
					}

					$("div .card-body table tbody").append(
						'<tr><td class="text-center">' +
							parseInt(i + 1) +
							"</td><td>" +
							data[i].user_id +
							"</td><td>" +
							data[i].department +
							'</td><td class="text-center h4">' +
							isDone +
							'</td><td class="text-center">' +
							xScore +
							'</td><td class="text-center">' +
							toDatetime(data[i].exam_date) +
							"</td><td>" +
							unassignBtn +
							"</td></tr>"
					);
				}
			},
		});
	});

	$("#btnSelectActiveEl").on("click", function () {
		$("#colSelectEl").attr("method", "post");
		$("#colSelectEl").attr("action", baseUrl + "elearning/assignment");
	});

	$("window").ready(function () {
		$("#colSelectEl").attr("method", "post");
		$("#colSelectEl").attr("action", baseUrl + "elearning/assignment");
	});

	$("#assignUserBtn").on("click", function () {
		const usersAssigned = $(".assignUnassignUserCheckbox:checked");
		const users = [];
		var ddd;
		for (let i = 0; i < usersAssigned.length; i++) {
			const user_id = usersAssigned[i].dataset.userid;
			//const elearning_id = $("#selectElearningCategory").find(":selected").val();
			const elearning_id = usersAssigned[i].dataset.elearningid;
			var pretest;
			if(this.dataset.pretest = 1) {
				pretest = 0;
			} else {
				pretest = 1;
			}
			users.push({
				elearning_id: elearning_id,
				user_id: user_id,
				pretest_done: pretest
			});
			console.log(elearning_id);
		}
		$.ajax({
			url: baseUrl + "elearning/assignUser",
			data: { data: users },
			method: "post",
			success: function (data) {
				window.location.href = baseUrl + "elearning/assignment";
			},
		});
	});

	$(".unassignedUser").on("click", function () {
		const user_id = this.dataset.userid;
		const elearning_id = this.dataset.elearningid;
		console.log(user_id, elearning_id);
		Swal.fire({
			title: "Unassign user",
			text: "Are you sure to unassign this user?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#007BFF",
			cancelButtonColor: "#DC3545",
			confirmButtonText: "Unassign",
		}).then((result) => {
			if (result.value) {
				window.location.href = baseUrl + "elearning/unassignUser/" + elearning_id + "/" + user_id;				
			}
		});
	});

	// reset user assignment
	$(".resetPretest").on("click", function () {
		var user_id = this.dataset.userid;
		var elearning_id = this.dataset.elearningid;
		console.log(user_id, elearning_id);
		Swal.fire({
			title: "Sure to reset",
			text: "You will have to do exam after resetting",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#007BFF",
			cancelButtonColor: "#DC3545",
			confirmButtonText: "Reset",
		}).then((result) => {
			if (result.value) {
				window.location.href = baseUrl + "elearning/resetPretest/" + elearning_id + "/" + user_id;				
			}
		});
	});

	$(".resetPosttest").on("click", function () {
		var user_id = this.dataset.userid;
		var elearning_id = this.dataset.elearningid;
		console.log(user_id, elearning_id);
		Swal.fire({
			title: "Sure to reset Post Test?",
			text: "You will have to do exam after resetting",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#007BFF",
			cancelButtonColor: "#DC3545",
			confirmButtonText: "Reset",
		}).then((result) => {
			if (result.value) {
				window.location.href = baseUrl + "elearning/resetPosttest/" + elearning_id + "/" + user_id;				
			}
		});
	});

	// disabledResetPosttestButton
	$(".resetPostTestDisabled").on("click", function(){
		var attm = this.dataset.maxattemp;
		Swal.fire({
				title: "Forbidden!",
				text: "Pelaksanaan Post Test maksimal: " + attm + " kali",
				icon: "error",
			});
		console.log(attm);
		if ($(this).hasClass("disabled")) {
			Swal.fire({
				title: "Forbidden!",
				text: "Pelaksanaan Post Test maksimal: " + attm + " kali",
				icon: "error",
			});
		}
	});

	// Questioner Assignment
	$("#tableElearningAssignedQuestionaire").DataTable();
	$("#tableElearningUnassignedQuestionaire").DataTable({
		"autoWidth": false,
		"pageLength": 100
	});

	// Submit Questioner Assignment
	$("#submitAssignQuestionaire").on("click", function () {
		const questionaireAssigned = $(".assignUnassignedQuestionaire:checked");
		const questions = [];
		for (let i = 0; i < questionaireAssigned.length; i++) {
			const questionaire_id = questionaireAssigned[i].dataset.questionaireid;
			const elearning_id = $("#questionaireAssignmentSelectElearningCategory").val();
			questions.push({
				elearning_id: elearning_id,
				questionaire_id: questionaire_id,
			});
		}
		console.log(questions);
		$.ajax({
			url: baseUrl + "elearning/assignUnassignedQuestionaire",
			data: { data: questions },
			method: "post",
			success: function (data) {
				window.location.href = baseUrl + "elearning/assignquestionaire";
			},
		});
	});

	// Unassign Questionaire
	$(".container-fluid").on("click", ".buttonUnassignQuestionaire", function(){
		var assignid = this.dataset.assignid;
		console.log(assignid)
		var title = 'Sure to Remove This Questioner?';
		var text = 'You can re-add the questioner later';
		var link = baseUrl + 'elearning/unassignQuestionaire/' + assignid;
		SwalConfirm(title, text, link, 'Remove', 'warning');		
	});

	// Unassign questions Group
	$(".container-fluid").on("click", ".buttonMarkUnassignQuestionaire", function(){
		var n = $(".container-fluid .buttonMarkUnassignQuestionaire:checked").length;
		if (n > 0) {
			var text = 'Unassign selected ' + n + ' questions';
			$("#buttonUnassignSelectedSelected").html(text);
			$("#buttonUnassignSelectedSelected").show(100);
		} else {
			$("#buttonUnassignSelectedSelected").hide(100);
		}
	});

	$("#buttonSelectAllUnassignQuestionaire").on("click", function(){
		var tbl = $("#tableListProductivityForEdit");

		if ($(this).is(":checked")) {
			$(".buttonMarkUnassignQuestionaire").prop("checked", true);
			$("#buttonUnassignSelectedSelected").html('Unassign all questions');
			$("#buttonUnassignSelectedSelected").show(100);
		} else {
			$(".buttonMarkUnassignQuestionaire").prop("checked", false);
			$("#buttonUnassignSelectedSelected").hide(100);
		}
	})

	// delete
	$("#buttonUnassignSelectedSelected").on("click", function(){
		var nums = $(".container-fluid .buttonMarkUnassignQuestionaire:checked").toArray();
		var lists = [];
		for (let i = 0; i < nums.length; i++) {
			lists.push(nums[i].value);
		}
		$.ajax({
			url : baseUrl + 'elearning/unassignQuestionaireGroups',
			data : {lists : lists},
			method : "post",
			success : function() {
				var title = "Confirm unassigned " + nums.length + " ?";
				var text = "Questioners will be unassigned";
				SwalConfirmReload(title, text, "OK", "info");
			}
		});
	});

	// Export questioner assigned to Excel
	$("#buttonQuestionsToExcel").on("click", function(){
		var id = $("#questionaireAssignmentSelectElearningCategory").val();
		window.location.href = baseUrl + "elearning/quesionaireAssignmentToExcel/" + id;
	});

	// confirm to Pretest
	$(".container-fluid").on("click", ".gotoPretest", function(e){
		e.preventDefault();
		const elearning_id = this.dataset.elearning_id;
		const passing_score = this.dataset.passing_score;
		const test_duration = this.dataset.test_duration;
		Swal.fire({
			title: "Duration: " + test_duration + " minutes",
			text: "You will preform Pretest now",
			icon: "warning",
			showCancelButton: true,
			cancelButtonText: "I'll did later",
			confirmButtonColor: "#007BFF",
			cancelButtonColor: "#DC3545",
			confirmButtonText: "Yes, got it",
		}).then((result) => {
			if (result.value) {
				window.location.href = baseUrl + "elearning/pretest/" + elearning_id;
			}
		});
	});

	// confirm to perform Post Text
	$(".container-fluid").on("click", ".gotoPosttest", function(e){
		e.preventDefault();
		const elearning_id = this.dataset.elearning_id;
		const passing_score = this.dataset.passing_score;
		const test_duration = this.dataset.test_duration;
		Swal.fire({
			title: "Minimum score to pass: " + passing_score + ",\nDuration: " + test_duration + " min",
			text: "Sure to perform examination now?",
			icon: "warning",
			showCancelButton: true,
			cancelButtonText: "Go back",
			confirmButtonColor: "#007BFF",
			cancelButtonColor: "#DC3545",
			confirmButtonText: "Perform Exam",
		}).then((result) => {
			if (result.value) {
				window.location.href = baseUrl + "elearning/posttest/" + elearning_id;
			}
		});
	});

	$("#buttonElearningSubmitExam").on("click", function (e) {
		e.preventDefault();
		Swal.fire({
			title: "Sure to submit exam",
			text: "Your answer will be submitted",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#007BFF",
			cancelButtonColor: "#d33",
			confirmButtonText: "Submit",
		}).then((result) => {
			if (result.value) {
				$("#formElearningExam").submit();
			}
		});
	});

	// autoSubmit Exam after timer end
	// var posttest_duration = $("#posttest_duration").val() * 60;
	// console.log(posttest_duration)
	// setTimeout(function(){
	// 	alert('Hai')
	// }, 5000);

	$("#selectResultId").on("change", function () {
		elearningResultByAgentByPeriod();		
	});

	$("#selectResultUserId").on("change", function () {
		elearningResultByAgentByPeriod();
	});

	$("#selectResultPrepost").on("change", function () {
		elearningResultByAgentByPeriod();
	});

	$("#btnSelectSummaryByCategory").on("click", function () {
		id = $("#selectCategorySummary option:selected").val();
		$("#buttonExportToExcel a").attr("href", baseUrl + "elearning/export/" + id);
		$.ajax({
			url: baseUrl + "elearning/getSummaryByCategory",
			data: {
				elearning_id: id,
			},
			method: "post",
			dataType: "json",
			success: function (data) {
				console.log(data);
				$("#jsGrid1 div table tbody").html("");
				for (i = 0; i < data.length; i++) {
					$("#jsGrid1 div table tbody").append(
						"<tr><td>" +
							(i + 1) +							
							"</td><td>" +
							data[i].fullname +
							"</td><td>" +
							data[i].npk +
							"</td><td>" +
							data[i].department +
							'</td><td class="text-center">' +
							toScore(data[i].pretest_score) +
							'</td><td class="text-center">' +
							toScore(data[i].posttest_score) +
							'</td><td class="text-center h5">' +
							isPass(data[i].is_pass) +
							'</td><td>' +
							toDatetime(data[i].posttest_date) +
							"</td></tr>"
					);
				}
			},
		});
	});

	// Export Summary to Excel
	$("#buttonExportToExcel").on("click", function(){
		const elearning_id = $("#selectCategorySummary").val();
		location.href = baseUrl + "elearning/export/" + elearning_id;
	});

	// Elearning summary
	$("#elearningSummaryTableSummary").ready(function () {
		//addAverageCol();
	});

	// Get Summary on Button Click
	$("#btnSelectSummaryResult").on("click", function () {
		const divSummary = $("#elearningSummaryTableSummary table");
		let selectElearningSummaryStart = $("#selectElearningSummaryStart").val();
		let selectElearningSummaryEnd = $("#selectElearningSummaryEnd").val();

		$.ajax({
			url: baseUrl + "elearning/summaryByPeriod",
			data: {
				selectElearningSummaryStart: selectElearningSummaryStart,
				selectElearningSummaryEnd: selectElearningSummaryEnd,
			},
			method: "post",
			dataType: "json",
			success: function (data) {
				newData = JSON.parse(JSON.stringify(data));
				var cols = Object.keys(data[0]);
				// console.log($("#elearningSummaryTableSummary table"));
				$("#elearningSummaryTableSummary table tbody").html("");
				var tableHead = '<tr class="bg-light"><th>User ID</th>';
				for (let x = 1; x < cols.length; x++) {
					tableHead += '<th class="px-3">' + toPeriodMin(cols[x]) + "</th>";
				}
				tableHead += "<th>Average</th></tr>";
				$("#elearningSummaryTableSummary table").html(tableHead);
				var allStringRow = "";
				var tableRowValue = document.createElement("tbody");
				for (let y = 0; y < newData.length; y++) {
					var dataRow = document.createElement("tr");
					var stringRow = "";
					var value = "";
					var ave = 0;
					var ttl = 0;
					$.each(data[y], function (i, d) {
						if (Number.isInteger(parseInt(d))) {
							value = '<td class="text-center" class="px-3">' + parseInt(d) + "</td>";
							ttl += parseInt(d);
						} else {
							value = "<td>" + d + "</td>";
						}
						ave = ttl / (cols.length - 1);
						stringRow += value;
					});
					$(dataRow).append(stringRow);
					var addAveTd = document.createElement("td");
					var addAveTdValue = document.createTextNode(ave.toFixed(1));
					addAveTd.appendChild(addAveTdValue);
					addAveTd.className = "text-center text-indigo";
					$(dataRow).append(addAveTd);
					$(tableRowValue).append(dataRow);
				}
				$("#elearningSummaryTableSummary table").append(tableRowValue);
			},
		});
	});

	$("#tableElearningSummaryByPeriod").DataTable({
		"autoWidth" : false,
		"searching" : false,
		"lengthChange" : false,
		"pageLength" : 100,
		"info" : false,
		"paging" : false
	});

	$("#tableElearningSummaryTableSummary").DataTable({
		"autoWidth" : true,
		"searching" : false,
		"lengthChange" : false,
		"pageLength" : 5,
		"info" : false,
		//"paging" : false
	});

	// mengganti placeholder setelah memilih file pada kolom upload questioner
	$("#uploadQuestionaireFile").on("change", function(){
		var file = this.files[0].name;
		var dflt = $(this).attr("placeholder");
		$("#labelUploadQuestionaireFile").text(file);		
	});

	$("#tableElearningQuestionaire").DataTable();
	$("#tableExamList").DataTable({
		"autoWidth": false
	});
	$("#tableElearningList").DataTable({
		"autoWidth": false
	});

	// Function add Average Column;
	function elearningResultByAgentByPeriod(){
		user_id = $("#selectResultUserId option:selected").val();
		elearning_id = $("#selectResultId option:selected").val();
		prepost = $("#selectResultPrepost").val();
		$.ajax({
			url: baseUrl + "elearning/resultByCategoryBySelectedAgent",
			data: {
				user_id: user_id,
				elearning_id: elearning_id,
				pre_post: prepost,
			},
			method: "post",
			dataType: "json",
			success: function (data) {
				console.log(data);
				$("table .table-sm").html("");
				if (data == null) {
					$("#examScore").html("-");
					$("#examDate").val("-");
					$("table .table-sm").html("");
				} else if (data.score != null) {
					$("#examScore").html(data.score);
					$("#examDate").val(data.date);
					$("table .table-sm").html("");
				} else if (data.length == 0) {
					$("#examScore").html("-");
					$("#examDate").val("-");
					$("table .table-sm").html("");
				} else {					
					if( prepost == 'pretest') {
						$("#examScore").html(data[0].pretest_score);
						$("#examDate").val(toDatetime(data[0].pretest_date));
						$("#examDuration").val(toDuration(data[0].pretest_duration));
					} else {
						$("#examScore").html(data[0].posttest_score);
						$("#examDate").val(toDatetime(data[0].posttest_date));
						$("#examDuration").val(toDuration(data[0].posttest_duration));
					}
					
					for (i = 0; i < data.length; i++) {
						$("table .table-sm").append(
							'<tr><td class="text-center">' +
								(i + 1) +
								"</td><td>" +
								data[i].question +
								// '</td><td class="text-center">' +
								// data[i].answer +
								'</td><td class="text-center h5">' +
								isCorrect(data[i].is_correct) +
								"</td></tr>"
						);
					}
				}
			},
		});
	}

	// Education Material
	$("#tableMaterialEducationProduct").DataTable();

	// set category on material group
	$("#bodyFormAddEducationMaterial").ready(function(){
		$("#addeducationmaterialGroupProduct").on("click", function(){
			$("#addeducationmaterialCategoryParent").html("");
			$("#addeducationmaterialCategoryParent").append('<select class="custom-select" name="addeducationmaterialCategory"><option value="">- select category -</option><option value="Air Conditioner">Air Conditioner</option><option value="Air Purifier">Air Purifier</option><option value="Audio">Audio</option><option value="Refrigerator">Refrigerator</option><option value="SHA">SHA</option><option value="Television">Television</option><option value="Washing Machine">Washing Machine</option><option value="OBP">OBP</option></select>');
		});
		$("#addeducationmaterialGroupNonproduct").on("click", function(){
			$("#addeducationmaterialCategoryParent").html("");
			$("#addeducationmaterialCategoryParent").append('<input type="" class="form-control" id="addeducationmaterialCategory" name="addeducationmaterialCategory">');
		});
	});


	// FUNCTION ELEARNING
	function isPass(data) {
		if (data == 1) {
			return '<i class="fas fa-check-circle text-primary"></i>';
		} else {
			return '<i class="far fa-times-circle text-danger"></i>';
		}
	}

	function isCorrect(data) {
		if (data == 1) {
			return '<i class="far fa-check-circle text-primary"></i>';
		} else {
			return '<i class="far fa-times-circle text-danger"></i>';
		}
	}

	function toScore(data) {
		if (data == 0) {
			return "-";
		} else {
			return data;
		}
	}

	function toDuration(dur) {
		hours = Math.floor(dur / 3600);
		minutes = Math.floor((dur - (hours * 3600)) / 60);
		seconds = dur - (hours * 3600) - (minutes * 60);

		timeString = hours.toString().padStart(2, '0') + ':' + 
		      minutes.toString().padStart(2, '0') + ':' + 
		      seconds.toString().padStart(2, '0');
		return timeString;
	}

	// SKAPE Feedback
	$(".tableBasicDataTable").DataTable();

	$(".container-fluid").on("click", ".buttonElearningSkapefeedbackDelete", function(e){
		e.preventDefault();
		var title = "Are you sure to delete?";
		var text = "You won't be able to revert this SKAPE Feedback";
		var link = $(this).attr("href");
		SwalConfirm(title, text, link, 'Delete', 'warning');
	});

	$(".container-fluid").on("click",".buttonElearningSkapefeedbackEdit", function(e){
		var id = this.dataset.id;
		$.ajax({
			url : baseUrl + 'elearning/feedbackbyid',
			data : {id : id},
			method : "post",
			dataType : "json",
			success : function(data) {
				$("#feedbackResponseId").val(data.id);
				$("#feedbackResponseCategory").val(data.category);
				$("#feedbackResponseLink").val(data.solution_id);
				$("#feedbackResponseTitle").val(data.solution_title);
				$("#feedbackResponseComment").val(data.feedback);
				$("#feedbackResponseRemark").val(data.remark);
				$("#feedbackResponseStatus").prop("checked", statusToChecked(data.status));
			}
		});
	});

	function statusToChecked(stts) {
		if (stts == 1) {
			return true;
		} else {
			return false;
		}
	}

	$("#feedbackResponseStatus").on("click", function(){
		console.log(this);
	})

	// ----------------------------------------------------------------------

	// INVENTORY
	$("#tableInventoryPc").DataTable({
		"autoWidth": false
	});
	$("#tableInventoryMonitor").DataTable({
		"autoWidth": false
	});
	$("#tableInventoryIpphone").DataTable({
		"autoWidth": false
	});
	$("#tableInventoryHeadset").DataTable({
		"autoWidth": false
	});
	$("#tableInventoryOthers").DataTable({
		"autoWidth": false
	});

	$("#tableInventoryPc").on("click", ".buttonDeletePc", function(e){
		e.preventDefault();
		const pc_id = this.dataset.id;
		// console.log(pc_id);
		const title = 'Sure to Delete?';
		const text = 'You will not be able to revert data!';
		const confirmText = 'Delete';
		const icon = 'warning';
		const link = baseUrl + 'inventory/deletepcbyid/' + pc_id;
		SwalConfirm(title, text, link, confirmText, icon);
	});

	$("#tableInventoryPc").on("click", ".buttonEditPC", function(e){
		e.preventDefault();
		const pc_id = this.dataset.id;
		$("#addAssetsPcLabel").html('Edit PC');
		$("#addAssetsPcSubmit").hide();
		$("#addAssetsPcUpdate").show();
		$("#addAssetsPc div div form").attr('action', baseUrl + 'inventory/updatePcById');
		$.ajax({
			url : baseUrl + "inventory/getPcById",
			method : "post",
			data : {
				pc_id : pc_id
			},
			dataType : "json",
			success : function(data){
				console.log(data);
				$("#addAssetsPcId").val(data.pc_id);
				$("#addAssetsPcDeptown").val(data.pc_deptown);
				$("#addAssetsPcBrand").val(data.pc_brand);
				$("#addAssetsPcModel").val(data.pc_model);
				$("#addAssetsPcSn").val(data.pc_sn);
				$("#addAssetsPcSpec").val(data.pc_spec);
				$("#addAssetsPcIp").val(data.pc_ip);
				$("#addAssetsPcRemark").val(data.pc_remark);
				$("#addAssetsPcRecdate").val(data.pc_recdate);
				$("#addAssetsPcStatus").val(data.pc_status);
			}
		});
	});

	$("#buttonAddAsstesPc").on("click",function(){
		$("#addAssetsPcLabel").html('Add PC');
		$("#addAssetsPcSubmit").show();
		$("#addAssetsPcUpdate").hide();
		$("#addAssetsPc form")[0].reset();
	});

	// ----------------------------------------------------------------------

	// OBIDIENCE
	$("#tableObidienceSummaryByAgent").DataTable({
		"autoWidth": false
	});

	$("#addIncomplianceUpdate").hide();
	$("#addIncomplianceDelete").hide();

	// Datatable
	$("#tableObidienceDetail").DataTable({
		"autoWidth": false,
		"lengthMenu": [10, 25, 50, 100, 250, 500, 1000],
	});

	$("#tableObidienceByAgent").DataTable({
		"autoWidth": false
	});

	$("#tableObidienceExchange").DataTable({
		"autoWidth": false,
		"lengthMenu": [ 15, 25, 50, 75, 100 ]
	});

	$("#tableAllAgentOvertimeDuration").DataTable({
		"autoWidth": false,
		"searching": false,
		// "lengthChange": false,
		"info": false
	});

	// Export Obidicen detail to Excel
	$("#buttonScheduleToExcel").on("click", function(){
		//e.preventDefault();		
		var startDate = $("#obidienceDetailDateStart").val();
		var endDate = $("#obidienceDetailDateEnd").val();
		var link = baseUrl + "obidience/toExcelObidienceDetail/" + startDate + "/" + endDate;
		// $("#formSelectPeriodObidienceDetail").removeAttr("action");
		// $("#formSelectPeriodObidienceDetail").attr("action", link);
		location.href = link;
	});
	
	// Export Obidicen OLD to Excel
	$("#obidienceDetailToExcel").on("click", function(){
		SwalInfo('We are sorry','This menu or function still unavailable yet','error');
	});

	// Delete obidience
	$(".container-fluid").on("click", ".buttonObidienceDataDelete", function(e){
		e.preventDefault();
		const complianceId = this.dataset.id;
		var title = "Are you sure to delete?";
		var text = "You won't be able to revert this Incompliance data";
		var link = $(this).attr("href");
		SwalConfirm(title, text, link, "Delete it", "warning");
	});

	// edit obidience
	$(".container-fluid").on("click", ".buttonObidienceDataEdit", function(e){
		e.preventDefault();
		SwalInfo('We are sorry','This menu or function still unavailable yet','error');
	})

	// edit Schedule Exchange
	$(".container-fluid").on("click", ".buttonEditScheduleReplace", function(e){
		const id = this.dataset.id;
		$.ajax({
			url : baseUrl + "obidience/scheduleExchangeById",
			data : {
				id : id
			},
			method : "post",
			dataType : "json",
			success : function(data){
				$("#scheduleExchangeId").val(data.id);
				$("#scheduleExchangeDate").val(data.date);
				$("#scheduleExchangeAgentScheduled").val(data.actual_overtime);
				$("#scheduleExchangeReplacedBy").val(data.replaced_by);
				$("#scheduleExchangeReason").val(data.reason);
				$("#scheduleExchangeRemark").val(data.remark);
				$("#scheduleExchangeTimeStart").val(data.time_start);
				$("#scheduleExchangeTimeEnd").val(data.time_end);
			}
		})
	});
	
	// edit Swap Schedule
	$(".container-fluid").on("click", ".buttonEditScheduleSwap", function(e){
		const id = this.dataset.id;
		$.ajax({
			url : baseUrl + "obidience/scheduleExchangeById",
			data : {
				id : id
			},
			method : "post",
			dataType : "json",
			success : function(data){
				$("#scheduleSwapIdFrom").val(data.id);
				$("#scheduleSwapDateFrom").val(data.date);
				$("#scheduleSwapAgentFrom").val(data.actual_overtime);
				$("#scheduleSwapTimeStartFrom").val(data.time_start);
				$("#scheduleSwapTimeEndFrom").val(data.time_end);				
				$("#scheduleSwapDurationFrom").val(data.duration);
				$("#scheduleSwapReasonFrom").val(data.reason);			
			}
		})
	});

	// get schedule data
	$("#scheduleSwapAgentTo").on("change", function(){
		var agent = $(this).val();
		var date = $("#scheduleSwapDateTo").val();
		$.ajax({
			url : baseUrl + "obidience/getScheduleExchangeByDateAgent",
			data : {
				agent : agent,
				date : date
			},
			method : "post",
			dataType : "json",
			success : function(data){
				$("#scheduleSwapIdTo").val(data.id);
				$("#scheduleSwapTimeStartTo").val(data.time_start);
				$("#scheduleSwapTimeEndTo").val(data.time_end);
				$("#scheduleSwapDurationTo").val(data.duration);
			}
		});
	});

	// get agents list on OT date
	$("#scheduleSwapDateTo").on("change", function(){
		var date = $(this).val();
		$.ajax({
			url : baseUrl + "obidience/getAgentsByDate",
			data : {
				date : date
			},
			method : "post",
			dataType : "json",
			success : function(data){
				$("#scheduleSwapAgentTo").find('option').remove();
				$('#scheduleSwapAgentTo').append($('<option>').text("- select agent -"));
				$.each(data, function (i, value) { 
					$('#scheduleSwapAgentTo').append($('<option>').text(value.agent).attr('value', value.agent));
				});				
			}
		});
	});

	$("#addSingleScheduleDate").on("change", function(){
		var date = $(this).val();
		// var startdate = $("#obidienceDetailDateStart").val();
		// var enddate = $("#obidienceDetailDateEnd").val();
		$.ajax({
			url : baseUrl + "obidience/unscheduledAgentsByDate",
			data : {
				date : date				
			},
			method : "post",
			dataType : "json",
			success : function(data){
				console.log(data);
				$("#addSingleScheduleAgentScheduled").find('option').remove();
				$.each(data, function (i, value) { 
					$('#addSingleScheduleAgentScheduled').append($('<option>').text(value.agent).attr('value', value.agent));
				});				
			}
		});
	});

	// get ot schedule for update
	$(".container-fluid").on("click", ".buttonEditScheduleUpdate", function(){
		const id = this.dataset.id;
		$.ajax({
			url : baseUrl + "obidience/scheduleExchangeById",
			data : {
				id : id
			},
			method : "post",
			dataType : "json",
			success : function(data){
				$("#scheduleUpdateId").val(data.id);
				$("#scheduleUpdateDate").val(data.date);
				$("#scheduleUpdateAgentScheduled").val(data.agent_scheduled);
				$("#scheduleUpdateTimeStart").val(data.time_start);
				$("#scheduleUpdateTimeEnd").val(data.time_end);
				$("#scheduleUpdateDuration").val(data.duration);
				$("#scheduleUpdateReplacedBy").val(data.replaced_by);
				$("#scheduleUpdateActualOvertime").val(data.actual_overtime);
				$("#scheduleUpdateActualStart").val(data.actual_start);
				$("#scheduleUpdateActualEnd").val(data.actual_end);
				$("#scheduleUpdateActualDuration").val(data.actual_duration);
				$("#scheduleUpdateReason").val(data.reason);
				$("#scheduleUpdateRemark").val(data.remark);
			}
		})
	});

	// Delete OT schedule
	$(".container-fluid").on("click", ".buttonDeleteScheduleUpdate", function(e){
		e.preventDefault();
		var id = this.dataset.id;
		const title = 'Sure to delete Overtime data?';
		const text = "You won't be able to revert data";
		const link = baseUrl + 'obidience/deleteObidience/' + id;
		SwalConfirm(title, text, link, "Delete", "warning");
	});

	// Get replacement automatically on modal
	$("#scheduleUpdateReplacedBy").on("change", function(){
		var actualOvertime = $(this).val();
		if(actualOvertime == '') {
			$("#scheduleUpdateActualOvertime").val($("#scheduleUpdateAgentScheduled").val());
		} else {
			$("#scheduleUpdateActualOvertime").val(actualOvertime);
		}
	});

	$("#scheduleUpdateAgentScheduled").on("click", function(){
		var actualOvertime = $(this).val();
		$("#scheduleUpdateActualOvertime").val(actualOvertime);
	})

	// Get OT duration automatically
	$("#addSingleScheduleTimeStart").on("change", function(){		
		performOTDurationByTimeStartEndAddSingle();
	});

	$("#addSingleScheduleTimeEnd").on("change", function(){		
		performOTDurationByTimeStartEndAddSingle();
	});

	$("#scheduleUpdateTimeStart").on("change", function(){
		var timeStart = $(this).val();
		var timeEnd = $("#scheduleUpdateTimeEnd").val();
		performOTDurationByTimeStartEndUpdate(timeStart, timeEnd, 'scheduleUpdateDuration');
	});
	
	$("#scheduleUpdateTimeEnd").on("change", function(){				
		var timeStart = $("#scheduleUpdateTimeStart").val();
		var timeEnd = $(this).val();
		performOTDurationByTimeStartEndUpdate(timeStart, timeEnd, 'scheduleUpdateDuration');
	});

	$("#scheduleUpdateActualStart").on("change", function(){		
		var timeStart = $(this).val();
		var timeEnd = $("#scheduleUpdateActualEnd").val();
		performOTDurationByTimeStartEndUpdate(timeStart, timeEnd, 'scheduleUpdateActualDuration');
	});
	
	$("#scheduleUpdateActualEnd").on("change", function(){				
		var timeStart = $("#scheduleUpdateActualStart").val();
		var timeEnd = $(this).val();
		performOTDurationByTimeStartEndUpdate(timeStart, timeEnd, 'scheduleUpdateActualDuration');
	});

	$("#buttonBenefitSimulationCalculate").on("click", function () {
		var salary = $("#benefitSimulationPersonalWage").val();
		if (salary == '' || salary <= 4000000) {
			SwalInfo("ALERT !", "Nominal gaji belum dimasukkan atau salah", "error");
		} else {
			fillingOvertimeFee(salary);
			totalSalaryEstimation();
			formexecute();
		}
	});

	$("#benefitSimulationMeal").on("input", function () {
		totalSalaryEstimation();
	});

	$("#benefitSimulationTransport").on("input", function () {
		totalSalaryEstimation();
	});

	// toggle date filter
	$("#buttonProductivityFillingToggleDateFilter").on("click", function(){
		$("#rowProductivityFillingFilter").toggle(100);
	});

	// auto sum productivity - on call input
	$("#tableProductivityFilling").ready(function(){
		// auto sum productivity - on call input
		$.each($("#tableProductivityFilling tbody tr .prodCallCol"), function() {
			$(this).on("input", function(){
				var ttl = 0;
				ttl = parseInt($(this).val()) + parseInt($(this).parent().siblings().find('.prodWhatsappCol').val()) + parseInt($(this).parent().siblings().find('.prodFollowupCol').val()) + parseInt($(this).parent().siblings().find('.prodOthersCol').val());
				$(this).parent().siblings().find('.prodTotalCol').val(ttl);
			});
		});

		// auto sum productivity - on WA input
		$.each($("#tableProductivityFilling tbody tr .prodWhatsappCol"), function() {
			$(this).on("input", function(){
				var ttl = 0;
				ttl = parseInt($(this).val()) + parseInt($(this).parent().siblings().find('.prodCallCol').val()) + parseInt($(this).parent().siblings().find('.prodFollowupCol').val()) + parseInt($(this).parent().siblings().find('.prodOthersCol').val());
				$(this).parent().siblings().find('.prodTotalCol').val(ttl);
			});
		});

		// auto sum productivity - on FU input
		$.each($("#tableProductivityFilling tbody tr .prodFollowupCol"), function() {
			$(this).on("input", function(){
				var ttl = 0;
				ttl = parseInt($(this).val()) + parseInt($(this).parent().siblings().find('.prodWhatsappCol').val()) + parseInt($(this).parent().siblings().find('.prodCallCol').val()) + parseInt($(this).parent().siblings().find('.prodOthersCol').val());
				$(this).parent().siblings().find('.prodTotalCol').val(ttl);
			});
		});

		// auto sum productivity - on Others input
		$.each($("#tableProductivityFilling tbody tr .prodOthersCol"), function() {
			$(this).on("input", function(){
				var ttl = 0;
				ttl = parseInt($(this).val()) + parseInt($(this).parent().siblings().find('.prodWhatsappCol').val()) + parseInt($(this).parent().siblings().find('.prodFollowupCol').val()) + parseInt($(this).parent().siblings().find('.prodCallCol').val());
				$(this).parent().siblings().find('.prodTotalCol').val(ttl);
			});
		});
	});

	// show OT productivity remark
	$(".container-fluid").on("click", ".buttonOvertimeProdRemark", function(){
		var text = this.dataset.remark;
		console.log(text);
		SwalInfoHtml('Remark on Productivity', text, 'info');
	});

	function fillingOvertimeFee(salary){
		var feeContainer = $(".overtimeFeeContainer");
		var calculateds = $(".durationCalculated");
		var totalFee = 0;
		for (let i = 0; i < feeContainer.length; i++) {
			fee = parseInt(salary) * parseFloat(calculateds[i].innerHTML) / 173;
			totalFee += fee;
			feeContainer[i].innerHTML = numberWithCommas(fee.toFixed(0));
		}
		$("#subtotalOvertimeFee").html(totalFee.toFixed(0));
		$("#benefitSimulationSummaryBasic").val(numberWithCommas(salary));
		$("#benefitSimulationOvertimeFee").val(totalFee.toFixed(0));
	}

	function totalSalaryEstimation() {
		var totalSalary = parseInt($("#benefitSimulationPersonalWage").val()) + parseInt($("#benefitSimulationTransport").val()) + parseInt($("#benefitSimulationMeal").val()) + parseInt($("#benefitSimulationOvertimeFee").val()) + parseInt($("#benefitSimulationOvertimeMeal").val()) + parseInt($("#benefitSimulationOvertimeTransport").val());
		$("#benefitSimulationGrandtotal").val(numberWithCommas(totalSalary));
	}

	function formexecute() {
		var endPeriod = $("#benefitSimulationDateEnd").val();
		var personalWage = $("#benefitSimulationPersonalWage").val();
		$.ajax({
			url: baseUrl + "obidience/updateWage",
			data: {
				date: endPeriod,
				personalWage : personalWage
			},
			method: "post",
			dataType: "json"
		});
	}

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

	function performOTDurationByTimeStartEndUpdate(timeStart, timeEnd, target){
		var dest = document.getElementById(target);
		$.ajax({
			url : baseUrl + "obidience/getOtDurationByTimeStartEnd",
			data : {
				timeStart : timeStart,
				timeEnd : timeEnd
			},
			method : "post",
			dataType : "json",
			success : function(data){
				$(dest).val(data);
			}
		})
	}

	function performOTDurationByTimeStartEndAddSingle(){
		var timeStart = $("#addSingleScheduleTimeStart").val();
		var timeEnd = $("#addSingleScheduleTimeEnd").val();
		$.ajax({
			url : baseUrl + "obidience/getOtDurationByTimeStartEnd",
			data : {
				timeStart : timeStart,
				timeEnd : timeEnd
			},
			method : "post",
			dataType : "json",
			success : function(data){
				$("#addSingleScheduleDuration").val(data);
			}
		})
	}

	// Chart summary of obidience
	var startPeriod = $("#obidienceSummaryDateStart").val();
	var endPeriod = $("#obidienceSummaryDateEnd").val();
	if (startPeriod) {
		$.ajax({
			url: baseUrl + "obidience/getSummary",	
			data: {
				startPeriod : startPeriod,
				endPeriod : endPeriod
			},
			method: "post",
			dataType: "json",
			success: function(data){
				console.log(data);
				var $obidienceSummaryChart = $('#obidienceSummaryChart');
				var obidienceSummaryChart  = new Chart($obidienceSummaryChart, {
					type   : 'bar',
					data   : {
						labels  : data.labels,
						datasets: [
							{
								backgroundColor: 'rgba(220, 53, 69, 1)',
								borderColor    : '#dc3545',
								data           : data.incompliance,							
							}
						]
					},
					options: {
						legend:{
							display: false
						},												
						scales : {
							yAxes:[{
								ticks:{
									suggestedMin: 0,
								},							
							}],				
							xAxes: [{
								display: true,
								gridLines: {
									display      : true,
									lineWidth    : '4px',
									color        : 'rgba(0, 0, 0, .2)',
									zeroLineColor: 'transparent'
								}
							}]						
						}
					}
				});
			}
		});
	}

	// -------------------------------------------------------------------------------------------	

	// PRODUCTIVITY
	// Select Productivity by Period by Agent
	$("#buttonSelectProductivityPeriod").on("click", function () {
		const agent = $("#selectProductivityAgent").val();
		const startPeriod = $("#selectProductivityStart").val();
		const endPeriod = $("#selectProductivityEnd").val();
		const averageRows = $("#rowProductivityAverage td");
		if (startPeriod == "" || endPeriod == "") {
			Swal.fire({
				title: "Data query failed",
				text: "Please fill the period of data you requested!",
				icon: "error",
			});
		} else {
			$.ajax({
				url: baseUrl + "productivity/productivityByPeriodByAgent",
				method: "post",
				data: {
					agent: agent,
					startPeriod: startPeriod,
					endPeriod: endPeriod,
				},
				dataType: "json",
				success: function (data) {
					let rows = data.length;
					$("#tableProductivityByPeriodByAgent tbody tr").not(":last").html("");
					for (let y = 0; y < rows; y++) {
						let newTr = document.createElement("tr");
						$(newTr).append(
							'<td class="text-center">' +
								toPeriodMin(data[y].period) +
								'</td><td class="text-center">' +
								data[y].icall +
								'</td><td class="text-center">' +
								data[y].callback +
								'</td><td class="text-center">' +
								data[y].follow_up +
								// '</td><td class="text-center">' +
								// data[y].sms +								
								'</td><td class="text-center">' +
								data[y].whatsapp +
								'</td><td class="text-center">' +
								data[y].sharp_id +
								'</td><td class="text-center">' +
								data[y].email +
								'</td><td class="text-center">' +
								data[y].notif_sap +
								'</td><td class="text-center">' +
								data[y].complaint +
								'</td><td class="text-center">' +
								data[y].part_code +
								'</td><td class="text-center">' +
								data[y].others +
								'</td><td class="text-center">' +
								data[y].total +
								'</td><td class="text-center">' +
								parseInt(data[y].work_hour) +
								'</td><td class="text-center">' +
								parseFloat(data[y].prod_hour).toFixed(1) +
								"</td>"
						);
						$(newTr).insertBefore("#rowProductivityAverage");
					}
					if (data.length == 1) {
						$("#productivityDataTitle").html(
							"Data of productivity on " + toPeriod(endPeriod)
						);
					} else {
						$("#productivityDataTitle").html(
							"Data of productivity on " +
								toPeriod(startPeriod) +
								" to " +
								toPeriod(endPeriod)
						);
					}
				},
			});
			$.ajax({
				url: baseUrl + "productivity/averageProductivityByPeriodByAgent",
				method: "post",
				data: {
					agent: agent,
					startPeriod: startPeriod,
					endPeriod: endPeriod,
				},
				dataType: "json",
				success: function (data) {
					console.log(data);
					averageRows[1].innerHTML = parseFloat(data.avg_icall).toFixed(0);
					averageRows[2].innerHTML = parseFloat(data.avg_callback).toFixed(0);
					averageRows[3].innerHTML = parseFloat(data.avg_follow_up).toFixed(0);
					// averageRows[4].innerHTML = parseFloat(data.avg_sms).toFixed(0);
					averageRows[4].innerHTML = parseFloat(data.avg_whatsapp).toFixed(0);
					averageRows[5].innerHTML = parseFloat(data.avg_sharp_id).toFixed(0);
					averageRows[6].innerHTML = parseFloat(data.avg_email).toFixed(0);
					averageRows[7].innerHTML = parseFloat(data.avg_notif_sap).toFixed(0);
					averageRows[8].innerHTML = parseFloat(data.avg_complaint).toFixed(0);
					averageRows[9].innerHTML = parseFloat(data.avg_part_code).toFixed(0);
					averageRows[10].innerHTML = parseFloat(data.avg_others).toFixed(0);
					averageRows[11].innerHTML = parseFloat(data.avg_total).toFixed(0);
					averageRows[12].innerHTML = parseFloat(data.avg_work_hour).toFixed(0);
					averageRows[13].innerHTML = parseFloat(data.avg_prod_hour).toFixed(1);
				},
			});
		}
	});

	// Select Summary Productivity by Period
	$("#buttonSelectSummaryProductivity").on("click", function () {
		getSummaryProductivity();
	});

	// Select Oder Data Summary Productivity
	$("#buttonSelectOrderSummaryProductivity").on("click", function () {
		getSummaryProductivity();
	});

	$("#buttonProductvitiyDetailToExcel").on("click", function(){
		//e.preventDefault();		
		var startDate = $("#selectSummaryProductivityStart").val();
		var endDate = $("#selectSummaryProductivityEnd").val();
		var link = baseUrl + "productivity/detailByPeriodToExcel/" + startDate + "/" + endDate;		
		location.href = link;
	});

	$("#buttonSelectProductivityByPeriod").on("click", function () {
		getProductivityByPeriod();
	});

	$("#buttonSelectOrderProductivityByPeriod").on("click", function () {
		getProductivityByPeriod();
	});

	$(".container-fluid").on(
		"click",
		".buttonEditProductivityAgent",
		function () {
			const id = this.dataset.id;
			$("#productivityAddSingleDataReset").hide();
			$("#editDataProductivity").modal("show");
			$("#editDataProductivityLabel").html("Edit agent productivity data");			
			$("#productivityAddSingleDataDelete").show();
			$("#productivityAddSingleDataSubmit").hide();
			$("#productivityUpdateDataSubmit").show();
			$.ajax({
				url: baseUrl + "productivity/productivityById",
				method: "post",
				dataType: "json",
				data: {
					id: id
				},
				success: function(data){
					$("#addProductivityPeriod").val(data.period);
					$("#addProductivityAgent").val(data.agent);
					$("#addProductivityIcall").val(data.icall);
					$("#addProductivityCallback").val(data.callback);
					$("#addProductivityFollowup").val(data.follow_up);
					$("#addProductivitySms").val(data.sms);
					$("#addProductivityWhatsapp").val(data.whatsapp);
					$("#addProductivitySharpid").val(data.sharp_id);
					$("#addProductivityEmail").val(data.email);
					$("#addProductivityNotifSap").val(data.notif_sap);
					$("#addProductivityComplaint").val(data.complaint);
					$("#addProductivityPartcode").val(data.part_code);
					$("#addProductivityOthers").val(data.others);
					$("#addProductivityWorkHour").val(parseInt(data.work_hour));
				}
			});
		}
	);

	$("#buttonAddDataProductivity").on("click", function () {
		$("#editDataProductivity .modal-footer .btn-warning").show();
		$("#editDataProductivity .modal-footer .btn-danger").hide();
		$("#productivityUpdateDataSubmit").hide();
		$("#productivityAddSingleDataSubmit").show();
	});

	$("#productivityUpdateDataSubmit").on("click", function(){
		$("#editDataProductivity div div form").attr("action", baseUrl + "productivity/editSingleProductivity");
		$(this).attr("type", "submit");		
	});

	$("#productivityAddSingleDataDelete").on("click", function(){
		const period = $("#addProductivityPeriod").val();
		const agent = $("#addProductivityAgent").val();
		const link = baseUrl + "deleteSingleProductivity/" + period + "/" + agent;
		const text = "You won't be able to revert data";
		const title = "Sure to Delete?";
		Swal.fire({
			title: title,
			text: text,
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: 'Delete',
		}).then((result) => {
			if (result.value) {
				$("#editDataProductivity div div form").attr("action", baseUrl + "productivity/deleteSingleProductivity");
				$("#editDataProductivity div div form").submit();
			}
		});
	});

	// Get Summary Productivity
	function getSummaryProductivity() {
		const startPeriod = $("#selectSummaryProductivityStart").val();
		const endPeriod = $("#selectSummaryProductivityEnd").val();
		const orderBy = $("#selectOrderSummaryProductivity").val();
		const orderType = $("#selectOrderTypeSummaryProductivity").val();

		if (startPeriod == endPeriod) {
			$("#productivityDataTitle").html(
				"Data of Agent Productivity on " + toPeriod(endPeriod)
			);
		} else {
			$("#productivityDataTitle").html(
				"Data of Agent Productivity on " +
					toPeriod(startPeriod) +
					" to " +
					toPeriod(endPeriod) +
					" (monthly average)"
			);
		}

		$.ajax({
			url: baseUrl + "productivity/getSummaryProductivity",
			method: "post",
			data: {
				startPeriod: startPeriod,
				endPeriod: endPeriod,
				orderBy: orderBy,
				orderType: orderType,
			},
			dataType: "json",
			success: function (data) {
				let dataRowsCa = document.querySelector("#tableProductivityCa tbody");
				let dataRowsPa = document.querySelector("#tableProductivityPa tbody");
				let dataRowsPart = document.querySelector(
					"#tableProductivityPart tbody"
				);
				let dataRowsSap = document.querySelector("#tableProductivitySap tbody");
				let dataRowsBo = document.querySelector("#tableProductivityBo tbody");
				// console.log(dataRows);
				let rows = data.length;
				$(dataRowsCa).html("");
				$(dataRowsPa).html("");
				$(dataRowsPart).html("");
				$(dataRowsSap).html("");
				$(dataRowsBo).html("");
				for (let y = 0; y < rows; y++) {
					if (data[y].jobcode == "cs-ccc-cc10" || data[y].jobcode == "cs-ccc-cc11"  || data[y].jobcode == "cs-ccc-cc12") {
						appendDataProd(dataRowsCa, data[y], false);
					} else if (data[y].jobcode == "cs-ccc-cc20") {
						appendDataProd(dataRowsPa, data[y], false);
					} else if (data[y].jobcode == "cs-ccc-cc30") {
						appendDataProd(dataRowsPart, data[y], false);
					} else if (data[y].jobcode == "cs-ccc-cc40") {
						appendDataProd(dataRowsSap, data[y], false);
					} else {
						appendDataProd(dataRowsBo, data[y], false);
					}
				}
			},
		});
	}

	// Get Summary Productivity
	function getProductivityByPeriod() {
		const period = $("#selectProductivityByPeriod").val();
		const orderBy = $("#selectOrderProductivityByPeriod").val();
		const orderType = $("#selectOrderTypeProductivityByPeriod").val();

		$("#productivityByPeriodDataTitle").html(
			"Data of Agent Productivity on " + toPeriod(period)
		);

		$.ajax({
			url: baseUrl + "productivity/getSummaryProductivity",
			method: "post",
			data: {
				startPeriod: period,
				endPeriod: period,
				orderBy: orderBy,
				orderType: orderType,
			},
			dataType: "json",
			success: function (data) {
				let dataRows = document.querySelector("#tableProductivityAll tbody");
				let rows = data.length;
				$(dataRows).html("");
				for (let y = 0; y < rows; y++) {
					appendDataProd(dataRows, data[y], true);
				}
			},
		});
	}

	// Append Data to Table on Summary Productivity
	function appendDataProd(dataRows, data, check) {
		if (check === false) {
			$(dataRows).append(
				"<tr><td>" +
					data.agent +
					'</td><td class="text-center">' +
					parseFloat(data.icall).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.callback).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.follow_up).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.sms).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.whatsapp).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.sharp_id).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.email).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.notif_sap).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.complaint).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.part_code).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.others).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.total).toFixed(0) +
					'</td><td class="text-center">' +
					parseInt(data.work_hour) +
					'</td><td class="text-center">' +
					parseFloat(data.prod_hour).toFixed(1) +
					"</td></tr>"
			);
		} else {
			$(dataRows).append(
				"<tr><td>" +
					data.agent +
					'</td><td class="text-center">' +
					parseFloat(data.icall).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.callback).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.follow_up).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.sms).toFixed(0) +					
					'</td><td class="text-center">' +
					parseFloat(data.whatsapp).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.sharp_id).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.email).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.notif_sap).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.complaint).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.part_code).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.others).toFixed(0) +
					'</td><td class="text-center">' +
					parseFloat(data.total).toFixed(0) +
					'</td><td class="text-center">' +
					parseInt(data.work_hour) +
					'</td><td class="text-center">' +
					parseFloat(data.prod_hour).toFixed(1) +
					'</td><td class="text-center"><button class="btn btn-sm text-primary buttonEditProductivityAgent" data-id="' +
					data.id +
					'"><span class="lnr lnr-pencil"></span></button></td></tr>'
			);
		}
	}

	// Productivity Daily - Chart transition
	$("#chartProductivityDailyByAgent").ready(function(){
		$.ajax({
			url: baseUrl + "productivity/productivitydailytransition",
			method: "post",
			data: {
				startPeriod: $("#productivityDailySelectDateStart").val(),
				endPeriod: $("#productivityDailySelectDateEnd").val(),
				agent: $("#productivityDailySelectAgent").val()
			},
			dataType: "json",
			success: function(result){
				var $chartProductivityDailyByAgent = $('#chartProductivityDailyByAgent');
				var chartProductivityDailyByAgent  = new Chart($chartProductivityDailyByAgent, {
					type   : 'line',
					data   : {
						labels  : result.date,
						datasets: [
							{
								label : "Total produktivitas per hari",
								backgroundColor : "#17A2B8",
								borderColor : "#17A2B8",
								data : result.totalproductivity,
								fill : false
							},
							{
								label : "target",
								backgroundColor : "orange",
								borderColor : "orange",
								fill : false,
								data : result.target
							}
						]
					},
					options: {
						legend : {
							display : true
						},
						scales : {
							y : {
								suggestedMin : 10		
							}
						}					
					}					
				});
			}
		});
	})

	// Download to Excel Productivity Daily
	$("#buttonProductvitiyDailySummaryToExcel").on("click", function(){
		var startPeriod = $("#productivityDailySelectDateStart").val();
		var endPeriod = $("#productivityDailySelectDateEnd").val();
		var link = baseUrl + 'productivity/productivityDailySummaryToExcel/' + startPeriod + '/' + endPeriod;
		window.location.href = link;
		// SwalConfirm("Download data?", "Data will be downloaded on spreadsheet", link, "Download", "info");
	});

	// productivity add per interval
	$('#inputRawProductivity').summernote({
		toolbar: [],
		height: "180px",
		callbacks: {
			onPaste: function (e) {
				var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');

				e.preventDefault();

				// Firefox fix
				setTimeout(function () {
					document.execCommand('insertText', false, bufferText);
				}, 10);
			}
		}
	});

	// Maintain Productivity Daily (OH)
	$(".container-fluid").on("click", ".buttonSelectAgentProductivityDaily", function(){
		var n = $(".container-fluid .buttonSelectAgentProductivityDaily:checked").length;
		if (n > 0) {
			var text = 'Delete Selected ' + n + ' Rows';
			$("#buttonDeleteSelectedProductivityDaily").html(text);
			$("#buttonDeleteSelectedProductivityDaily").show(100);
		} else {
			$("#buttonDeleteSelectedProductivityDaily").hide(100);
		}
	});

	$("#buttonSelectAllProductivityDaily").on("click", function(){
		var tbl = $("#tableListProductivityForEdit");

		if ($(this).is(":checked")) {
			$(".buttonSelectAgentProductivityDaily").prop("checked", true);
			$("#buttonDeleteSelectedProductivityDaily").show(100);
		} else {
			$(".buttonSelectAgentProductivityDaily").prop("checked", false);
			$("#buttonDeleteSelectedProductivityDaily").hide(100);
		}
	});

	// delete
	$("#buttonDeleteSelectedProductivityDaily").on("click", function(){
		var nums = $(".container-fluid .buttonSelectAgentProductivityDaily:checked").toArray();
		var lists = [];
		for (let i = 0; i < nums.length; i++) {
			lists.push(nums[i].value);
		}
		console.log(lists);
		$.ajax({
			url : baseUrl + 'productivity/deleteDailyMulti',
			data : {lists : lists},
			method : "post",
			success : function() {
				var title = nums.length + " Data Deleted";
				var text = "Deleted data can not be reverted";
				SwalConfirmReload(title, text, "OK", "info");
			}
		});
	});

	$(".container-fluid").on("click", ".buttonDeleteSingleProductivityDaily", function(e){
		e.preventDefault();
		var id = this.dataset.id;
		var link = this.href;
		SwalConfirm("Confirm Delete Data?", "Deleted data cannot be revert", link, "Delete", "warning");
	})

	// Edit Daily
	$(".container-fluid").on("click", ".buttonEditSingleProductivityDaily", function(e){
		e.preventDefault();
		var id = this.dataset.id;
		$.ajax({
			url : baseUrl + 'productivity/getDailySingleById',
			method : "post",
			data : {id : id},
			dataType : "json",
			success : function(data) {
				$("#editProductivityDailyId").val(data.id);
				$("#editProductivityDailyDate").val(data.date);
				$("#editProductivityDailyAgent").val(data.agent);
				$("#editProductivityDailyIcall").val(data.icall);
				$("#editProductivityDailyWhatsapp").val(data.whatsapp_reply);
				$("#editProductivityDailyFollowup").val(data.followup);
				$("#editProductivityDailyTarget").val(data.target);
				$("#editProductivityDailyRemark").val(data.remark.toUpperCase());
				// $("#editProductivityDailyAssignment").val(data.assignment.toUpperCase());
				if (data.assignment == 'whatsapp') {
					$("#editProductivityDailyAssignmentWhatsapp").prop("checked", true);
				} else if (data.assignment == 'follow up') {
					$("#editProductivityDailyAssignmentFollowup").prop("checked", true);
				} else {
					$("#editProductivityDailyAssignmentReguler").prop("checked", true);
				}
			}
		})
	});

	// Delete Single Row Productivity Interval
	$(".buttonDeleteProductivityInterval").on("click", function(e){
		e.preventDefault();
		var agent = this.dataset.agent;
		var link = baseUrl + 'productivity/deleteproductivityinterval/' + agent;
		SwalConfirm("Confirm Delete?", "After data deleted it unable to revert", link, "Delete", "warning");
	});

	// Maintain Productivity Interval
	// Auto Sum Productivity per Agent Row
	$("#tableListProductivityForEdit").ready(function(){
		var calls = $(".prodCall");
		var whatsapps = $(".prodWhatsapp");
		var followups = $(".prodFollowup");
		var totals = $(".prodTotal");
		
		for (let i = 0; i < totals.length; i++) {
			$(calls[i]).on("input", function(){
				autosumRowProductivityInterval(i);
			});
			$(whatsapps[i]).on("input", function(){
				autosumRowProductivityInterval(i);
			});
			$(followups[i]).on("input", function(){
				autosumRowProductivityInterval(i);
			});
		}

		function autosumRowProductivityInterval(i){
			$(totals[i]).val(parseInt(calls[i].value) + parseInt(whatsapps[i].value) + parseInt(followups[i].value));
		}
	});



	// Show/hide delete button
	$(".container-fluid").on("click", ".buttonSelectAgentProductivityInterval", function(){
		var n = $(".container-fluid .buttonSelectAgentProductivityInterval:checked").length;
		if (n > 0) {
			var text = 'Delete Selected ' + n + ' Rows';
			$("#buttonDeleteSelectedProductivityInterval").html(text);
			$("#buttonDeleteSelectedProductivityInterval").show(100);
		} else {
			$("#buttonDeleteSelectedProductivityInterval").hide(100);
		}
	});

	// select all rows prod interval
	$("#buttonSelectAllProductivityInterval").on("click", function(){
		var tbl = $("#tableListProductivityForEdit");

		if ($(this).is(":checked")) {
			$(".buttonSelectAgentProductivityInterval").prop("checked", true);
			$("#buttonDeleteSelectedProductivityInterval").html("Delete all rows");
			$("#buttonDeleteSelectedProductivityInterval").show(100);
		} else {
			$(".buttonSelectAgentProductivityInterval").prop("checked", false);
			$("#buttonDeleteSelectedProductivityInterval").hide(100);
		}
	});

	// delete group productivity interval
	$("#buttonDeleteSelectedProductivityInterval").on("click", function(){
		var nums = $(".container-fluid .buttonSelectAgentProductivityInterval:checked").toArray();
		var lists = [];
		for (let i = 0; i < nums.length; i++) {
			lists.push(nums[i].value);
		}
		console.log(lists);
		$.ajax({
			url : baseUrl + 'productivity/deleteproductivityintervalgroup',
			data : {lists : lists},
			method : "post",
			success : function() {
				var title = nums.length + " Data Deleted";
				var text = "Deleted data can not be reverted";
				SwalConfirmReload(title, text, "OK", "info");
			}
		});
	});

	// -------------------------------------------------------------------------------------------	

	// PROFILE
	$("#buttonUpdatePassword").on("click", function () {
		$("#formUpdateProfile").hide();
		$("#formUpdatePassword").fadeToggle();
	});

	$("#buttonEditProfile").on("click", function () {
		$("#formUpdatePassword").hide();
		$("#formUpdateProfile").fadeToggle();
	});

	$('#profileEditPhoto').on('change',function(){
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    });

    $("#buttonProfileEditRemovePhoto").on("click", function(){
    	var link = baseUrl + 'profile/removephoto';
		var title = "Yakin Hapus Foto Profil?";
		var text = "Foto bisa diganti/upload ulang";
		SwalConfirm(title, text, link, "Hapus", "warning");
    });

    $("#btnBgprofileUpdate").on("click", function(){
    	var bg_position = $('.bgposition:checked').val();
    	var bg = $('.bgname:checked').val();
    	var user_id = $("#bguserid").val();
    	$.ajax({
    		url: baseUrl + "profile/updatebackground",
			data: {
				user_id : user_id,
				bg : bg,
				bg_position : bg_position
			},
			method: "post",
			success : function(data){
				//console.log(data);
				// SwalInfo('Success', 'Background updated', 'info');
				Swal.fire({
					title: 'Success',
					text: 'Background updated',
					icon: 'success',
					showCancelButton: false,
					confirmButtonColor: "#007BFF",
					cancelButtonColor: "#DC3545",
					confirmButtonText: 'OK',
				}).then((result) => {
					if (result.value) {
						window.location.href = baseUrl + 'dashboard';
					}
				});
			}
    	});
    });

	// -------------------------------------------------------------------------------------------	

	// SETTING
	// activate-deactivate display setting for dashboard items
	$(".buttonActivateDashboardItem").on("click", function(){
		const id = this.dataset.itemid;
		let idValue = this.dataset.value;
		console.log(id, idValue);
		location.href = baseUrl + 'setting/toggleDashboardItem/' + id + '/' + idValue;
	});

	// activate-deactivate survey
	$("#buttonActivateSurvey").on("click", function(){
		var showSurvey = this.dataset.value;
		if ($(this).prop("checked") == true) {
			$(this).val(1);
		} else {
			$(this).val(0);
		}		
		//location.href = baseUrl + 'setting/toggleSurveyDisplay/' + showSurvey;
	});	

	$("#tableGeneralInfoList").DataTable({
		// "pageLength" : 100,
		"lengthChange" : false,
		"info" : false,		
		// "paging" : false
	});	

	$(".container-fluid").on("click", ".buttonGeneralInfoContentDelete", function(e){
		e.preventDefault();
		var id = this.dataset.id;
		var link = this.href + "/" + id;
		var title = "Sure to delete?";
		var text = "General Info content can not be revert";
		SwalConfirm(title, text, link, "Delete", "warning");
	});

	$("#submitActivateSurvey").on("click", function(){
		console.log($("#buttonActivateSurvey"));
		var showSurvey = $("#buttonActivateSurvey").val();
		console.log(showSurvey);
	})

	$("#buttonAddKpiTarget").on("click", function(){
		const title = 'Please confirm!';
		const text = "Add new KPI target and measurement?";
		const link = baseUrl + 'setting/kpiAdd';
		SwalConfirm(title, text, link, "Yes, go", "warning");
	});

	$("#buttonAddKpiMeasurement").on("click", function(){
		const title = 'Please confirm!';
		const text = "Add new KPI target and measurement?";
		const link = baseUrl + 'setting/kpiMeasurementAdd';
		SwalConfirm(title, text, link, "Yes, go", "warning");
	});	

	$(".container-fluid").on("click", ".buttonDeleteBreakSchedule", function(e){
		e.preventDefault();
		const id = this.dataset.id;
		const title = 'Sure to delete break schedule?';
		const text = "";
		const link = baseUrl + 'setting/deleteBreakSchedule/' + id;
		SwalConfirm(title, text, link, "Delete", "warning");
	});

	$("#buttonSaveUpdateBreakSchedule").on("click", function(){
		var names = $(".breakScheduleList");
		var schedule = [];
		for (let i = 0; i < names.length; i++) {
			const id = names[i].dataset.id;
			const name = names[i].value;
			schedule.push({
				id: id,
				name: name
			});
		}	
		//console.log(schedule);		
		$.ajax({
			url: baseUrl + "setting/updateBreakSchedule",
			data: {
				schedule : schedule
			},
			method: "post",
			success: function (data) {
				// window.location.reload();
				Swal.fire({
					title: "Update sucess",
					text: "Break schedule successly updated",
					icon: "success",
				});
			},
		});
	});

	//Copy to New Schedule
	$(".buttonCopyToNewSchedule").on("click", function(){
		var id = this.dataset.id;
		$("#copyBreakScheduleSourceid").val(id);
	});

	$(".buttonEditBreakdate").on("click", function(){
		var id = this.dataset.id;
		$.ajax({
			url: baseUrl + "setting/breakdateById",
			data: { id : id	},
			dataType : "json",
			method: "post",
			success: function (data) {
				console.log(data);
				$("#editBreakdateId").val(data.id);
				$("#editBreakdateStartdate").val(data.date_start);
				$("#editBreakdateEnddate").val(data.date_end);
				$("#editBreakdateRemark").val(data.remark);
			},
		});
	});


	// confirm delete schedule group
	$(".container-fluid").on("click", ".buttonDeleteScheduleGroup", function(e){
		e.preventDefault();
		const id = this.dataset.id;
		const title = 'Sure to delete break schedule group?';
		const text = "";
		const link = baseUrl + 'setting/deleteBreakScheduleGroup/' + id;
		SwalConfirm(title, text, link, "Delete", "warning");
	});

	$("#buttonUpdateBreakSchedule").on("click", function(){
		submitUpdateDetailBreakSchedule();
	})

	function submitUpdateDetailBreakSchedule()
	{
		const breakId = $("#breakDetailGroupId").html();
		var agents = $(".itemList");
		var dataUpdate = [];
		for (agent of agents) {
			var breakgroup = agent.dataset.breakgroup;
			var rowData = [];
			rowData.push(breakId, breakgroup, agent.dataset.name);
			dataUpdate.push(rowData);
		}

		// $("#dataCollectBreakScheduleUpdate").val(dataUpdate);
		console.log(dataUpdate);
		// $("#formSubmitUpdateBreakSchedule").submit();

		$.ajax({
			url : baseUrl + "setting/updateBreakScheduleGroup",
			method : "post",
			dataType : "json",
			data : {dataUpdate : dataUpdate},
			success : function(data) {
				Swal.fire({
					title: "Success",
					text: "Break schedule updated!",
					icon: "success",
					showCancelButton: false,
					confirmButtonColor: "#3085d6",
					confirmButtonText: "OK",
				});
			}
		});
	}

	// ===============================================
	// Working Calendar		
	$("#settingWorkingCalendarTable").DataTable({
		"pageLength" : 12,
		"lengthMenu" : [12, 24, 36, 48, 60],
		"info" : false,
		"searching" : true,
	});	

	$("#buttonAddSingleWorkingmonth").on("click", function(){
		$("#addNewWorkingCalendarTitle").html("Add new working calendar data");
		$("#addNewWorkingCalendar form").attr("action", "");
		$("#addNewWorkingCalendarSubmit").html("Save");
		$("#addNewWorkingCalendarId").val('');
		$("#addNewWorkingCalendarMonth").val('');
		$("#addNewWorkingCalendarDays").val('');
	})

	$(".container-fluid").on("click", ".buttonDeleteWorkingCalendar", function(e){
		e.preventDefault();	
		const id = this.dataset.id;
		const title = 'Sure to delete this working month?';
		const text = "";
		const link = baseUrl + 'setting/deleteWorkingmonth/' + id;
		SwalConfirm(title, text, link, "Delete", "warning");
	});

	$(".container-fluid").on("click", ".buttonEditWorkingCalendar", function(e){
		e.preventDefault();	
		const id = this.dataset.id;
		$("#addNewWorkingCalendarTitle").html("Edit working calendar data");
		$("#addNewWorkingCalendar form").attr("action", baseUrl + "setting/editWorkingmonth");
		$("#addNewWorkingCalendarSubmit").html("Update");
		$.ajax({
			url : baseUrl + "setting/workingmonthById",
			method : "post",
			dataType : "json",
			data : {id : id},
			success : function(data) {
				console.log(data);
				$("#addNewWorkingCalendarId").val(data.id);
				$("#addNewWorkingCalendarMonth").val(data.working_month);
				$("#addNewWorkingCalendarDays").val(data.working_day);
			}
		});
	});

	// General Vote
	$(".container-fluid").on("click", ".buttonToggleVoteStatus", function(){
		var id = this.value;
		var stts;
		this.checked == true ? stts = 1 : stts = 0;
		$.ajax({
			url : baseUrl + 'setting/toggleGeneralVote',
			method : "post",
			data : {
				id : id,
				stts : stts
			},
			success : function (data){
				SwalInfo('Updated', 'General vote status updated!', 'success');
				//window.location.reload(false);
			}
		});
	});

	$("#buttonAddNewGeneralVote").on("click", function(){
		$("#modalAddGeneralVoteLabel").html("Add New General Vote");
		$("#modalAddGeneralVote form").attr("action", "");
		$("#addGneralVoteId").val("");
		$("#addGneralVoteName").val("");
		$("#addGneralVoteDesc").html("");
		$("#addGneralVoteDatalist").html("");
		$("#addGneralVoteDateStart").val("");
		$("#addGneralVoteDateEnd").val("");
		$("#submitKpiNewTargetAdd").html("Save");
	});

	$(".container-fluid").on("click", ".buttonGeneralVoteEdit", function(e){
		e.preventDefault();
		var id = this.dataset.id;
		$("#modalAddGeneralVoteLabel").html("Edit General Vote");
		$("#modalAddGeneralVote form").attr("action", baseUrl + 'setting/editGeneralVote');
		$("#submitKpiNewTargetAdd").html("Update");

		$.ajax({
			url : baseUrl + 'setting/generalVoteById',
			data : {id : id},
			method : "post",
			dataType : "json",
			success : function(data) {
				$("#addGneralVoteId").val(data.id);
				$("#addGneralVoteName").val(data.vote_name);
				$("#addGneralVoteDesc").html(data.vote_desc);
				$("#addGneralVoteDatalist").html(data.data_list);
				$("#addGneralVoteDateStart").val(data.vote_start);
				$("#addGneralVoteDateEnd").val(data.vote_end);
			}
		});
	});

	$(".container-fluid").on("click", ".buttonGeneralVoteDelete", function(e){
		e.preventDefault();
		var id = this.dataset.id;
		const title = 'Sure to delete?';
		const text = "You will delete this Vote. Please be careful!";
		const link = baseUrl + 'setting/deleteGeneralVote/' + id;
		SwalConfirm(title, text, link, "Delete", "warning");
	});
	

	// ------------------------------------------------------------------------------------------

	// CHAT
	// Summernote for compose message
	$('#formChatMesage').summernote({
		toolbar: [
		    ['style', ['bold', 'italic', 'underline', 'clear']],
		    ['font', ['strikethrough', 'superscript', 'subscript']],
		    ['fontsize', ['fontsize']],
		    ['color', ['color']],
		    ['para', ['ul', 'ol', 'paragraph']],
		    ['table', ['table']],
		    ['height', ['height']],
		    ['view', ['codeview']],
		],
		disableDragAndDrop : true,
		height: "80px",
		callbacks: {
			onPaste: function (e) {
				var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');

				e.preventDefault();

				// Firefox fix
				setTimeout(function () {
					document.execCommand('insertText', false, bufferText);
				}, 10);
			}
		}
	});

	$('#editMessageDetail').summernote({
		toolbar: [
	    ['style', ['bold', 'italic', 'underline', 'clear']],
	    ['font', ['strikethrough', 'superscript', 'subscript']],
	    ['fontsize', ['fontsize']],
	    ['color', ['color']],
	    ['para', ['ul', 'ol', 'paragraph']],
	    ['table', ['table']],
	    ['height', ['height']],
	    ['view', ['codeview']],
	  ],
		height: "200px",
		callbacks: {
			onPaste: function (e) {
				var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
				e.preventDefault();

				// Firefox fix
				setTimeout(function () {
					document.execCommand('insertText', false, bufferText);
				}, 1);
			}
		}
	});

	// Load New Chat
	// let lastChatId = ChatConfig.lastId;
	// let lastDatetime = ChatConfig.lastDatetime;
	// function loadNewChat() {
	//     $.ajax({
	//         url: ChatConfig.baseUrl + "chat/get_new_messages",
	//         type: 'GET',
	//         data: { last_id: lastDatetime },
	//         dataType: 'json',
	//         success: function(response) {
	// 		    if (response.length > 0) {
	// 		        // Balikkeun urutanana sangkan nu panganyarna aya di luhur pisan
	// 		        response.reverse().forEach(function(row) {
	// 		            let html = (row.userid == ChatConfig.userId) 
	// 		                       ? renderRightChat(row) 
	// 		                       : renderLeftChat(row);
			            
	// 		            if ($(`#chat${row.id}`).length == 0) { 
	// 					    $('#container-table-chat').prepend(html);
	// 					}

	// 		            // UPDATE lastChatId ka ID panggedena nu aya dina response
	// 		            // Ieu tameng sangkan teu duplikat
	// 		            if (parseInt(row.id) > lastChatId) {
	// 		                lastChatId = parseInt(row.id);
	// 		            }

	// 		        });
	// 		    }
	// 		}
	//     });
	// }

	// function renderRightChat(row) {
	//     let quotaHtml = '';
	//     if (parseInt(row.quota_limit) > 0) {
	//         quotaHtml = `
	//             <div class="border-top mt-2 pt-1" style="border-top: 1px dashed rgba(255,255,255,0.3) !important">
	//                 <small class="font-weight-bold">Monitoring Antrian (Limit: ${row.quota_limit})</small>
	//             </div>`;
	//     }

	//     return `
	//         <div class="direct-chat-msg right float-right mt-2" style="max-width: 80%; min-width: 55%" id="chat${row.id}">	    		
	//     		<div class="pretty p-icon p-toggle p-plain repliedtoButton"  style="opacity: 0.5;"">
    //                 <input type="radio" name="repliedtoId" id="" value="${row.id}" class="disabled">
    //                 <div class="state p-off">
    //                     <i class="icon fas fa-reply "></i>
    //                     <label></label>
    //                 </div>
    //                 <div class="state p-on p-info-o">
    //                     <i class="icon fas fa-reply"></i>
    //                     <label></label>
    //                 </div>
    //             </div>
	//             <div class="direct-chat-infos clearfix">
	//                 <span class="direct-chat-timestamp float-left">${row.datetime}</span>
	//                 <a href="#" data-target="#modalEditMessage" data-toggle="modal" class="btnEditChatMessage text-secondary ml-2" data-id="${row.id}"><i class="fas fa-edit"></i></a>
	//             </div>
	//             <img class="direct-chat-img" src="${ChatConfig.baseUrl}assets/img/profile/${row.photo}">
	//             <div class="direct-chat-text bg-sender">
	//                 ${row.message}
	//                 ${quotaHtml}
	//             </div>
	//         </div>
	//         <div class="clearfix"></div>`;
	// }

	// function renderLeftChat(row) {
	//     let btnAntrian = '';
	//     // Paké ChatConfig.userName
	//     let currentUserName = ChatConfig.userId;

	//     if (parseInt(row.quota_limit) > 0) {
	//         let countLi = (row.message.match(/<li>/g) || []).length;
	//         let sisa = parseInt(row.quota_limit) - countLi;
	//         let geusDaptar = row.message.includes(`<li>${currentUserName}</li>`);

	//         let btnStatus = '';
	//         if (geusDaptar) {
	//             btnStatus = `<button class="btn btn-sm btn-success disabled w-100" disabled><i class="fas fa-check"></i> Ente udah daftar</button>`;
	//         } else if (sisa <= 0) {
	//             btnStatus = `<button class="btn btn-sm btn-secondary disabled w-100" disabled>Kuota Pinuh</button>`;
	//         } else {
	//             btnStatus = `<button class="btn btn-sm btn-primary w-100 btn-ikut" data-id="${row.id}">
	//                             <i class="fas fa-ticket-alt"></i> Ikut Antrian (${sisa})
	//                          </button>`;
	//         }

	//         btnAntrian = `
	//             <div id="wrapper-antrian-${row.id}" class="mt-2 pt-2 border-top" style="border-top: 1px solid rgba(0,0,0,0.1) !important">
	//                 ${btnStatus}
	//             </div>`;
	//     }

	//     return `
	//         <div class="direct-chat-msg mt-3 float-left" style="max-width: 80%; min-width: 60%;" id="chat${row.id}">
	//     		<div class="pretty p-icon p-toggle p-plain repliedtoButton"  style="opacity: 0.5;">
    //                 <input type="radio" name="repliedtoId" id="" value="${row.id}" class="disabled">
    //                 <div class="state p-off">
    //                     <i class="icon fas fa-reply "></i>
    //                     <label></label>
    //                 </div>
    //                 <div class="state p-on p-info-o">
    //                     <i class="icon fas fa-reply"></i>
    //                     <label></label>
    //                 </div>
    //             </div>
	//             <div class="direct-chat-infos clearfix">
	//                 <span class="direct-chat-name float-left">${row.userid}</span>
	//                 <span class="direct-chat-timestamp float-right">${row.datetime}</span>
	//             </div>
	//             <img class="direct-chat-img" src="${ChatConfig.baseUrl}assets/img/profile/${row.photo}">
	//             <div class="direct-chat-text bg-receiver">
	//                 ${row.message}
	//                 ${btnAntrian}
	//             </div>
	//         </div>
	//         <div class="clearfix"></div>`;
	// }
	// setInterval(loadNewChat, 5000);

	// Event listener pas tombol "Ikut Antrian" diklik
	$(document).on('click', '.btnSubmitVoulenteer', function(e) {
	    e.preventDefault();
	    let btn = $(this);
	    let idPesan = btn.data('id');

	    if (this.innerHTML == '<i class="fas fa-check-square"></i> Sudah Ikut Antrian') {
	    	SwalInfo('Sudah Masuk List!', 'Ya kali udah antre masih mau ikutan ngantre lagi', 'error');
	    } else if (this.innerHTML == '<i class="fas fa-minus-circle"></i> Kuota sudah penuh!') {
	    	SwalInfo('Kuota Penuh!', 'Terlambat daftar/kesalip yang lain. Sabar ya Le', 'error');
	    } else {
		    // Cegah double click: langsung disable & pasang loading
		    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Ngadaptar...');
		    $.ajax({
		        url: ChatConfig.baseUrl + 'chat/add_volunteer_with_limit',
		        type: 'POST',
		        data: { id: idPesan},
		        dataType: 'json',
		        success: function(res) {
		            if (res.status === 'success') {
		            	chatAutoLoad();
		                // Notif sukses (bisa make SweetAlert mun aya)
		                SwalInfo('Success', 'Berhasil masuk antrian', 'success');
		                // updateSingleMessage(idPesan);
		                // Opsional: Langsung ganti tombol jadi "Tos Daptar" tanpa nunggu 5 detik
		                // btn.removeClass('btn-primary').addClass('btn-success').html('<i class="fas fa-check"></i> Anjeun sudah daftar');
		            } else {
		                // alert(res.message);
		                SwalInfo('Failed', res.message, 'error');
		                // Mun gagal, balikeun deui tombolna sangkan bisa dicoba deui
		                btn.prop('disabled', true).html('<i class="fas fa-check-square"></i> Sudah Ikut Antrean, Coy!');
		            }
		        },
		        error: function() {
		            // alert('Aduh, aya gangguan jaringan euy!');
		            SwalInfo('Failed', 'Mungkin ada gangguan jaringan', 'error');
		            btn.prop('disabled', false).html('<i class="fas fa-hand-paper"></i> Ikut Antrian');
		        }
		    });
	    }
	});

	$(document).on('click', '.btnBatalVoulenteer', function(e) {
	    e.preventDefault();
	    let btn = $(this);
	    let idPesan = btn.data('id');

	    Swal.fire({
	        title: 'Sure to cancel queue?',
	        text: "Nama dikau akan hilang dari peredaran list ini",
	        icon: 'warning',
	        showCancelButton: true,
	        confirmButtonColor: "#007BFF",
			cancelButtonColor: "#DC3545",
	        confirmButtonText: 'Yes, Cancel!'
	    }).then((result) => {
	        if (result.isConfirmed) {
	            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Ngahapus...');
	            
	            $.ajax({
	                url: ChatConfig.baseUrl + 'chat/remove_volunteer',
	                type: 'POST',
	                data: { id: idPesan },
	                dataType: 'json',
	                success: function(res) {
	                    if (res.status === 'success') {
	                        chatAutoLoad(); // Refresh daptar chat
	                        SwalInfo('Ok!', res.message, 'success');
	                    } else {
	                        SwalInfo('Gagal', res.message, 'error');
	                        btn.prop('disabled', false);
	                    }
	                },
	                error: function() {
	                    SwalInfo('Error', 'Gangguan jaringan, coba deui euy!', 'error');
	                    btn.prop('disabled', false);
	                }
	            });
	        }
	    });
	});

	$(document).on('click', '.btn-admin-delete', function(e) {
	    let icon = $(this);
	    let namaUser = icon.data('name');
	    let idPesan = icon.data('id'); // Saluyukeun jeung atribut ID chat maneh

	    Swal.fire({
	        title: 'Remove from list?',
	        text: "Bakal ngahapus " + namaUser + " tina daptar",
	        icon: 'warning',
	        showCancelButton: true,
	        confirmButtonColor: "#007BFF",
			cancelButtonColor: "#DC3545",
	        confirmButtonText: 'Enya, Hapus!'
	    }).then((result) => {
	        if (result.isConfirmed) {
	            $.ajax({
	                url: ChatConfig.baseUrl + 'chat/admin_remove_volunteer',
	                type: 'POST',
	                data: { 
	                    id: idPesan, 
	                    nama_user: namaUser 
	                },
	                dataType: 'json',
	                success: function(res) {
	                    if (res.status === 'success') {
	                        chatAutoLoad(); // Refresh daptar
	                        Swal.fire('Terhapus!', res.message, 'success');
	                    } else {
	                        Swal.fire('Gagal', res.message, 'error');
	                    }
	                }
	            });
	        }
	    });
	});

	// function cekAntrian() {
	//     var namaUser = ChatConfig.userId; // Ieu sesuaikeun jeung session/variabel user
	    
	//     var geusAya = $("ol li").filter(function() {
	//         return $(this).text().trim() === namaUser;
	//     }).length > 0;

	//     if (geusAya) {
	//         $("button").prop("disabled", true).text("Sudah Ikut Antrian");
	//     } else {
	//         $("button").prop("disabled", false).text("Ikut Antri");
	//     }
	// }

	function updateSingleMessage(idPesan) {
	    $.ajax({
	        url: ChatConfig.baseUrl + 'chat/messagebyid', // Jieun method anyar di Controller
	        type: 'POST',
	        data: { chatid: idPesan },
	        dataType: 'json',
	        success: function(row) {
	            if (row) {
	                // Render ulang eusi balon chatna hungkul
	                let newHtml = (row.userid == ChatConfig.userId) 
	                              ? renderRightChat(row) 
	                              : renderLeftChat(row);
	                
	                // Ganti balon chat nu lila ku nu anyar
	                $(`#chat${idPesan}`).replaceWith(newHtml);
	            }
	        }
	    });
	}

	// [NEW] post new message with user tagging
	// 1. FUNGSI PAS KLIK TOMBOL @ (TAG USER)
    // Pake $(document).on sangkan jalan terus sanajan chat di-reload
    $(document).on('click', '.btnActionTag', function(e) {
        e.preventDefault();
        
        let username = $(this).data('username');
        let userid = $(this).data('userid'); // Pastikeun di PHP aya data-userid
        
        if (username) {
            // Ngabersihan spasi dina ngaran sangkan jadi format @NgaranUser
            let cleanName = username.replace(/\s+/g, '');
            let tagText = "@" + cleanName + " ";
            
            // Masakkeun teks ka Summernote
            $("#formChatMesage").summernote('editor.insertText', tagText);
            
            // Simpen ID user nu ditag ka input hidden sangkan karingkus ku AJAX
            $('#postMessageTaggedUserId').val(userid);
            
            // Fokuskeun deui ka editor
            $("#formChatMesage").summernote('focus');
        }
    });

    // 2. FUNGSI SUBMIT PESEN VIA AJAX
    $("#buttonChatSubmit").on("click", function(e) {
        e.preventDefault();

        // Candak data tina form
        var message = $("#formChatMesage").summernote('code'); 
        var repliedto = $("#postMessageRepliedto").val();
        var issticky = $("#postMessageIssticky").is(":checked") ? 1 : 0;
        var notesticky = $("#postMessageStickyNote").val();
        var istagged = $("#postMessageIsTagged").is(":checked") ? "open" : null;
        
        // Candak ID user nu ditag tina hidden input
        var taggedUserId = $("#postMessageTaggedUserId").val(); 
        var postMessageQuotaLimit = $("#postMessageQuotaLimit").val();

        // Validasi pesen kosong (Summernote mawa tag p/br mun kosong)
        if (message == '<p><br></p>' || message == '' || message == '<p>&nbsp;</p>') {
            SwalInfo('Hmmmm!', 'Gak boleh post pesan kosong', 'error');
            return false;
        }

        // console.log(message, repliedto, issticky, notesticky, taggedUserId, postMessageQuotaLimit);

        // Eksekusi AJAX
        $.ajax({
            url : baseUrl + "chat/postMessage",
            method : "post",
            dataType: "json",
            data : {
                "message" : message,
                "replied_to" : repliedto,
                "is_sticky" : issticky,
                "note_sticky" : notesticky,
                "tagged_by" : istagged, // ID User dikirim ka kolom tagged_by
                "postMessageQuotaLimit" : postMessageQuotaLimit,
            },
            beforeSend: function() {
                // Opsi: Tambahkeun loading mun perlu
                $("#buttonChatSubmit").prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            },
            success : function(res) {
            	console.log(res);
            	// console.log("PHP response: ", res);
                // 1. Reset Summernote
                $("#formChatMesage").summernote('reset');
                
                // 2. Reset Form & Hidden Inputs
                $("#postMessageIssticky").prop("checked", false);
                $("#postMessageStickyNote").val('');
                $("#postMessageRepliedto").val(null);
                $("#postMessageTaggedUserId").val(''); // WAJIB: Reset tag sangkan teu nempel terus
                $("#postMessageIsTagged").prop('checked', false);
                $("#postMessageQuotaLimit").val(0);
                
                // 3. Reset Button State
                $("#buttonChatSubmit").prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Send');
                
                // 4. Refresh Chat Room
                if(typeof chatAutoLoad === 'function') {
                    chatAutoLoad();
                }
            },
            error: function(xhr, status, error) {
                SwalInfo('Error!', 'Gagal mengirim pesan', 'error');
                $("#buttonChatSubmit").prop('disabled', false).html('Send');
            }
        });
    });

	$(document).on("click", ".btnEditChatMessage", function(e){
		e.preventDefault();
		var chatid = this.dataset.id;
		var msg = $(this).parent().next().next().html().trim();
		var isSticky = this.dataset.issticky;
		var noteId = 'note'+chatid;
		// var noteSticky = document.getElementById(noteId).innerHTML;
		// console.log(chatid, isSticky, noteId, noteSticky);

		$.ajax({
			url : baseUrl + "chat/messagebyid",
			method : "post",
			dataType : "json",
			data : {
				chatid : chatid
			},
			success : function(data) {
				// blank the form
				$('#editMessageDetail').summernote('reset');
				var newstring = '<p>' + msg + '</p>';
				// fill the form
				$("#editMessageId").val(chatid);
				$('#editMessageStickyNote').val(data.note_sticky);
				$('#editMessageDetail').summernote('pasteHTML', data.message);
				if (isSticky == 1) {
					$("#editMessageIssticky").prop('checked', true);
				} else {
					$("#editMessageIssticky").prop('checked', false);
				}
			}
		})
		
	});

	// replied to on hover
	$(document).on("hover", ".repliedtoButton", function(){
		$(this).css("opacity", 1);
	});

	// replied to on click
	$(document).on("click", ".repliedtoButton", function(){
		$(this).css("opacity", 1);
		var id = $(this).children().first().val();
		$("#postMessageRepliedto").val(id);
		console.log($(this).children().first());
	});

	// message tag button [NEW]
	$("#container-table-chat").on("click", ".btnTagMessage", function(){
	    var id = this.dataset.id;
	    var userid = this.dataset.userid;
	    var $btn = $(this); // Simpen context tombolna

	    // Langsung tembak fungsi tag, tong dicek misah
	    $.ajax({
	        url : baseUrl + "chat/tagmessage",
	        data : {
	            id : id,
	            user_id : userid
	        },
	        method : "post",
	        dataType: "json", // Pastikeun backend ngirim json
	        beforeSend: function() {
	            $btn.prop('disabled', true); // Ngonci tombol pas keur loading proses
	        },
	        success : function(response) {
	        	console.log(response);
	            // var res = JSON.parse(response); // Mun teu otomatis jadi object
			    if (response.status === "success") {
			        // SwalInfo('Mantap!', 'Pesen geus ditag ku anjeun', 'success');
			        chatAutoLoad();
			    } else {
			        SwalInfo('Telat Coy!', response.message, 'error');
			        chatAutoLoad();
			    }
	        },
	        error: function() {
	            SwalInfo('Error!', 'Aya gangguan jaringan, Breh.', 'error');
	            $btn.prop('disabled', false); // Buka deui mun error jaringan
	        }
	    });
	});

	// Dismiss pinned message
	$(".container-fluid").on("click", ".dismissPinMessage", function(e){
		e.preventDefault();
		var id = this.dataset.id;
		$.ajax({
			url : baseUrl + "chat/dismisspinned",
			data : {id : id},
			method : "post",
			success : function() {
				SwalConfirmReload('Success', 'Message unpinned from sticky', "OK", "info");
			}
		});
	});

	$(".container-fluid").on("click", ".btnDeleteReplyTemplate", function(e){
		e.preventDefault();
		var id = this.dataset.id;
		var link = baseUrl + 'chat/deletetemplate/' + id;
		SwalConfirm("Sure to Delete?", "After deleted, it is unable to be reverted", link, "Delete", "warning");
	});

	// $(".buttonCopyTemplate").on("click", function(){
	// 	var teks = $(this).prev().prev().text();
	// });

	// -------------------------------------------------------------------------------------------	

	// USER MANAGEMENT
	// Data Tabel User List
	// $("#tableUserLists").DataTable({
	// 	"autoWidth" : true,
	// 	"searching" : true,
	// 	"lengthChange" : false,
	// 	"pageLength" : 100,
	// 	"info" : false,
	// 	"paging" : false
	// });

	$("#buttonShowUserByJoindate").on("click", function () {
		$("#listAllUserByJoinDate").fadeIn();
		$("#listAllUserByBirthdate").hide();
		$("#formAddUser").hide();
	});

	$("#buttonShowUserByBirthdate").on("click", function () {
		$("#listAllUserByBirthdate").fadeIn();
		$("#listAllUserByJoinDate").hide();
		$("#formAddUser").hide();
	});

	$(".buttonDeleteUser").on("click", function () {
		const user_id = this.dataset.userid;
		const title = "Sure to delete this user?";
		const text = "You won't be able to revert user data!";
		Swal.fire({
			title: title,
			text: text,
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Delete",
		}).then((result) => {
			if (result.value) {
				window.location.href =
					baseUrl + "usermanagement/deleteUserById/" + user_id;
			}
		});
	});

	$("#tableListRequestResetPassword tbody tr").on("click", function () {
		const user_id = $(this).data("userid");
		const id = $(this).data("id");
		$("#colActionResetId").val(id);
		$("#colActionResetUserId").val(user_id);
		$("#colActionResetPassword").fadeIn(100);
	});

	$("#buttonCloseActionResetPassword").on("click", function () {
		$("#colActionResetPassword").fadeOut(100);
	});

	// reset password
	$("#buttonResetPassword").on("click", function () {
		const id = $("#colActionResetId").val();
		const user_id = $("#colActionResetUserId").val();
		$.ajax({
			url: baseUrl + "usermanagement/resetPassword",
			data: {
				id: id,
				user_id: user_id,
			},
			method: "post",
			success: function () {
				Swal.fire({
					title: "Password Reset",
					text: "User password successly setted to default!",
					icon: "success",
					showCancelButton: false,
					confirmButtonColor: "#3085d6",
					confirmButtonText: "OK",
				}).then((result) => {
					if (result.value) {
						window.location.reload(false);
					}
				});
			},
		});
	});

	// unlock/reset user
	$("#buttonUnlockUser").on("click", function () {
		const id = $("#colActionResetId").val();
		const user_id = $("#colActionResetUserId").val();
		$.ajax({
			url: baseUrl + "usermanagement/resetUser",
			data: {
				id: id,
				user_id: user_id,
			},
			method: "post",
			success: function () {
				Swal.fire({
					title: "Unlocking User",
					text: "You've been successly unlock user account",
					icon: "success",
					showCancelButton: false,
					confirmButtonColor: "#3085d6",
					confirmButtonText: "OK",
				}).then((result) => {
					if (result.value) {
						window.location.reload(false);
					}
				});
			},
		});
	});

	// delete reset request
	$("#buttonDismissResetRequest").on("click", function () {
		const id = $("#colActionResetId").val();
		const user_id = $("#colActionResetUserId").val();
		// console.log(id);
		$.ajax({
			url: baseUrl + "usermanagement/dismissResetRequest",
			data: {
				id: id,
				user_id: user_id,
			},
			method: "post",
			success: function () {
				Swal.fire({
					title: "Dismiss Request",
					text: "You've been dismissed reset request",
					icon: "success",
					showCancelButton: false,
					confirmButtonColor: "#3085d6",
					confirmButtonText: "OK",
				}).then((result) => {
					if (result.value) {
						window.location.reload(false);
					}
				});
			},
		});
	});

	// -----------------------------------------------------------------------------------------

	// VOICE ASSESSMENT

	//	Survey Form
	$("#voiceSurveyFormPeriod").on("change", function () {
		voiceInfoByPeriodeByAgent();
	});

	$("#voiceSurveyFormAgent").on("change", function () {
		voiceInfoByPeriodeByAgent();
	});

	// Current Score on survey
	var surveyInputs = $("#voiceSurveyFormContainer input:radio[name^='voiceSurveyForm']");
	countCurrentVoiceScoreOnClick(surveyInputs, $("#voiceSurveyFormCurrentScore"));

	// Current Score on Edit
	var surveyEdit = $("#voiceSurveyEditContainer input:radio[name^='voiceSurveyEdit']");
	voiceInfoByPeriodeByAgentOnEdit();
	countCurrentVoiceScoreOnClick(surveyEdit, $("#voiceSurveyEditCurrentScore"));

	function countCurrentVoiceScoreOnClick(elmt, target) {
		var initVal = 0;
		for (let i = 0; i < elmt.length ; ++i) {
            if ($(elmt[i]).prop('checked')) {
                initVal += parseInt($(elmt[i]).val());
            }
        }

        var initRslt = value2barslite(parseFloat(initVal / 25 * 100).toFixed(1), initVal, 25);
	    target.html(initRslt);

		elmt.on("click", function(){
			var count = 0;
	        for (let i = 0; i < elmt.length ; ++i) {
	            if ($(elmt[i]).prop('checked')) {
	                count += parseInt($(elmt[i]).val());
	            }
	        }
	        var rslt = value2barslite(parseFloat(count / 25 * 100).toFixed(1), count, 25);
	        target.html(rslt);
		});
	}

	function voiceInfoByPeriodeByAgent() {
		const user_id = $("#voiceSurveyFormAgent").val();
		const period = $("#voiceSurveyFormPeriod").val();
		$.ajax({
			url: baseUrl + "voice/numberVoiceByAgentByPeriod",
			method: "post",
			data: {
				user_id: user_id,
				period: period,
			},
			success: function (data) {
				const e = JSON.parse(data);
				var rslt = value2barslite(parseFloat(e.averageScore / 25 * 100).toFixed(1), e.averageScore.toFixed(0), 25);
				$("#voiceSurveyFormLatestScore").html(rslt);
				$("#voiceSurveyFormVoiceNumber").val(parseInt(e.voiceNumber) + 1);
			},
		});
	}

	function voiceInfoByPeriodeByAgentOnEdit() {
		const user_id = $("#voiceSurveyEditAgent").val();
		const period = $("#voiceSurveyEditPeriod").val();
		$.ajax({
			url: baseUrl + "voice/numberVoiceByAgentByPeriod",
			method: "post",
			data: {
				user_id: user_id,
				period: period,
			},
			success: function (data) {
				const e = JSON.parse(data);
				var rslt = value2barslite(parseFloat(e.averageScore / 25 * 100).toFixed(1), e.averageScore.toFixed(0), 25);
				$("#voiceSurveyEditLatestScore").html(rslt);
				$("#voiceSurveyEditVoiceNumber").val(parseInt(e.voiceNumber));
			},
		});
	}

	function value2barslite(rtio, score, qty) {
	 	clr = '';
	 	stat = '';
	 	if (rtio >= 95) {
	 		clr = 'success';
	 		stat = 'Good';
	 	} else if (rtio > 70 && rtio < 95) {
	 		clr = 'info';
	 		stat = 'Need improve';
	 	} else if (rtio > 50 && rtio < 70) {
	 		clr = 'warning';
	 		stat = 'Warning';
	 	} else {
	 		clr = 'danger';
	 		stat = 'Bad';
	 	}
	    teks = '<div class="col-sm-auto"><div><span class="badge">' + stat + ' <span class="text-muted">(' + score + '/' + qty + ')</span></div><span class="ml-1 float-right h6 text-' + clr + '"> ' + rtio + '%</span><div class="progress-group"><div class="progress" style="min-height: 18px;"><div class="progress-bar bg-'+ clr +'" style="width:' + rtio + '%; height: 100%;"></div></div></div></div>';
	    return teks;
	}

	function value2barsmini(rtio, qty) {
	 	clr = '';
	 	stat = '';
	 	if (rtio >= 95) {
	 		clr = 'success';
	 		stat = 'Good';
	 	} else if (rtio > 70 && rtio < 95) {
	 		clr = 'info';
	 		stat = 'Need improve';
	 	} else if (rtio > 50 && rtio < 70) {
	 		clr = 'warning';
	 		stat = 'Warning';
	 	} else {
	 		clr = 'danger';
	 		stat = 'Bad';
	 	}
	    teks = '<div class="col-sm-auto"><div><span class="badge">' + stat + ' </div><span class="ml-1 float-right h6 text-' + clr + '"> ' + rtio + '%</span><div class="progress-group"><div class="progress" style="min-height: 18px;"><div class="progress-bar bg-'+ clr +'" style="width:' + rtio + '%; height: 100%;"></div></div></div></div>';
	    return teks;
	}

	// Detail 
	$(".buttonVoiceDetailEdit").on("click", function(e){
		e.preventDefault();
		const id = this.dataset.id;
		const agent = this.dataset.agent;
		const phone = this.dataset.phone;
		var link = baseUrl + "voice/editVoice/" + id;
		// console.log(id);
		SwalConfirmHtml("Please confirm", 'Sure to edit result of <b class="text-indigo">' + agent + '</b> on <b class="text-indigo">' + phone + '</b>?', link, "Yes, Edit", "warning");
	})

	$(".buttonVoiceDetailDelete").on("click", function(e){
		e.preventDefault();
		const id = this.dataset.id;
		const title = 'Sure to Delete Voice Data?';
		const text = "You won't be able to revert data";
		const link = baseUrl + 'voice/deleteVoiceById/' + id;
		SwalConfirm(title, text, link, "Delete", "warning");
	});

	$("#buttonExcelVoiceDetail").on("click", function(){		
		const startPeriod = $("#voiceSummaryDateStart").val();
		const endPeriod = $("#voiceSummaryDateEnd").val();
		location.href = baseUrl + "voice/detailVoiceExportToExcel/" + startPeriod + "/" + endPeriod;		
	});

	$("#tableSummaryBadFindingsList").DataTable({
		"autoWidth": false,
		"pageLength" : 200,
		"info" : false,
		"lengthChange": false
	});

	$("#tableSummaryVoiceByAgent").DataTable({
		"autoWidth" : true,
		"searching" : false,
		"lengthChange" : false,
		"pageLength" : 100,
		"info" : false,
		"paging" : false
	});

	// Voice Detail Data Table
	$("#tableVoiceDetail").DataTable({
		"autoWidth": false
	});

	$("#tableVoiceByAgent").DataTable({
		"autoWidth": false
	});

	// Summary to Excel
	$("#buttonToExcelSummaryVoice").on("click", function(){
		var startPeriod = $("#selectSummaryVoiceStart").val();
		var endPeriod = $("#selectSummaryVoiceEnd").val();
		window.location.href = baseUrl + 'voice/summaryVoiceToExcel/' + startPeriod + '/' + endPeriod;
	});

	// chart summaryvoice - OLD data
	$("#chartVoiceUnproperSummary").on("ready", function(){
		$.ajax({
			url: baseUrl + "voice/unproperSummary",
			method: "post",
			data: {
				startPeriod: $("#selectSummaryVoiceStart").val(),
				endPeriod: $("#selectSummaryVoiceEnd").val()
			},
			dataType: "json",
			success: function(data){			
				var $chartVoiceUnproperSummary = $('#chartVoiceUnproperSummary');
				var $chartVoiceUnproperSummary  = new Chart($chartVoiceUnproperSummary, {
					type   : 'pie',
					data   : {
						labels  : data.labels,
						datasets: [
							{
								// backgroundColor: ["#fbf8cc", "#fde4cf", "#ffcfd2", "#f1c0e8", "#cfbaf0", "#a3c4f3", "#90dbf4", "#8eecf5", "#98f5e1", "#b9fbc0"],
								backgroundColor: ["#a4133c", "#f94144", "#f3722c", "#f8961e", "#f9844a", "#f9c74f", "#caffbf", "#fdffb6",  "#90be6d", "#43aa8b", "#4d908e", "#577590", "#277da1", "#073b4c"],
								borderColor    : 'transparent',
								data           : data.values,							
							}
						]
					},
					options: {
						title: {
				            display: true,
				            text: 'Unproper finding from Voice Assessment: ' + toPeriod($("#selectSummaryVoiceStart").val()) + ' to ' + toPeriod($("#selectSummaryVoiceEnd").val()),
				            size: 28
				        },
						legend:{
							display: true,
							position: "right"
						},																					
					}					
				});
			}
		});
	});

	$("#voiceTableTransitionByPeriod").DataTable({
		"autoWidth": false,
		"pageLength" : 100,
		"info" : false,
		"searching" : false,
		"lengthChange" : false,
		"paging": false
	});


	// Edit Voice Survey Result
	// console.log(JSON.stringify(voiceData));

	// ----------------------------------------------------------------------------------

	// ASSESSMENT
	// Edit Others KPI data
	$(".container-fluid").on("click", ".buttonOthersKpiDataEdit", function(e){
		e.preventDefault();
		$("#modalAddSingleOthersKpi form").attr("action", baseUrl + "assessment/editOthersKpiData");
		const id = this.dataset.id;
		$.ajax({
			url : baseUrl + "assessment/getSingleOthersKpi",
			method : "post",
			data : { id : id },
			dataType : "json",
			success: function(data) {
				console.log(data);
				$("#modalAddSingleOthersKpiLabel").html("Edit single Other KPI data");
				$("#addSingleOthersKpiId").val(data.id);
				$("#addSingleOthersKpiPeriod").val(data.period);
				$("#addSingleOthersKpiAgent").val(data.agent);
				$("#addSingleOthersKpiSkapeDraft").val(data.skape_draft);
				$("#addSingleOthersKpiSkapeSolution").val(data.skape_solution);
				$("#addSingleOthersKpiKnowledgeSharing").val(data.knowledge_sharing);
				$("#addSingleOthersKpiPartCallback").val(data.part_callback);
				$("#addSingleOthersKpiComplaintForward").val(data.complaint_forward);
				$("#addSingleOthersKpiComplaintCompletion").val(data.complaint_completion);
				$("#addSingleOthersKpiComplaintReport").val(data.complaint_report);
				$("#addSingleOthersKpiEmailReply").val(data.email_reply);
				$("#addSingleOthersKpiPromoInquiry").val(data.promo_inquiry);
			}
		});
	});

	$("#buttonOthersKpiDataAdd").on("click", function(){
		$("#modalAddSingleOthersKpiLabel").html("Add single Other KPI data");
		$("#addSingleOthersKpiId").val("");
		$("#addSingleOthersKpiPeriod").val("");
		$("#addSingleOthersKpiAgent").val("");
		$("#addSingleOthersKpiSkapeDraft").val("");
		$("#addSingleOthersKpiSkapeSolution").val("");
		$("#addSingleOthersKpiKnowledgeSharing").val("");
		$("#addSingleOthersKpiPartCallback").val("");
		$("#addSingleOthersKpiComplaintForward").val("");
		$("#addSingleOthersKpiComplaintReport").val("");
	});

	// Table of KPI Summary 
	$("#tableKpiResultSummarySeid").DataTable({
		"autoWidth" : false,
		"searching" : false,
		"lengthChange" : false,
		"pageLength" : 100,
		"info" : false,
		"paging": false
	});

	$("#tableKpiResultSummaryOts").DataTable({
		"autoWidth" : false,
		"searching" : false,
		"lengthChange" : false,
		"pageLength" : 100,
		"info" : false,
		"paging": false
	});

	// Count average value on each KPI item
	// Customer Assistant (Level 1)
	$(".averageKpiProductivityCA").html(getAverageResult($(".kpiResultProductivity")).toFixed(1) + '%');
	$(".averageKpiCsindexCA").html(getAverageResult($(".kpiResultCsindex")).toFixed(1) + '%');
	$(".averageKpiAbsenceCA").html(getAverageResult($(".kpiResultAbsence")).toFixed(1) + '%');
	$(".averageKpiElearningCA").html(getAverageResult($(".kpiResultElearning")).toFixed(1) + '%');

	var kpiAchievementCA =
		(parseInt($(".weightKpiProductivityCA").html()) * parseFloat($(".averageKpiProductivityCA").html()) / 100) + 
		(parseInt($(".weightKpiCsindexCA").html()) * parseFloat($(".averageKpiCsindexCA").html()) / 100) + 
		(parseInt($(".weightKpiAbsenceCA").html()) * parseFloat($(".averageKpiAbsenceCA").html()) / 100) + 
		(parseInt($(".weightKpiElearningCA").html()) * parseFloat($(".averageKpiElearningCA").html()) / 100);
	$(".kpiAchievementCA").html(parseFloat(kpiAchievementCA).toFixed(2) + '%  (' + convertKpiResultToAlphabet(kpiAchievementCA) + ') ');

	// Product Assistant
	$(".averageKpiProductivityPA").html(getAverageResult($(".kpiResultProductivity")).toFixed(1) + '%');
	$(".averageKpiCsindexPA").html(getAverageResult($(".kpiResultCsindex")).toFixed(1) + '%');
	$(".averageKpiAbsencePA").html(getAverageResult($(".kpiResultAbsence")).toFixed(1) + '%');
	$(".averageKpiSkapeDraftPA").html(getAverageResult($(".kpiResultSkapeDraft")).toFixed(1) + '%');
	$(".averageKpiKnowledgeSharingPA").html(getAverageResult($(".kpiResultKnowledgeSharing")).toFixed(1) + '%');

	var kpiAchievementPA =
		(parseInt($(".weightKpiProductivityPA").html()) * parseFloat($(".averageKpiProductivityPA").html()) / 100) + 
		(parseInt($(".weightKpiCsindexPA").html()) * parseFloat($(".averageKpiCsindexPA").html()) / 100) + 
		(parseInt($(".weightKpiAbsencePA").html()) * parseFloat($(".averageKpiAbsencePA").html()) / 100) + 
		(parseInt($(".weightKpiSkapeDraftPA").html()) * parseFloat($(".averageKpiSkapeDraftPA").html()) / 100) + 
		(parseInt($(".weightKpiKnowledgeSharingPA").html()) * parseFloat($(".averageKpiKnowledgeSharingPA").html()) / 100);
	$(".kpiAchievementPA").html(parseFloat(kpiAchievementPA).toFixed(2) + '%  (' + convertKpiResultToAlphabet(kpiAchievementPA) + ') ');

	// Spare Part
	$(".averageKpiProductivityPart").html(getAverageResult($(".kpiResultProductivity")).toFixed(1) + '%');
	$(".averageKpiCsindexPart").html(getAverageResult($(".kpiResultCsindex")).toFixed(1) + '%');
	$(".averageKpiElearningPart").html(getAverageResult($(".kpiResultElearning")).toFixed(1) + '%');
	$(".averageKpiAbsencePart").html(getAverageResult($(".kpiResultAbsence")).toFixed(1) + '%');
	$(".averageKpiPartCodePart").html(getAverageResult($(".kpiResultPartCode")).toFixed(1) + '%');
	$(".averageKpiPartCallbackPart").html(getAverageResult($(".kpiResultPartCallback")).toFixed(1) + '%');

	var kpiAchievementPart =
		(parseInt($(".weightKpiProductivityPart").html()) * parseFloat($(".averageKpiProductivityPart").html()) / 100) + 
		(parseInt($(".weightKpiCsindexPart").html()) * parseFloat($(".averageKpiCsindexPart").html()) / 100) + 
		(parseInt($(".weightKpiElearningPart").html()) * parseFloat($(".averageKpiElearningPart").html()) / 100) + 
		(parseInt($(".weightKpiAbsencePart").html()) * parseFloat($(".averageKpiAbsencePart").html()) / 100) + 
		(parseInt($(".weightKpiPartCodePart").html()) * parseFloat($(".averageKpiPartCodePart").html()) / 100) + 
		(parseInt($(".weightKpiPartCallbackPart").html()) * parseFloat($(".averageKpiPartCallbackPart").html()) / 100);
	$(".kpiAchievementPart").html(parseFloat(kpiAchievementPart).toFixed(2) + '%  (' + convertKpiResultToAlphabet(kpiAchievementPart) + ') ');

	// Spare Part Plus
	$(".averageKpiProductivityPartPlus").html(getAverageResult($(".kpiResultProductivity")).toFixed(1) + '%');
	$(".averageKpiCsindexPartPlus").html(getAverageResult($(".kpiResultCsindex")).toFixed(1) + '%');
	$(".averageKpiEmailReplyPartPlus").html(getAverageResult($(".kpiResultEmailReply")).toFixed(1) + '%');
	$(".averageKpiAbsencePartPlus").html(getAverageResult($(".kpiResultAbsence")).toFixed(1) + '%');
	$(".averageKpiPromoInquiryPartPlus").html(getAverageResult($(".kpiResultPromoInquiry")).toFixed(1) + '%');
	//$(".averageKpiPartCallbackPartPlus").html(getAverageResult($(".kpiResultPartCallback")).toFixed(1) + '%');

	var kpiAchievementPart =
		(parseInt($(".weightKpiProductivityPartPlus").html()) * parseFloat($(".averageKpiProductivityPartPlus").html()) / 100) + 
		(parseInt($(".weightKpiCsindexPartPlus").html()) * parseFloat($(".averageKpiCsindexPartPlus").html()) / 100) + 
		(parseInt($(".weightKpiEmailReplyPartPlus").html()) * parseFloat($(".averageKpiEmailReplyPartPlus").html()) / 100) + 
		(parseInt($(".weightKpiAbsencePartPlus").html()) * parseFloat($(".averageKpiAbsencePartPlus").html()) / 100) + 
		(parseInt($(".weightKpiPromoInquiryPartPlus").html()) * parseFloat($(".averageKpiPromoInquiryPartPlus").html()) / 100) 
		//(parseInt($(".weightKpiPartCallbackPartPlus").html()) * parseFloat($(".averageKpiPartCallbackPartPlus").html()) / 100);
	$(".kpiAchievementPartPlus").html(parseFloat(kpiAchievementPart).toFixed(2) + '%  (' + convertKpiResultToAlphabet(kpiAchievementPart) + ') ');

	// Complaint Specialist
	$(".averageKpiComplaintForwardComplaint").html(getAverageResult($(".kpiResultComplaintForwardComplaint")).toFixed(1) + '%');
	$(".averageKpiComplaintCompletionComplaint").html(getAverageResult($(".kpiResultComplaintCompletionComplaint")).toFixed(1) + '%');
	$(".averageKpiComplaintReportComplaint").html(getAverageResult($(".kpiResultComplaintReportComplaint")).toFixed(1) + '%');
	$(".averageKpiCsindexComplaint").html(getAverageResult($(".kpiResultCsindex")).toFixed(1) + '%');
	$(".averageKpiAbsenceComplaint").html(getAverageResult($(".kpiResultAbsence")).toFixed(1) + '%');	
	
	var kpiAchievementComplaint =
		(parseInt($(".weightKpiComplaintForwardComplaint").html()) * parseFloat($(".averageKpiComplaintForwardComplaint").html()) / 100) + 
		(parseInt($(".weightKpiComplaintCompletionComplaint").html()) * parseFloat($(".averageKpiComplaintCompletionComplaint").html()) / 100) + 
		(parseInt($(".weightKpiComplaintReportComplaint").html()) * parseFloat($(".averageKpiComplaintReportComplaint").html()) / 100) + 
		(parseInt($(".weightKpiCsindexComplaint").html()) * parseFloat($(".averageKpiCsindexComplaint").html()) / 100) + 
		(parseInt($(".weightKpiAbsenceComplaint").html()) * parseFloat($(".averageKpiAbsenceComplaint").html()) / 100);		
	$(".kpiAchievementComplaint").html(parseFloat(kpiAchievementComplaint).toFixed(2) + '%  (' + convertKpiResultToAlphabet(kpiAchievementComplaint) + ') ');

	// convert KPI result to alphabet
	function convertKpiResultToAlphabet(result) {
		var val = parseFloat(result) * 1.67;
        if (val > 111) {
            return 'S';
        } else if (val >= 106 && val < 111) {
            return 'A';
        } else if (val >= 100 && val < 106) {
            return 'B';
        } else if (val >= 80 && val < 100) {
            return 'C';
        } else {
            return 'D';
        }
	}
	
	$(".container-fluid").on("click", ".deleteOrderList", function(e){
		e.preventDefault();
		const id = this.dataset.id;
		var title = 'Yakin hapus order?';
		var text = 'Kalau dihapus harus input ulang';
		var link = baseUrl + 'form/deleteoder/' + id;
		SwalConfirm(title, text, link, 'Hapus', 'warning');
	});

	// function get Average Result KPI items
	function getAverageResult(data){
		var sumValue = 0;
		var aveValue = 0;
		for(let i = 0; i < data.length; i++){
			sumValue += parseFloat($(data[i]).html());
			aveValue = (sumValue / data.length);
		}
		return 	aveValue	
	}

	// othersKPI datatable
	$("#tableOthersKpiItem").DataTable({		
		"autoWidth" : false
	});	

	// delete KPI Other item
	$(".container-fluid").on("click", ".buttonOthersKpiDataDelete", function(e) {
		e.preventDefault();
		var link = this.href;
		console.log(link);		
		var title = "Delete data";
		var text = "Are you sure to delete this data?";
		var confirmText = "Cancel";
		var link = this.href;
		SwalConfirm(title, text, link, confirmText, "warning");
	});

	// ----------------------------------------------------------------------------------


	// MENU MANAGEMENT
	// dismiss menu access
	$(".container-fluid").on("click",".buttonDismissMenuAccess", function(){
		var menuid = this.dataset.menuid;
		var roleaccess = this.dataset.roleaccess;
		var title = "Dismiss Access";
		var text = "Are you sure to dismis this menu access?";
		var confirmText = "Dismiss";
		var link = baseUrl + "menumanagement/dismissMenuAccess/" + menuid + "/" + roleaccess;
		SwalConfirm(title, text, link, confirmText, "warning");
	});

	// Add menu access
	$("#menuAccessAdd").on("click", function(){
		const menusAssigned = $(".unassignedMenuAcces:checked");
		const menus = [];
		for (let i = 0; i < menusAssigned.length; i++) {
			const menu_id = menusAssigned[i].dataset.menuid;
			const role_access = $("#menumanagementSelectAccess").val();
			menus.push({
				menu_id: menu_id,
				role_access: role_access
			});
		}
		// console.log(menus);
		$.ajax({
			url: baseUrl + "menumanagement/addMenuAccess",
			data: { data: menus },
			method: "post",
			success: function (data) {
				window.location.reload(false);
			},
		});
	});

	// toggle submenu access
	$(".container-fluid").on("click", ".submenuAccessCheckbox", function(){
		var submenuid = this.dataset.submenuid;
		var roleaccess = this.dataset.roleaccess;
		var checkAccess = this.checked;

		console.log(submenuid, roleaccess, checkAccess);
		$.ajax({			
			url : baseUrl + "menumanagement/toggleSubmenuAccess",
			data : {
				submenuid : submenuid,
				roleaccess : roleaccess,
				checkAccess : checkAccess
			},
			method : "post",
			success : function() {
				window.location.reload(false);
			}
		})
	});

	// ----------------------------------------------------------------------------------

	// SURVEY
	var surveyCount = parseInt($("#surveyFilling").html());
	let tresh = parseInt($("#surveyTreshold").html());
	//let tresh = 3;
	if(surveyCount < tresh) {
		$("#categorySurvey").modal('show');
		$("#modalInfoIsiFeedbackNewskape").modal('show');
	}

	$(".container-fluid").on("click", ".btnDeleteFeedback", function(){
		const id = this.dataset.id;				
		var title = 'Yakin hapus Feedback?';
		var text = 'Setelah dihapus feedback tidak bisa dipulihkan';
		var link = baseUrl + 'survey/deleteSkapeFeedback/' + id;
		var confirmText = 'Delete';
		var icon = 'warning'
		SwalConfirm(title, text, link, confirmText, icon);
	});

	$(".container-fluid").on("click", ".btnEditFeedback", function(){
		$("#surveyFeedbackNewskapeLabel").html("Edit Feedback NEW SKAPE");
		$("#feedbackNewskapeSubmit").html("Update");
		$("#surveyFeedbackNewskape form").attr("action", baseUrl + "survey/editfeedback");
		const id = this.dataset.id;
		$.ajax({
			url : baseUrl + 'survey/feedbackbyid',
			method : 'post',
			dataType : 'json',
			data : {
				id : id
			},
			success : function (data) {
				console.table(data);
				$("#feedbackNewskapeId").val(data.id);
				$("#feedbackNewskapeCategory").val(data.category);
				$("#feedbackNewskapeDetail").html(data.detail);
			}
		});
	});

	$("#btnAddFeedbackNewskape").on("click", function(){
		$("#surveyFeedbackNewskapeLabel").html("Tambah Feedback NEW SKAPE");
		$("#feedbackNewskapeSubmit").html("Save");
		$("#feedbackNewskapeId").val('');
		$("#feedbackNewskapeCategory").val('');
		$("#feedbackNewskapeDetail").html('');
	});

	$("#tableFeedbackNewskape").DataTable({
		"lengthChange" : false,
		"info" : false
	});

	// ----------------------------------------------------------------------------------

	// SELECT2 JS
	$(".js-example-basic-single").select2({
		theme: 'bootstrap4',
	});
	// ----------------------------------------------------------------------------------
	
	// POPOVER
	$('.popover-dismiss').popover({
	  trigger: 'focus'
	})

	// ----------------------------------------------------------------------------------
	// GENERAL FUNCTION
	// BUTTON LOGOUT
	$("#buttonLogout").on("click", function (e) {
		const link = $(this).attr("href");
		buttonLogout(e, link);
	});

	$("#navButtonLogout").on("click", function (e) {
		const link = $(this).attr("href");
		buttonLogout(e, link);
	});

	function buttonLogout(e, link) {
		e.preventDefault();
		// console.log(link);
		const title = "Sure to logout?";
		const text = "Your current session will be ended";
		SwalConfirm(title, text, link, "Logout", "question");
	}

	// SELECT THEME ON NAVBAR
	$("#navbarSelectTheme span").on("click", function () {
		const themeId = $(this).data("theme");
		const themeText = $(this).data("themetext");
		const themeName = $(this).data("themename");
		const user_id = $(this).data("userid");
		const controller = $(this).data("controller");
		const method = $(this).data("method");
		// console.log(themeId, themeName, themeText, controller, method);

		$.ajax({
			url: baseUrl + "profile/setViewTheme",
			data: {
				id: themeId,
				user_id: user_id,
				controller: controller,
				method: method,
				theme_text: themeText,
				theme_name: themeName,
			},
			method: "post",
			success: function (data) {
				location.reload(false);
			},
		});
	});

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

	function SwalConfirmReload(title, text, confirmText, icon) {
		Swal.fire({
			title: title,
			text: text,
			icon: icon,
			showCancelButton: false,
			confirmButtonColor: "#007BFF",
			cancelButtonColor: "#DC3545",
			confirmButtonText: confirmText,
		}).then((result) => {
			if (result.value) {
				window.location.reload(false);
			}
		});
	}

	function SwalConfirmHtml(title, text, link, confirmText, icon) {
		Swal.fire({
			title: title,
			html: text,
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

	function SwalInfoHtml(title, text, icon) {
		Swal.fire({
			title: title,
			html: text,
			icon: icon,
			confirmButtonColor: "#007BFF",
			confirmButtonText: '<i class="fa fa-check"></i> OK'
		});
	}	

	// convert to long string period
	// function toDateLong(data) {
	// 	month = [
	// 		"January",
	// 		"February",
	// 		"March",
	// 		"April",
	// 		"May",
	// 		"June",
	// 		"July",
	// 		"August",
	// 		"September",
	// 		"October",
	// 		"November",
	// 		"December",
	// 	];
	// 	dateArray = data.substring(0, 10).split("-");
	// 	if (dateArray[0] == "0000") {
	// 		return "-";
	// 	} else {
	// 		date =
	// 			dateArray[2] +
	// 			" " +
	// 			month[parseInt(dateArray[1]) - 1] +
	// 			" " +
	// 			dateArray[0];
	// 		return date;
	// 	}
	// }

	// convert to short string period
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

	function fixPercentage(value, precision) {
		var multiplier = Math.pow(10, precision || 0);
		return Math.round(value * 100 * multiplier) / multiplier;
	}

	
});