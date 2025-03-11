<?php
$firstName = 'Дмитрий';
$lastName = 'Афельчонок';
$email = 'ДмитрийАфельчонок@gmail.com';

$message = "Имя: " . $firstName . "\n";
$message .= "Фамилия: " . $lastName . "\n";
$message .= "Email: " . $email . "\n";

echo "==============\n";
echo "Тема письма: MY TEST EMAIL\n";
echo "==============\n";
echo $message;
echo "==============\n";

$to = 'd.y.afelchonok@student.khai.edu';
$subject = 'MY TEST EMAIL';
$headers = 'From: AfelchonokDmytro.528st.SMTP@gmail.com';

mail($to, $subject, $message, $headers);
?>