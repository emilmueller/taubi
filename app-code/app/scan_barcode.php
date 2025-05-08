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

  <div class="container md-8">
    <h1 class="title">Buch erfassen</h1>

    <div class="row align-items-center">
      <div class="col-lg-1 mb-2">
          <label for="isbnInput" class="col-form-label">ISBN:</label>
      </div>  
      <div class="col-lg-7">
          <input type="text" id="isbnInput" class="form-control" placeholder="ISBN-Nummer"/>
      </div>
    </div>

    

    
    <div class="row align-items-center pt-5 mb-5">
      <div class="col-lg-1">
        
      </div>
      <div class="col-lg-7">
        <div class="row">
          <div class="col-lg-6 col-md-12">
          <button class="btn btn-secondary" id="startButton">Start</button>
          <button class="btn btn-secondary" id="resetButton">Reset</button>
          <select id="sourceSelect"></select>
            <video id="video" width="100%" style="border: 1px solid gray"></video><br/>
            
        </div>
      
          
        
        <div class="col-lg-6 col-md-12" id="sourceSelectPanel" >
          <div class="pt-5 row">
            <label>Kamerabild spiegeln</label>
          </div>
          <div class="row pt-2 align-items-center">
            <div class="col-2">
              <button id="HFlipButton" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrows" viewBox="0 0 16 16">
                  <path d="M1.146 8.354a.5.5 0 0 1 0-.708l2-2a.5.5 0 1 1 .708.708L2.707 7.5h10.586l-1.147-1.146a.5.5 0 0 1 .708-.708l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L13.293 8.5H2.707l1.147 1.146a.5.5 0 0 1-.708.708z"/>
                </svg>
              </button>
            </div>
            <div class="col-2">
              <button id="VFlipButton" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrows-vertical" viewBox="0 0 16 16">
                  <path d="M8.354 14.854a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 13.293V2.707L6.354 3.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 2.707v10.586l1.146-1.147a.5.5 0 0 1 .708.708z"/>
                </svg>
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

    document.getElementById('HFlipButton').addEventListener('click', function(){
      hgespiegelt = !hgespiegelt;
      document.getElementById('video').style.transform= hgespiegelt ? "scaleX(-1)" : "scaleX(1)";



    });

    document.getElementById('VFlipButton').addEventListener('click', function(){
      vgespiegelt = !vgespiegelt;
      document.getElementById('video').style.transform= vgespiegelt ? "scaleY(-1)" : "scaleY(1)";



    });

    function startCamera(codereader){



    }


    window.addEventListener('load', function () {
      let selectedDeviceId;
      const codeReader = new ZXing.BrowserMultiFormatReader()
      console.log('ZXing code reader initialized')
      codeReader.listVideoInputDevices()
        .then((videoInputDevices) => {
          const sourceSelect = document.getElementById('sourceSelect')
          selectedDeviceId = videoInputDevices[0].deviceId
          if (videoInputDevices.length >= 1) {
            videoInputDevices.forEach((element) => {
              const sourceOption = document.createElement('option')
              sourceOption.text = element.label
              sourceOption.value = element.deviceId
              sourceSelect.appendChild(sourceOption)
            })

            sourceSelect.onchange = () => {
              selectedDeviceId = sourceSelect.value;
              codeReader.reset()
              codeReader.decodeFromVideoDevice(selectedDeviceId, 'video', (result, err) => {
              if (result) {
                console.log(result)
                document.getElementById('isbnInput').value = result.text
                //window.open("getbook.php?isbn="+result.text);
              }
              if (err && !(err instanceof ZXing.NotFoundException)) {
                console.error(err)
                document.getElementById('isbnInput').value = err
              }
            })

            };

            const sourceSelectPanel = document.getElementById('sourceSelectPanel')
            sourceSelectPanel.style.display = 'block'
          }

          document.getElementById('startButton').addEventListener('click', () => {
            codeReader.decodeFromVideoDevice(selectedDeviceId, 'video', (result, err) => {
              if (result) {
                console.log(result)
                document.getElementById('isbnInput').value = result.text
                //window.open("getbook.php?isbn="+result.text);
              }
              if (err && !(err instanceof ZXing.NotFoundException)) {
                console.error(err)
                document.getElementById('isbnInput').value = err
              }
            })
            console.log(`Started continous decode from camera with id ${selectedDeviceId}`)
          })

          document.getElementById('resetButton').addEventListener('click', () => {
            codeReader.reset()
            document.getElementById('isbnInput').value = '';
            console.log('Reset.')
          })

        })
        .catch((err) => {
          console.error(err)
        })
    })
  </script>

</body>

</html>