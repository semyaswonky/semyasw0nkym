<?php
header('Content-Type: application/json; charset=utf-8');

// ===== НАСТРОЙКИ =====
$to = "maksimsereda188@gmail.com";
$subject = "Новая заявка в семью Sw0nkym";

// ===== ПОЛУЧАЕМ ДАННЫЕ =====
$nick      = trim($_POST['nick'] ?? '');
$age       = trim($_POST['age'] ?? '');
$discord   = trim($_POST['discord'] ?? 'не указан');
$region    = trim($_POST['region'] ?? '');
$frac_stat = trim($_POST['fraction_status'] ?? '');
$fraction  = trim($_POST['fraction'] ?? 'не указана');
$about     = trim($_POST['about'] ?? '');

// ===== ВАЛИДАЦИЯ =====
if($nick === '' || $age === '' || $region === '' || $frac_stat === '' || $about === ''){
    echo json_encode(['success' => false, 'error' => 'Заполните все обязательные поля']);
    exit;
}

// ===== ФОРМИРУЕМ ПИСЬМО =====
$boundary = md5(uniqid(time()));

$body  = "--$boundary\r\n";
$body .= "Content-Type: text/plain; charset=UTF-8\r\n";
$body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";

$body .= "═══════════════════════════════════\r\n";
$body .= "   ЗАЯВКА В СЕМЬЮ Sw0nkym\r\n";
$body .= "═══════════════════════════════════\r\n\r\n";
$body .= "Ник в игре:      $nick\r\n";
$body .= "Возраст:         $age\r\n";
$body .= "Discord:         $discord\r\n";
$body .= "Область работы:  $region\r\n";
$body .= "Во фракции:      $frac_stat\r\n";
$body .= "Фракция:         $fraction\r\n\r\n";
$body .= "─── О себе ───────────────────────\r\n";
$body .= "$about\r\n\r\n";
$body .= "═══════════════════════════════════\r\n";
$body .= "Дата: " . date('d.m.Y H:i:s') . "\r\n";
$body .= "IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "\r\n";
$body .= "═══════════════════════════════════\r\n";

// HTML-версия
$html_body  = "--$boundary\r\n";
$html_body .= "Content-Type: text/html; charset=UTF-8\r\n";
$html_body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";

$html_body .= '<div style="font-family:monospace;max-width:600px;margin:0 auto;background:#0a0a12;border:1px solid #222;border-radius:16px;overflow:hidden">';
$html_body .= '<div style="background:linear-gradient(135deg,#7c3aed,#ec4899);padding:20px 28px;text-align:center">';
$html_body .= '<h2 style="color:#fff;margin:0;font-family:sans-serif;letter-spacing:2px">ЗАЯВКА — Sw0nkym</h2></div>';
$html_body .= '<div style="padding:28px">';
$html_body .= '<table style="width:100%;border-collapse:collapse;font-family:sans-serif;font-size:15px">';
$html_body .= '<tr><td style="padding:10px 0;color:#888;border-bottom:1px solid #222;width:40%">Ник в игре</td><td style="padding:10px 0;color:#fff;border-bottom:1px solid #222;font-weight:600">'.htmlspecialchars($nick).'</td></tr>';
$html_body .= '<tr><td style="padding:10px 0;color:#888;border-bottom:1px solid #222">Возраст</td><td style="padding:10px 0;color:#fff;border-bottom:1px solid #222;font-weight:600">'.htmlspecialchars($age).'</td></tr>';
$html_body .= '<tr><td style="padding:10px 0;color:#888;border-bottom:1px solid #222">Discord</td><td style="padding:10px 0;color:#fff;border-bottom:1px solid #222;font-weight:600">'.htmlspecialchars($discord).'</td></tr>';
$html_body .= '<tr><td style="padding:10px 0;color:#888;border-bottom:1px solid #222">Область работы</td><td style="padding:10px 0;color:#fff;border-bottom:1px solid #222;font-weight:600">'.htmlspecialchars($region).'</td></tr>';
$html_body .= '<tr><td style="padding:10px 0;color:#888;border-bottom:1px solid #222">Во фракции</td><td style="padding:10px 0;color:#fff;border-bottom:1px solid #222;font-weight:600">'.htmlspecialchars($frac_stat).'</td></tr>';
$html_body .= '<tr><td style="padding:10px 0;color:#888">Фракция</td><td style="padding:10px 0;color:#fff;font-weight:600">'.htmlspecialchars($fraction).'</td></tr>';
$html_body .= '</table>';
$html_body .= '<div style="margin-top:20px;padding:16px;background:#11111a;border-radius:10px;border:1px solid #222">';
$html_body .= '<div style="color:#888;font-size:13px;margin-bottom:8px;text-transform:uppercase;letter-spacing:1px">О себе</div>';
$html_body .= '<div style="color:#ddd;line-height:1.6;white-space:pre-wrap">'.htmlspecialchars($about).'</div>';
$html_body .= '</div>';
$html_body .= '<div style="margin-top:20px;padding-top:16px;border-top:1px solid #222;color:#555;font-size:13px">';
$html_body .= 'Дата: '.date('d.m.Y H:i:s').' · IP: '.($_SERVER['REMOTE_ADDR'] ?? 'unknown');
$html_body .= '</div>';
$html_body .= '</div></div>';
$html_body .= "\r\n--$boundary--\r\n";

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/alternative; boundary=\"$boundary\"\r\n";
$headers .= "From: Sw0nkym Family <no-reply@sw0nkym.ru>\r\n";
$headers .= "Reply-To: no-reply@sw0nkym.ru\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

// Отправляем
$full_body = $body . $html_body;
$sent = @mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $full_body, $headers);

if($sent){
    echo json_encode(['success' => true]);
}else{
    echo json_encode(['success' => false, 'error' => 'mail() failed']);
}
?>