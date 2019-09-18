<?php
require "../vendor/autoload.php";

/* ID da lista criada no mailchimp - ID da lista (Testedigilex): b0f946b9aa */
const LIST_ID = '4e5e81565e';

/* KEY da API do mailchimp - KEY da API (Testedigilex): eb723a1add51acc25e97c94a18ce333b-us4 */
const API_KEY = '196d17a1565ab9dff8fa600120fad17c-us4';

/* Aqui colocar aqui o custom template - Se tiver em outra página pego aqui com $_POST['name_of_field'] */
const HTML = "<!DOCTYPE html>
                <html>
                <head>
                    <meta charset=\"UTF-8\"/>
                    <title>Document</title>
                </head>
                <body>
                    Hello!
                </body>
                </html>";
/* Esse email tem que ser o mesmo da conta testedigilex@gmail.com */
const REPLAY_TO = 'lucianoalbis@yahoo.com.br';

/* Nome da campanha */
const FROM_NAME = 'Campanha';

/* Título do email */
const NEWSLETTER_SUBJECT_LINE = 'Campanha contra a fome';

use \DrewM\MailChimp\MailChimp;

$MailChimp = new MailChimp(API_KEY);

# Add user to list
$result = $MailChimp->post("lists/".LIST_ID."/members", [
    'email_address' => rand().'-eml@uol.com.br',
    'status'        => 'subscribed'
]);
//print_r($result);

# Get List
$result = $MailChimp->get("lists/".LIST_ID."/members");
//print_r($result);

# Create template
$result = $MailChimp->post("templates/", [
    'name' => 'Template 1',
    'html'        => HTML,
]);
$response = $MailChimp->getLastResponse();
$responseObj = json_decode($response['body']);
$template_id = $responseObj->id;
//print_r($result);

# Create Campaigns
$result = $MailChimp->post("campaigns", [
    'type' => 'regular',
    'recipients' => ['list_id' => LIST_ID],
    'settings' => ['subject_line' => NEWSLETTER_SUBJECT_LINE,
        'reply_to' => REPLAY_TO,
        'from_name' => FROM_NAME
    ]
]);
$response = $MailChimp->getLastResponse();
$responseObj = json_decode($response['body']);
$campaigns_id = $responseObj->id;
//print_r($result);

# Manage Campaign Content
$result = $MailChimp->put('campaigns/' . $campaigns_id . '/content', [
    'template' => ['id' => $template_id,
        'sections' => ['body' => HTML]
    ]
]);
//print_r($result);

# Send Campaign
$result = $MailChimp->post('campaigns/' . $campaigns_id . '/actions/send');
//print_r($result);
