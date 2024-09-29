<?php

function sendToSlackAPI($token, $message, $thread = null, $room, $username, $icon = ":robot_face:") {
    // Sanitize and format the message
    $message = trim(str_replace(["\r\n", "\r", "\n", '"'], ["\\n", "\\n", "\\n", '\"'], $message));

    // Prepare the data payload
    $data = [
        "channel"    => "#{$room}",
        "text"       => $message,
        "username"   => $username,
        "icon_emoji" => $icon,
    ];

    // Include thread timestamp if provided
    if (!empty($thread)) {
        $data['thread_ts'] = $thread;
    }

    // Initialize cURL and set options
    $ch = curl_init("https://slack.com/api/chat.postMessage");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token,
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute the request and capture the response
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return "cURL Error: $error_msg";
    }

    // Close cURL and return the result
    curl_close($ch);
    return $result;
}