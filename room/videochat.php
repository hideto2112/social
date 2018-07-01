<?php
$room_id=$_GET["room_id"];
$position=$_GET["position"];
?>

<html>
<head>
  <meta charset="utf-8">
  <title>教室</title>
  <link rel="stylesheet" href="style.css">
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="text/javascript" src="//cdn.webrtc.ecl.ntt.com/skyway-latest.js"></script>
  <script type="text/javascript" src="script.js"></script>
</head>

<body>

<input type="hidden" id="position" value="<?= $position ?>">

<div class="pure-g">

  <!-- Video area -->
  <div class="pure-u-2-3" id="video-container">
    <div id="their-videos"></div>
    <video id="my-video" muted="true" autoplay playsinline></video>
  </div>

  <!-- Steps -->
  <div class="pure-u-1-3">
    <div id="settings">
      <h2>デバイスを選択してください</h2>
      <div class="select">
        <label for="audioSource">オーディオ: </label><select id="audioSource"></select>
      </div>      
      <div class="select">
        <label for="videoSource">カメラ: </label><select id="videoSource"></select>
      </div>
    </div>

    <!-- Get local audio/video stream -->
    <div id="step1">
      <p>Please click `allow` on the top of the screen so we can access your webcam and microphone for calls.</p>
      <div id="step1-error">
        <p>Failed to access the webcam and microphone. Make sure to run this demo on an http server and click allow when asked for permission by the browser.</p>
        <a href="#" class="pure-button pure-button-error" id="step1-retry">再試行</a>
      </div>
    </div>

    <!-- <p>Your id: <span id="my-id">...</span></p> -->
    <!-- Make calls to others -->
    <div id="step2">
      <h3>入室しますか？</h3>
      <form id="make-call" class="pure-form">
        <input type="hidden" placeholder="Join room..." id="join-room" value="<?= $room_id ?>">
        <!-- <input type="text" placeholder="Join room..." id="join-room"> -->
        <button class="pure-button pure-button-success" type="submit">入室する</button>
      </form>
      <!-- <p><strong>Warning:</strong> You may connect with people you don't know if you both use the same room name.</p>
      <p><strong>注意：</strong>同じルーム名を使用した場合、知らない人と接続する可能性があります。</p> -->
    </div>

    <!-- Call in progress -->
    <div id="step3">
      <p>入室中です</p>
      <!-- <p>Currently in room <span id="room-id">...</span></p> -->
      <p><a href="#" class="pure-button pure-button-error" id="end-call">退室する</a></p>
    </div>
  </div>
</div>


</body>
</html>
