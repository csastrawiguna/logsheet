<?php 

 function achievement2color($val, $mult) {
    $ratio = $val / $mult;
    if ($ratio < 0.80) {
       return 'danger';
    } else if ($ratio >= 0.80 && $ratio <0.90) {
       return 'warning';
    } else if ($ratio >= 0.90 && $ratio <0.95) {
       return 'info';
    } else {
       return 'success';
    }
 }

function value2bars($stat, $rtio, $qty, $clr) {
    $teks = '<div class="col-sm-3 mb-3"><div><span class="badge">' . $stat . '</span></div><span class="ml-1 float-right h6 text-' . $clr . '"> ' . $rtio . '</span><div class="progress-group"><div class="progress" style="min-height: 18px;"><div class="progress-bar bg-'. $clr .'" style="width:' . $rtio . '; height: 100%;"></div></div></div><div class="text-muted">' . $qty . ' <small>voices</small></div></div>';
    return $teks;
}

function value2barslite($rtio, $score, $qty) {
 	$clr = '';
 	$stat = '';
 	if ($rtio >= 90) {
 		$clr = 'success';
 		$stat = 'Good';
 	} else if ($rtio > 70 && $rtio < 90) {
 		$clr = 'info';
 		$stat = 'Need improve';
 	} else if ($rtio > 50 && $rtio < 70) {
 		$clr = 'warning';
 		$stat = 'Warning';
 	} else {
 		$clr = 'danger';
 		$stat = 'Bad';
 	}
    $teks = '<div class="col-sm-auto"><div><span class="badge">' . $stat . ' <span class="text-muted">(' . $score . '/' . $qty . ')</span></div><span class="ml-1 float-right h6 text-' . $clr . '"> ' . $rtio . '%</span><div class="progress-group"><div class="progress" style="min-height: 18px;"><div class="progress-bar bg-'. $clr .'" style="width:' . $rtio . '%; height: 100%;"></div></div></div></div>';
    return $teks;
}

function value2barstotal($rtio, $qty) {
 	$clr = '';
 	$stat = '';
 	if ($rtio >= 90) {
 		$clr = 'success';
 		$stat = 'Good';
 	} else if ($rtio > 70 && $rtio < 90) {
 		$clr = 'info';
 		$stat = 'Need improve';
 	} else if ($rtio > 50 && $rtio < 70) {
 		$clr = 'warning';
 		$stat = 'Warning';
 	} else {
 		$clr = 'danger';
 		$stat = 'Bad';
 	}
    $teks = '<div class="col-sm-auto"><div><span class="badge">' . $stat . ' </div><span class="ml-1 float-right h6 text-' . $clr . '"> ' . $rtio . '%</span><div class="progress-group"><div class="progress" style="min-height: 18px;"><div class="progress-bar bg-'. $clr .'" style="width:' . $rtio . '%; height: 100%;"></div></div></div></div>';
    return $teks;
}

// detail
function greeting2score($score) {
	if ($score == 3) {
		return '<span class="text-success">Good<br>(' . $score . ')</span>';
	} else {
		return '<span class="text-danger text-bold">Bad<br>(' . $score . ')</span>';
	}
}

function smile2score($score) {
	if ($score == 10) {
		return '<span class="text-success">Good<br>(' . $score . ')</span>';
	} else if ($score == 5) {
		return '<small class="text-info">Need improve<br>(' . $score . ')</small>';
	} else if ($score == 3) {
		return '<span class="text-warning">Flat<br>(' . $score . ')</span>';
	} else {
		return '<span class="text-danger text-bold">Bad<br>(' . $score . ')</span>';
	}
}

function accuracy2score($score) {
	if ($score == 10) {
		return '<span class="text-success">Good<br>(' . $score . ')</span>';
	} else if ($score == 5) {
		return '<span class="text-warning text-bold">Less<br>(' . $score . ')</span>';
	} else {
		return '<span class="text-danger text-bold">Bad<br>(' . $score . ')</span>';
	}
}

function closing2score($score) {
	if ($score == 2) {
		return '<span class="text-success">Good<br>(' . $score . ')</span>';
	} else {
		return '<span class="text-danger text-bold">Bad<br>(' . $score . ')</span>';
	}
}

function link2text($remark, $link) {
	if ($remark == '' || $remark == NULL) {
		if ($link !== '' || $link !== NULL) {
			return '<a href="'. base_url('assets/voices/') . $link .'"><i class="fas fa-volume-up text-primary"></i></a>';
		} 
	} else {
		if ($link == '' || $link == NULL) {
			return;
		} else {
			return '<br><a href="'. base_url('assets/voices/') . $link .'"><i class="fas fa-volume-up text-primary"></i></a>';
		}
	}
}

function source2icon($src) {
	if (strtolower($src) == 'incoming') {
		return '<span class="float-right" title="Incoming Call"><i class="fas fa-phone-square-alt text-primary"></i></span>';
	} else {
		return '<span class="float-right" title="Follow Up"><i class="fas fa-check-circle text-secondary"></i></span>';
	}
}

function surveyorTag($surveyor, $dt) {
	$first = strtoupper($surveyor[0]);
	$third = $surveyor[2];
	return '<span class="badge badge-pill badge-secondary px-2 py-1" title="Survey by : ' . $surveyor . '&#013;Survey at  : ' . date("d-M-Y", strtotime($dt)) . '" style="cursor: pointer;"><i class="fas fa-user-tie"></i> ' . $first . $third . '</span><br>';

}

function checkedTag($data, $ref) {
	$data == $ref ? $out = 'checked' : $out = '';
	return $out;
}

function itemScoreToColor($val)
{
	if ($val == 5) {
		$out = '<span class="badge badge-pill badge-success">Good (' . $val . ')</span>';
	} else if ($val == 3) {
		$out = '<span class="badge badge-pill badge-warning">Need improve (' . $val . ')</span>';
	} else{
		$out = '<span class="badge badge-pill badge-danger">Bad (' . $val . ')</span>';
	}
	return $out;
}

function wa2barstotal($rtio, $qty) {
 	$clr = '';
 	$stat = '';
 	if ($rtio >= 85) {
 		$clr = 'success';
 		$stat = 'Good';
 	} else if ($rtio >= 70 && $rtio < 85) {
 		$clr = 'warning';
 		$stat = 'Need improve';
 	} else {
 		$clr = 'danger';
 		$stat = 'Bad';
 	}
    $teks = '<div class="col-sm-auto"><div><span class="badge">' . $stat . ' </div><span class="ml-1 float-right h6 text-' . $clr . '"> ' . $rtio . '</span><div class="progress-group"><div class="progress" style="min-height: 18px;"><div class="progress-bar bg-'. $clr .'" style="width:' . $rtio . '%; height: 100%;"></div></div></div></div>';
    return $teks;
}

function wa2barslite($rtio, $score, $qty) {
 	$clr = '';
 	$stat = '';
 	if ($rtio >= 85) {
 		$clr = 'success';
 		$stat = 'Good';
 	} else if ($rtio > 40 && $rtio < 85) {
 		$clr = 'warning';
 		$stat = 'Need improve';
 	} else {
 		$clr = 'danger';
 		$stat = 'Bad';
 	}
    $teks = '<div class="col-sm-auto"><div><span class="badge">' . $stat . ' <span class="text-muted">(' . $score . ' / ' . $qty . ')</span></div><span class="ml-1 float-right h6 text-' . $clr . '"> ' . $rtio . '</span><div class="progress-group"><div class="progress" style="min-height: 18px;"><div class="progress-bar bg-'. $clr .'" style="width:' . $rtio . '%; height: 100%;"></div></div></div></div>';
    return $teks;
}

function wa2bars($stat, $rtio, $qty, $clr) {
    $teks = '<div class="col-sm-4 mb-3"><div><span class="badge">' . $stat . '</span></div><span class="ml-1 float-right h6 text-' . $clr . '"> ' . $rtio . '</span><div class="progress-group"><div class="progress" style="min-height: 18px;"><div class="progress-bar bg-'. $clr .'" style="width:' . $rtio . '; height: 100%;"></div></div></div><div class="text-muted">' . $qty . ' <small>chats</small></div></div>';
    return $teks;
}

$allowedAccess = in_array($this->session->userdata('role_access'), ['1', '4', '5', '6', '9']);