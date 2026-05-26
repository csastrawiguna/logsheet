<?php                
    $allowSticky = ['1', '2', '4', '5', '6', '9'];
    $allowSetting = ['1', '5', '9'];

    function getColour($text, $comparison) {
        if ($text == $comparison) {
            echo '<span class="text-primary">' . $text . '</span>';
        } else {
            echo '<span class="text-dark">' . $text . '</span>';
        }
    }

    function timeStringer($text) {
        $output = '';
        $currDate = date("d-M-Y");
        $date = date("d-M-Y", strtotime($text));
        if ($date == $currDate) {
            echo date("H:i:s", strtotime($text));
        } else {
            echo '<em>' . date("d-M-Y H:i", strtotime($text)) . '</em>';
        } 

        return $output;
    }

    function stickyNote($text) {
        if ($text == 1) {
            return ' <i class="fas fa-bookmark text-danger"></i>&nbsp;';
        } else {
            return '';
        }
    }

    function repliedToStringSender($id, $userid, $message) {
        if ($id == NULL || $id == 0) {
            return '';
        } else {
            $content = preg_replace("/<img[^>]+\>/i", "[image] ", $message); 
            if(strlen($content) > 100) {
                $text = substr(strip_tags($content, '<p><br>'), 0, 100);
            } else {
                $text = $content;
            }
            return '<div class="callout callout-warning"><span><b class="text-secondary">' . $userid . '</b><br><a href="#chat' . $id . '" class="text-secondary text-decoration-none">' . $text . '</a></span></div>';
        }
    }

    function repliedToStringOthers($id, $userid, $message) {
        if ($id == NULL || $id == 0) {
            return '';
        } else {
            $content = preg_replace("/<img[^>]+\>/i", "[image] ", $message);
            if(strlen($content) > 100) {
                $text = substr(strip_tags($content, '<p><br>'), 0, 100);
            } else {
                $text = $content;
            }
            return '<div class="callout callout-default"><span><b class="text-secondary">' . $userid . '</b><br><a href="#chat' . $id . '" class="text-secondary text-decoration-none">' . $text . '</a></span></div>';
        }
    }

    function tag2ReplyButton($val, $userid, $login_id) {
        if ($val == NULL || is_null($val) || $userid == $login_id) {
            return '';
        } else {
            return 'display: none;';
        }
    }

    function tag2Style($val, $userid, $id) {
        $tagged = '<p><button class="float-right btn badge badge-secondary disabled">Firas tagged</button></p>';
        $free = '<p><button class="float-right btn badge badge-warning btnTagMessage">Tag me!</button></p>';
        if ($val == NULL || is_null($val)) { 
            return '';
        } else if (strtolower($val) == 'open') {
            return '<button class="btn badge px-1 py-1 badge-warning btnTagMessage" data-userid="' . $userid . '" data-id="' . $id . '" title="Click to tag this messsage!">Tag me!</button>';
        } else {
            return '<span class="badge px-1 py-1 badge-success" data-userid="' . $val . '" title="' . $val . ' tagged this!">' . $val . '</span>';
        }
    }

    function useridToInitial($name) {
        $hashName = md5($name);
        $hashDate = md5(date("dmY"));
        $userColor = '#' . substr($hashDate, 0, 2) . substr($hashName, 0, 4); // Warna random tina hash

        // 1. Cabut tanda '#' terus pecah jadi RGB (Hex to Decimal)
        $hex = str_replace('#', '', $userColor);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // 2. Hitung tingkat kacaangan (Luma) make rumus YIQ
        $luma = ($r * 299 + $g * 587 + $b * 114) / 1000;

        // 3. Tentukan warna teks (bodas mun poek, hideung mun caang)
        $textColor = ($luma > 192) ? '#000000' : '#FFFFFF';

        return [
            'initial' => strtoupper($name[0]) . $name[2],
            'bgColor' => $userColor,
            'textColor' => $textColor
        ];
    }

    // function checkUserInVoulenteerList($userid, $message, $remain) {
    //     // Regex pikeun néangan eusi di jero tag <li>
    //     preg_match_all('/<li>(.*?)<\/li>/i', $message, $matches);
        
    //     // $matches[1] isina array ngaran-ngaran user tina <li>
    //     $daftarAntrian = array_map('trim', $matches[1]); 

    //     if ($remain <= 0) {
    //         return [
    //             'disabled' => 'disabled',
    //             'background' => 'btn-secondary',
    //             'caption'  => '<i class="fas fa-minus-circle"></i> Kuota sudah penuh!'
    //         ];
    //     } else {
    //         if (in_array($userid, $daftarAntrian)) {
    //             return [
    //                 'disabled' => 'disabled',
    //                 'background' => 'btn-success',
    //                 'caption'  => '<i class="fas fa-check-square"></i> Sudah Ikut Antrean'
    //             ];
    //         }
            
    //         return [
    //             'disabled' => '',
    //             'background' => 'btn-primary',
    //             'caption'  => '<i class="fas fa-hand-paper"></i> Ikut Antre'
    //         ];
    //     }
    // }

    function checkUserInVoulenteerList($userid, $message, $remain) {
        preg_match_all('/<li>(.*?)<\/li>/i', $message, $matches);
        $daftarAntrian = array_map('trim', $matches[1]); 

        if (in_array($userid, $daftarAntrian)) {
            // Mun geus aya dina daptar, tampilkeun tombol BATAL
            return [
                'disabled' => '', // Ulah didisable sangkan bisa diklik
                'background' => 'btn-danger', // Warna beureum sangkan kontras
                'class' => 'btnBatalVoulenteer', // Class anyar jang JS
                'caption'  => '<i class="fas fa-times-circle"></i> Batal Ngantre'
            ];
        }

        if ($remain <= 0) {
            return [
                'disabled' => 'disabled',
                'background' => 'btn-secondary',
                'class' => 'btnSubmitVoulenteer',
                'caption'  => '<i class="fas fa-minus-circle"></i> Kuota penuh!'
            ];
        }

        return [
            'disabled' => '',
            'background' => 'btn-primary',
            'class' => 'btnSubmitVoulenteer',
            'caption'  => '<i class="fas fa-hand-paper"></i> Ikut Antre'
        ];
    }

    function checkAdminOld($message, $check, $id) {
        if ($check) {
            // Tambahkeun icon hapus (x) di jero unggal <li>
            // Pake class 'btn-hapus-antrian' jeung data-name jang nandaan ngaranna
            $message = preg_replace('/<li>(.*?)<\/li>/i', '<li>$1 <i class="fas fa-times text-danger btn-admin-delete" data-name="$1" data-id="' . $id . '" style="cursor:pointer; display:none; margin-left:5px;" title="Hapus User Ieu"></i></li>', $message);
            return $message;
        } else {
            return $message;
        }
    }

    function checkAdmin($message, $check, $id) {
        // Cek naha user teh Admin ($check == true)
        // JEUNG cek naha aya tulisan '<p>Daftar/list:</p>' dina pesenna
        if ($check && strpos($message, '<p>Daftar/list:</p>') !== false) {
            
            // Mun bener daptar antrian, kakara pasang icon (x) hapusna
            $message = preg_replace(
                '/<li>(.*?)<\/li>/i', 
                '<li>$1 <i class="fas fa-times text-danger btn-admin-delete" data-name="$1" data-id="' . $id . '" style="cursor:pointer; display:none; margin-left:5px;" title="Hapus User Ieu"></i></li>', 
                $message
            );
        }

        // Balikeun deui pesenna (nu geus aya tombolan atawa nu asli keneh)
        return $message;
    }

?>
<style type="text/css">
    #container-table-chat {
        height: 460px;
        /*min-height: 450px;*/
        display: block;
        overflow-y: auto;
        overflow-x: auto;
        background-color: rgba(236, 240, 241, 0.9);
        border-radius: 9px;
    }

    .bg-sender {
        background-color: rgba(255, 255, 240, 0.9);
    }

    .bg-receiver {
        margin-top: 4px;
        background-color: rgba(251, 251, 249, 1);
    }

    #pinnedList{
        height: 440px;
        display: block;
        overflow-y: auto;
        padding: 1px 1px 1px 1px;
    }

    .callout {
        background-color: #fff;
        border: 0 solid #e4e7ea;
        border-left: 4px solid #c8ced3;
        border-radius: .35rem;
        margin: 0.25rem 0;
        padding: .75rem .5rem;
        position: relative;
    }

    .callout h4 {
        font-size: 1.3125rem;
        margin-top: 0;
        margin-bottom: 0;
    }
   
    .callout p:last-child {
        margin-bottom: 0;
        padding: 0px;
        color: #7f7f7f;
        font-size: 0.9rem;
    }

    .callout-default {
        border-left-color: #777;
        background-color: #f5f5f5;
    }

    .callout-default h4 {
        color: #777;
    }

    .callout-warning {
        background-color: #f6f0e3;
        border-color: #faebcc;
        border-left-color: #edb100;
    }

    .callout-warning h4 {
        color: #f0ad4e;
    }

    li:hover .btn-admin-delete {
        display: inline-block !important;
    }
</style>
