<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Zoom Meeting Invitation</title>

  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 8px;
      background-color: #f9f9f9;
    }

    p {
      margin: 10px 0;
    }

    a {
      text-decoration: none;
    }

    .button {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #ffffff;
      color: #ffffff;
      border-radius: 4px;
      border: 3px solid #007bff;
    }
  </style>
</head>

<body>
  <div class="container">
    <div style="margin-bottom: 40px">
      <img src="{{ asset('./assets/compiled/png/logo.png') }}" style="width: 180px; height: auto;" alt="Logo">
    </div>

    <div style="margin-bottom: 20px">
      <strong>
        <p>Halo {{$penerima}},</p>
      </strong>
      <p>Anda telah dijadwalkan untuk mengikuti sesi konsultasi dengan informasi sebagai berikut:</p>
      <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <tr>
          <td style="width: 20%; font-weight: bold">Topik</td>
          <td>: {{$topik}}</td>
        </tr>
        <tr>
          <td style="width: 20%; font-weight: bold">Tanggal</td>
          <td>: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</td>
        </tr>
        <tr>
          <td style="width: 20%; font-weight: bold">Waktu</td>
          <td>: {{$waktu}}</td>
        </tr>
        <tr>
          <td style="width: 20%; font-weight: bold">Durasi</td>
          <td>: {{$durasi}}</td>
        </tr>
      </table>
    </div>

    <div>
      <p>Silakan gunakan tautan di bawah ini untuk bergabung dalam sesi konsultasi:</p>
      <div style="margin-bottom: 20px">
        <a href="{{ $zoomLink }}" class="button" target="_blank">Bergabung di Zoom</a>
      </div>
      <p>Atau salin tautan berikut ke browser Anda:</p>
      <div style="margin-bottom: 20px">
        <a href="{{ $zoomLink }}">{{ $zoomLink }}</a>
      </div>
      <div style="margin-top: 30px">
        <p>Terima kasih,</p>
        <p><strong>Tim Konsultasi Actions.id</strong></p>
      </div>
    </div>
  </div>
</body>

</html>
