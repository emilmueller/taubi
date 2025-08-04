<?php
session_start();

include '../api/login_check.php';

?>
<!doctype html>
<html data-bs-theme="dark"  lang="de">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="ZXing for JS">

  <title>Buch erfassen</title>

  <!-- <link rel="stylesheet" rel="preload" as="style" onload="this.rel='stylesheet';this.onload=null"
    href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
  <link rel="stylesheet" rel="preload" as="style" onload="this.rel='stylesheet';this.onload=null"
    href="https://unpkg.com/normalize.css@8.0.0/normalize.css">
  <link rel="stylesheet" rel="preload" as="style" onload="this.rel='stylesheet';this.onload=null"
    href="https://unpkg.com/milligram@1.3.0/dist/milligram.min.css"> -->
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script type="text/javascript" src="/js/qrcode.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="../css/taubi.css" rel="stylesheet">

    <style>
    


    .video-container {
      position: relative;
      width: 100%;
      aspect-ratio: 1 / 1;
    }

    video {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none; /* Overlay klickt nicht in Video rein */
    }

    /* Der transparente Bereich (z. B. 40% in der Mitte) */
    .mask {
      position: absolute;
      background-color: rgba(0, 0, 0, 0.6);
    }

    /* Oben */
    .mask.top {
      top: 0;
      left: 0;
      width: 100%;
      height: 35%;
    }

    /* Unten */
    .mask.bottom {
      bottom: 0;
      left: 0;
      width: 100%;
      height: 35%;
    }

    /* Links */
    .mask.left {
      top: 35%;
      left: 0;
      width: 20%;
      height: 30%;
    }

    /* Rechts */
    .mask.right {
      top: 35%;
      right: 0;
      width: 20%;
      height: 30%;
    }


    .target-box {
      position: absolute;
      top: 34%;
      left: 19%;
      width: 62%;
      height: 32%;
      border: 1px dotted white;
      box-sizing: border-box;
      background-color: rgba(0, 0, 0, 0.0); /* leichte Transparenz */
    }

    


  </style>
</head>

<body id="body">
  <?php include 'nav.php'; ?>

  <div class="container">
    <h1 class="title">Buch erfassen</h1>
    

    <div class="row align-items-center">
      <div class="col-lg-1 col-2 mb-2">
          <label for="isbnInput" class="col-form-label">ISBN:</label>
      </div>  
      <div class="col-lg-6 col-6">
          <input type="text" id="isbnInput" class="form-control" placeholder="ISBN-Nummer"/>
          <!-- <div class="invalid-feedback">Keine gültige ISBN-Nummer!</div> -->
      </div>
      <div class="col-lg-5 col-4">
        <button id="okButton" class="btn btn-success" disabled title="Buch suchen">
          <span class="bi bi-search" ></span>
        </button>
        <button id="backButton" class="btn btn-danger" onclick="history.back();" title="Zurück">
          <span class="bi bi-x-square" ></span>
        </button>
        
      
      </div>
    </div>

    

    
    <div class="row align-items-center pt-5 mb-5">
      <div class="col-lg-1 col-md-0">
        
      </div>
      <div class="col-lg-7">
        <div class="row">
          <div class="col-lg-8 col-md-12">
            <label for="sourceSelect">Kamera: </label>
          <select class="form-select" id="sourceSelect"></select>
          <div class="video-container">
          <video id="video" style="border: 1px solid gray"></video>
            <div class="overlay">
              <div class="mask top"></div>
              <div class="mask bottom"></div>
              <div class="mask left"></div>
              <div class="mask right"></div>
              <div class="target-box"></div>
            </div>
          </div>  
        </div>
      
          
        
        <div class="col-lg-4 col-md-12" id="sourceSelectPanel" >
          <div class="pt-3 row">
            <label>Kamerabild drehen</label>
          </div>
          <div class="row pt-2 align-items-center">
            <div class="col-3">
                <button id="RotateButton" class="btn btn-secondary bi bi-arrow-clockwise">
                
                </button>
            </div>
            <div class="col-3">
              <button id="HFlipButton" class="btn btn-secondary bi bi-arrows">
               
              </button>
            </div>
            <div class="col-3">
              <button id="VFlipButton" class="btn btn-secondary bi bi-arrows-vertical">
               
              </button>
            </div>
          </div>
          <div class="row pt-5 d-none d-md-block">
            <div class="col">
              Wechsle aufs Handy
            </div>
            <div class="col">
              <div id="qrcode"></div>
            </div>
        
          </div>
        </div>
      </div>
    </div>
  </div>

    

      
   

    <footer class="footer">
      <section class="container">
        <p>Licensed under the <a target="_blank"
            href="https://github.com/zxing-js/library#license" title="MIT">MIT</a>.</p>
      </section>
    </footer>
  </div>



  <script type="text/javascript" src="https://unpkg.com/@zxing/library@latest/umd/index.min.js"></script>
  <script type="text/javascript">
    let token;
    fetch('../api/set_temp_token.php')
      .then(response => response.json())
      .then(result =>{
        if(result.success){
          const scanUrl = `https://taubi.code-camp.ch/api/scan_barcode_remote.php?token=${result.token}`;
          token = result.token;

          //Wieder wegmachen im Prod
          console.log(scanUrl);
          var qrcode = new QRCode(document.getElementById('qrcode'), {
          width:150,
          height:150,
          correctLevel : QRCode.CorrectLevel.H
        });
        qrcode.makeCode(scanUrl);

        }
      })


      setInterval(() =>{
        fetch('../api/get_scan.php', {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                token: token
            })
        })
        .then(response => response.json())
        .then(res => {
            if (res.success){
              //console.log(res.isbn);
              fetch('../api/delete_temp_token.php?token='+token)
              .then(response => response.json())
              .then(answer => {
                if(answer.success){
                  window.location.href = `getbook.php?book_id=${res.isbn}&action=isbn_search`;
                }else{
                  window.location.href = `../app/error_page.php?message=Das Scannen hat nicht funktioniert. Versuch es noch einmal.&redirect=/app`;
                }
              })
              
            }     
        })
      }, 2000);

    

    

    
    function checkISBN(n){
      n=n.replaceAll('-','');
      
      let s =0;
      let ci="";
      let xi=0;
      // if (n.length==0){
      //   return true;
      // }else 
      
      if(n.length == 10){
        for (let i =0; i<n.length;i++){
          ci=n.charAt(i);
          if(ci=="x" || ci=="X"){
            xi=10;
          }else{
            xi=parseInt(ci);
          }

          s+= (10-i)*xi;
        }
        return s%11==0;


      }else if(n.length ==13){
        for (let i=0;i<n.length;i++){
          xi=n.charAt(i);
          s+=(1+(i%2)*2)*xi;

        }
        return s%10==0;


      }else{
        return false;
      }
    }
    

    
    document.addEventListener('DOMContentLoaded', function () {
      let vgespiegelt = false;
      let hgespiegelt = false;
      let rotation = 0;

      const video = document.getElementById('video');
      const isbnInput = document.getElementById('isbnInput');
      const okButton = document.getElementById('okButton');
      const sourceSelectPanel = document.getElementById('sourceSelectPanel');
      const sourceSelect = document.getElementById('sourceSelect');

      document.getElementById('VFlipButton').addEventListener('click', function () {
        vgespiegelt = !vgespiegelt;
        video.style.transform = vgespiegelt ? "scaleY(-1)" : "scaleY(1)";
      });

      document.getElementById('HFlipButton').addEventListener('click', function () {
        hgespiegelt = !hgespiegelt;
        video.style.transform = hgespiegelt ? "scaleX(-1)" : "scaleX(1)";
      });

      document.getElementById('RotateButton').addEventListener('click', function () {
        rotation = (rotation + 90) % 360;
        video.style.transform = `rotate(${rotation}deg)`;
      });

      okButton.addEventListener('click', function () {
        const result = isbnInput.value;
        if (result) {
          window.location.href = `getbook.php?book_id=${result}&action=isbn_search`;
        } else {
          alert("Keine ISBN-Nummer eingegeben");
        }
      });

      isbnInput.addEventListener('input', function () {
        const value = isbnInput.value;
        if (!checkISBN(value)) {
          isbnInput.classList.add('is-invalid');
          isbnInput.classList.remove('is-valid');
          okButton.classList.add('btn-secondary');
          okButton.classList.remove('btn-success');
          okButton.disabled = true;
        } else {
          isbnInput.classList.remove('is-invalid');
          isbnInput.classList.add('is-valid');
          okButton.classList.remove('btn-secondary');
          okButton.classList.add('btn-success');
          okButton.disabled = false;
        }
      });
    });

    window.addEventListener('load', function () {
      let selectedDeviceId;
      const codeReader = new ZXing.BrowserMultiFormatReader();
      const video = document.getElementById('video');
      console.log('ZXing code reader initialized');

      codeReader.listVideoInputDevices()
        .then((videoInputDevices) => {
          selectedDeviceId = videoInputDevices[0]?.deviceId;

          if (videoInputDevices.length >= 1) {
            videoInputDevices.forEach((element) => {
              const sourceOption = document.createElement('option');
              sourceOption.text = element.label;
              sourceOption.value = element.deviceId;
              sourceSelect.appendChild(sourceOption);
            });

            const handleScanResult = (result, err) => {
              if (result) {
                console.log(result);
                isbnInput.value = result.text;
                if (checkISBN(result.text)) {
                  isbnInput.classList.remove('is-invalid');
                  isbnInput.classList.add('is-valid');
                  okButton.classList.remove('btn-secondary');
                  okButton.classList.add('btn-success');
                  okButton.disabled = false;

                  //kamera deaktivieren, Canvas grau machen.
                  codeReader.reset();
                  //video.classList.add('inactive');

                } else {
                  isbnInput.classList.add('is-invalid');
                  isbnInput.classList.remove('is-valid');
                  okButton.classList.add('btn-secondary');
                  okButton.classList.remove('btn-success');
                  okButton.disabled = true;
                }
              }
              if (err && !(err instanceof ZXing.NotFoundException)) {
                console.error(err);
                isbnInput.value = err;
              }
            };

            codeReader.decodeFromVideoDevice(selectedDeviceId, 'video', handleScanResult);

            console.log(`Started continuous decode from camera with id ${selectedDeviceId}`);

            sourceSelect.onchange = () => {
              selectedDeviceId = sourceSelect.value;
              codeReader.reset();
              codeReader.decodeFromVideoDevice(selectedDeviceId, 'video', handleScanResult);
            };

            sourceSelectPanel.style.display = 'block';
          }
        })
        .catch((err) => {
          console.error(err);
        });
    });


    

    



    
  </script>

</body>

</html>