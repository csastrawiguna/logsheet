<?php 

$allowedChangeAgent = ['1', '2', '4', '5', '9'];

if(!$this->input->post()) {                  
  $agent = $this->session->userdata('user_id');
  $startDate = date("Y-m-d", strtotime("-30 days"));
  $endDate = date("Y-m-d");
} else {
  $agent = $this->input->post('productivityDailySelectAgent');
  $startDate = date("Y-m-d", strtotime($this->input->post('productivityDailySelectDateStart')));
  $endDate = date("Y-m-d", strtotime($this->input->post('productivityDailySelectDateEnd')));
}

function targetisnull($data) {
    if (is_null($data)) {
        return '-';
    } else {
        return $data['target'];
    }
}

function isundertarget($total, $fu, $assign, $target) {
    if (strtolower($assign) == 'follow up') {
        if ((int)$fu < (int)$target) {
            echo 'text-danger text-bold';    
        } else {
            echo '';
        }
    } else {
        if ((int)$total < (int)$target) {
            echo 'text-danger text-bold';
        } else {
            echo '';
        }
    }
}

function assignment2icon($job) {
    $icon = '';
    if (strtolower($job) === 'whatsapp') {
        $icon = '<span class="h5"><i class="fab fa-whatsapp text-success"></i></span>';
    } else if (strtolower($job) === 'follow up' || strtolower($job) === 'fu') {
        $icon = '<i class="far fa-smile-beam"></i>';
    } else {
        $icon = '<i class="fas fa-phone-alt text-secondary"></i>';
    }
    return $icon;
}

function achievement2color($ratio) {
    if ($ratio < 0.70) {
        return 'danger';
    } else if ($ratio >= 0.70 && $ratio <0.85) {
        return 'warning';
    } else if ($ratio >= 0.85 && $ratio <1) {
        return 'info';
    } else {
        return 'success';
    }
}



?>