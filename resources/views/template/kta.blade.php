<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kartu Tanda Anggota</title>
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      background: #f0f0f0;
      font-family: Arial, sans-serif;
    }
    .card {
      width: 410px;
      height: 230px;
      border: 3px solid black;
      box-sizing: border-box;
      position: relative;
      background-image: url('{{ asset('image/bg-kta.png') }}');
      /* background-repeat: no-repeat; */
      background-size: cover;
      background-position: center;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding-left: 120px; /* beri ruang untuk foto di kiri */
    }
    .photo-box {
      position: absolute;
      left: 20px;
      top: 65px;
      width: 90px;
      height: 90px;
      background: white;
      box-sizing: border-box;
    }
    .text-top {
      color: white;
      font-weight: bold;
      font-size: 16px;
      line-height: 1.3;
      margin-bottom: 5px;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.7);
    }
    .text-middle {
      color: white;
      font-weight: bold;
      font-size: 18px;
      line-height: 1.3;
      margin-bottom: 0;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.7);
    }
    .text-bottom {
      color: red;
      font-weight: bold;
      font-size: 18px;
      line-height: 1.4;
      margin-top: 10px;
      text-align: left;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="photo-box"><img src="{{ asset('storage/' . $biodata->pas_foto_url) }}" alt="img" width="100%" height="100px"></div>
    <div>
      <div class="text-top">{{ $biodata->no_kta }}</div>
      <div class="text-middle">{{ $biodata->nama_lengkap }}</div>
      <div class="text-bottom">
        {{ $jurusan }}<br />
        Paskibra
      </div>
    </div>
  </div>
</body>
</html>
