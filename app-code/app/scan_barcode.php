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

    <style>
    body {
      transition: background-color 0.3s, color 0.3s;
    }

    .ribbon {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
    }

    .ribbon a {
      color: white;
      margin-right: 20px;
      text-decoration: none;
    }

    .card-deck .card {
      margin-bottom: 20px;
    }

    .book-form {
      margin: 20px 0;
    }

    #notification {
      position: fixed;
      bottom: 20px;
      left: 20px;
      z-index: 9999;
      background-color: #333;
      color: #fff;
      padding: 12px 20px;
      border-radius: 6px;
      opacity: 0;
      transition: opacity 0.5s ease;
      pointer-events: none;
    }

    #notification.show {
      opacity: 1;
    }

    #notification.success {
      background-color: #28a745;
    }

    #notification.error {
      background-color: #dc3545;
    }

    .footer{
      position: absolute;
      bottom: 0;
      width: 100%;
      height: 2.5rem;  


    }


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
      background-color: rgba(0, 0, 0, 0.5);
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
      top: 35%;
      left: 20%;
      width: 60%;
      height: 30%;
      border: 1px dashed grey;
      box-sizing: border-box;
      background-color: rgba(0, 0, 0, 0.0); /* leichte Transparenz */
    }

    


  </style>
</head>

<body id="body">
  <!-- Ribbon at the top -->
  <div class="ribbon d-flex justify-content-between align-items-center">
    <div>
      <a href="/" class="btn btn-link">Bibliothek</a>
      <a href="/account?my_books" class="btn btn-link">Meine Bücher</a>
    </div>
    <div class="d-flex align-items-center">
      <a href="/account" class="btn btn-link">
        <i class="bi bi-person-circle"></i> Konto
      </a>
    </div>
  </div>

  <div class="container">
    <h1 class="title">Buch erfassen</h1>

    <div class="row align-items-center">
      <div class="col-lg-1 col-xs-2 mb-2">
          <label for="isbnInput" class="col-form-label">ISBN:</label>
      </div>  
      <div class="col-lg-6 col-xs-8">
          <input type="text" id="isbnInput" class="form-control" placeholder="ISBN-Nummer"/>
      </div>
      <div class="col-lg-5 col-xs-2">
        <button id="okButton" class="btn btn-secondary" disabled>OK</button>
      
      </div>
    </div>

    

    
    <div class="row align-items-center pt-5 mb-5">
      <div class="col-lg-1 col-md-0">
        
      </div>
      <div class="col-lg-7">
        <div class="row">
          <div class="col-lg-8 col-md-12">
            <label for="sourceSelect">Kamera: </label>
          <select id="sourceSelect"></select>
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

    const sessionId = 'session-' + Math.random().toString(36).substring(2);
    const scanUrl = `https://taubi.jakach.ch/app/scan_barcode.php?session=${sessionId}`;

    var qrcode = new QRCode(document.getElementById('qrcode'), {
      width:150,
      height:150
    });
    qrcode.makeCode(scanUrl);

    let hgespiegelt = false;
    let vgespiegelt = false;
    let rotation = 0;

    

    

    


    

    



    $(document).ready(function(){
      
      $('#VFlipButton').on('click', function(){
        vgespiegelt = !vgespiegelt;
        $('#video').css('transform',vgespiegelt ? "scaleY(-1)" : "scaleY(1)");
      });

      $('#HFlipButton').on('click', function(){
        hgespiegelt = !hgespiegelt;
        $('#video').css('transform',hgespiegelt ? "scaleX(-1)" : "scaleX(1)");
      });

      $('#RotateButton').on('click', function(){
        rotation += 90;
        rotation = rotation%360;
        $('#video').css('transform','rotate('+rotation+'deg)');
      });

      $('#okButton').on('click', function(){
        let result = $('#isbnInput').val();
        if(result){
          window.open("getbook.php?isbn="+result);
        }else{
          alert("Keine ISBN-Nummer eingegeben");
        }
      });


      



    });

    $(window).on('load', function() {
      let selectedDeviceId;
      const codeReader = new ZXing.BrowserMultiFormatReader()
      console.log('ZXing code reader initialized')
      codeReader.listVideoInputDevices()
        .then((videoInputDevices) => {
          const sourceSelect = document.getElementById('sourceSelect');
          selectedDeviceId = videoInputDevices[0].deviceId
          if (videoInputDevices.length >= 1) {
            videoInputDevices.forEach((element) => {
              const sourceOption = document.createElement('option')
              sourceOption.text = element.label
              sourceOption.value = element.deviceId
              sourceSelect.appendChild(sourceOption)
            })


            codeReader.decodeFromVideoDevice(selectedDeviceId, 'video', (result, err) => {
              if (result) {
                console.log(result)
                $('#isbnInput').val(result.text);
                // window.open("getbook.php?isbn="+result.text);
              }
              if (err && !(err instanceof ZXing.NotFoundException)) {
                console.error(err)
                $('#isbnInput').val(err);
              }
            })
            console.log(`Started continous decode from camera with id ${selectedDeviceId}`)


            sourceSelect.onchange = () => {
              selectedDeviceId = sourceSelect.value;
              codeReader.reset()
              codeReader.decodeFromVideoDevice(selectedDeviceId, 'video', (result, err) => {
              if (result) {
                console.log(result)
                $('#isbnInput').val(result.text);
                // window.open("getbook.php?isbn="+result.text);
              }
              if (err && !(err instanceof ZXing.NotFoundException)) {
                console.error(err)
                $('#isbnInput').val(err);
              }
            })

            };

            $('#sourceSelectPanel').css('display','block');
            
          }

          

          // document.getElementById('resetButton').addEventListener('click', () => {
          //   codeReader.reset()
          //   document.getElementById('isbnInput').value = '';
          //   console.log('Reset.')
          // })

        })
        .catch((err) => {
          console.error(err)
        })




    })
  </script>

</body>

</html>