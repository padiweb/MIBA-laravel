<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Kartu Siswa</title>
<style type="text/css">
  body { font-family: sans-serif; margin: 0; }
  @page { margin: 0.5cm 0.3cm; }
  .grid { width: 100%; }
  .card {
    display: inline-block;
    width: 48%;
    vertical-align: top;
    border: 1px solid #000;
    padding: 6px 8px;
    margin: 0 0.5% 8px 0.5%;
    box-sizing: border-box;
  }
  .school   { font-size: 9pt; font-weight: bold; text-align: center; margin: 0; }
  .address  { font-size: 5pt; text-align: center; margin: 0; }
  .phone    { font-size: 5pt; text-align: center; font-style: italic; margin: 0 0 3px 0; }
  hr { border: none; height: 1px; background-color: #333; margin: 2px 0; }
  .container { position: relative; min-height: 70px; }
  .topright { position: absolute; top: 0; right: 0; }
  .photo { height: 50px; width: 50px; border: 1px solid #000; object-fit: cover; }
  .info-table { font-size: 5.5pt; margin-left: 40px; border-collapse: collapse; }
  .info-table td { padding: 0 2px; vertical-align: top; }
  .barcode-wrap { margin-top: 4px; }
  .date-label { font-size: 5pt; margin-left: 25%; margin-top: 0; }
</style>
</head>
<body>
<div class="grid">
@foreach($students as $student)
  <div class="card">
    <p class="school">{{ $setting['school'] ?? '' }}</p>
    <p class="address">{{ $setting['address'] ?? '' }} - {{ $setting['district'] ?? '' }} - {{ $setting['city'] ?? '' }}</p>
    <p class="phone">Telp. {{ $setting['phone'] ?? '' }}</p>
    <hr>
    <div class="container">
      <div class="topright">
        @if($student->student_img)
          <img src="{{ public_path('uploads/student/'.$student->student_img) }}" class="photo">
        @else
          <img src="{{ public_path('media/img/user.png') }}" class="photo">
        @endif
      </div>
      <table class="info-table">
        <tr><td>NIM</td><td>:</td><td>{{ $student->student_nis }}</td></tr>
        <tr><td>Nama</td><td>:</td><td>{{ $student->student_full_name }}</td></tr>
        <tr><td>Tempat, Tanggal Lahir</td><td>:</td><td>{{ $student->student_born_place }}, {{ $student->student_born_date ? \Carbon\Carbon::parse($student->student_born_date)->locale('id')->isoFormat('D MMMM Y') : '' }}</td></tr>
        <tr><td>Kelas</td><td>:</td><td>{{ $student->class->class_name ?? '' }}</td></tr>
        @if(($app_level ?? '')=='senior')
        <tr><td>Unit Sekolah</td><td>:</td><td>{{ $student->majors->majors_name ?? '' }}</td></tr>
        @endif
      </table>
      <div class="barcode-wrap">
        {!! \App\Helpers\Barcode::code39Svg($student->student_nis, 28, 1, true) !!}
      </div>
      <p class="date-label">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MM Y') }}</p>
    </div>
  </div>
@endforeach
</div>
</body>
</html>
