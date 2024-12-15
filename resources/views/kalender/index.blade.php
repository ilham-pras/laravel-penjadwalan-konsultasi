@extends('layouts.app')

@section('title', 'Kalender Penjadwalan')

@section('css')
  {{-- Flatpicker --}}
  <link href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css" rel="stylesheet" type="text/css">
  <style>
    #calendar a {
      color: #000000;
      text-decoration: none;
    }

    .mr-auto {
      margin-right: auto;
    }
  </style>
@endsection

@section('content')
  @if (auth()->user()->role === 'admin')
    @include('layouts.sidebar')
  @endif

  <div id="main" class="layout-horizontal">
    @if (auth()->user()->role === 'admin')
      <header class="mb-4">
        <nav class="navbar navbar-expand navbar-light navbar-top">
          <div class="container-fluid">
            <a href="#" class="burger-btn d-block">
              <i class="bi bi-justify fs-3"></i>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav ms-auto mb-lg-0"></ul>

              <div class="dropdown">
                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                  <div class="user-menu d-flex">
                    <div class="user-name text-end me-2">
                      <h6 class="mb-0 text-gray-600">{{ auth()->user()->name }}</h6>
                      <p class="mb-0 text-sm text-gray-600">
                        @if (auth()->user()->role === 'admin')
                          Administrator
                        @elseif (auth()->user()->role === 'user')
                          Member
                        @endif
                      </p>
                    </div>
                    <div class="user-img d-flex align-items-center dropdown-toggle">
                      <div class="avatar avatar-md">
                        <img src="{{ asset('./assets/compiled/png/profile-picture.png') }}" alt="Profile Picture">
                      </div>
                    </div>
                  </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="min-width: 11rem">
                  <li>
                    <h6 class="dropdown-header">Hello, {{ auth()->user()->name }}!</h6>
                  </li>
                  <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="icon-mid bi bi-person me-2"></i>Profile</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                      onclick="event.preventDefault();
                		  document.getElementById('logout-form').submit();">
                      <i class="icon-mid bi bi-box-arrow-left me-2"></i>
                      Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                      @csrf
                    </form>
                  </li>
                </ul>
              </div>
            </div>

          </div>
        </nav>
      </header>
    @elseif (auth()->user()->role === 'user')
      @include('layouts.navigation')
    @endif

    <div class="content-wrapper container">
      <div class="page-heading">
        <h3>Kalender Penjadwalan</h3>
      </div>
      <div class="page-content">
        <section class="row">
          <div class="col">
            <div class="alert alert-primary">
              <p>Note:</p>
              <p>Silahkan klik kalender jika ingin membuat janji temu.</p>
            </div>

            @include('kalender.jam-operasional')
            @include('kalender.modal')

            <div class="card bg-white">
              <div class="card-header bg-white">
                <h4>Kalender Jadwal Konsultasi</h4>
              </div>
              <div class="card-body">
                {{-- Fullcalendar --}}
                <div id="calendar"></div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>

    <footer>
      <div class="container">
        <div class="footer clearfix mb-0 text-muted">
          <div class="float-start">
            <p>Copyright &copy; 2024 by Mazer</p>
          </div>
          <div class="float-end">
            <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a href="https://saugi.me">Saugi</a></p>
          </div>
        </div>
      </div>
    </footer>
  </div>
@endsection

@section('script')
  {{-- Moment & Fullcalendar --}}
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/id.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

  <script>
    var booking = @json($events);
    var jamOperasional = @json($jamOperasional);
    var currentUserId = '{{ auth()->user()->id }}';
    var currentUserRole = "{{ auth()->user()->role }}";
    moment.locale('id');
    var calendar = null;

    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
      calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'id',
        initialView: 'dayGridMonth',
        firstDay: 1,
        initialDate: new Date(),
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        eventTimeFormat: {
          hour: '2-digit',
          minute: '2-digit',
          meridiem: false, // Menghilangkan AM/PM
          hour12: false // Format 24 jam
        },
        dayHeaderFormat: {
          weekday: 'long'
        },
        height: 900,
        events: booking.map(function(event) {
          return {
            id: event.id,
            user_id: event.user_id,
            start: event.start,
            end: event.end,
            title: event.title,
            nama_lengkap: event.nama_lengkap,
            perusahaan: event.perusahaan,
            jenis_konsultasi: event.jenis_konsultasi,
            durasi_konsultasi: event.durasi_konsultasi,
            deskripsi: event.deskripsi,
            zoom_link: event.zoom_link,
            backgroundColor: (event.user_id == currentUserId) ? '#198754' : '#435ebe',
            borderColor: (event.user_id == currentUserId) ? '#198754' : '#435ebe',
            display: 'block' // Menampilkan event dalam blok penuh, bukan dot
          };
          console.log(booking);
        }),
        dayMaxEvents: true,
        nowIndicator: true,
        selectable: true,
        dateClick: function(info) {
          var formattedDate = new Date(info.dateStr).toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: '2-digit'
          });
          $('#scheduleModalLabel').text('Jadwal Konsultasi Tanggal ' + formattedDate);

          // Menampilkan jumlah jadwal konsultasi yang ada pada tanggal yang dipilih
          const clickedDate = moment(info.date).format('YYYY-MM-DD');
          var filteredEvents = calendar.getEvents().filter(event => {
            return moment(event.start).format('YYYY-MM-DD') === clickedDate;
          });

          // Menampilkan jadwal semua pengguna
          var allTableBody = '';
          if (filteredEvents.length > 0) {
            filteredEvents.forEach((item, index) => {
              allTableBody += `
                <tr>
                  <td class="align-middle text-center">${index + 1}</td>
                  <td class="align-middle">${item.title}</td>
                  <td class="align-middle">
                    ${moment(item.start).format('D MMMM YYYY')}
                  </td>
                  <td class="align-middle">
                    ${moment(item.start).format('HH:mm')} - ${moment(item.end).format('HH:mm')}
                  </td>
                </tr>`;
            });
          } else {
            allTableBody = `<tr><td colspan="4" class="text-center py-3">-- Tidak Ada Jadwal Konsultasi --</td></tr>`;
          }
          $('#allEvent').html(allTableBody);

          // Menampilkan jadwal pengguna yang sekarang login
          var myTableBody = '';
          var userEvents = filteredEvents.filter(item => item.extendedProps.user_id == currentUserId);

          if (userEvents.length > 0) {
            userEvents.forEach((item, index) => {
              myTableBody += `
                <tr>
                  <td class="align-middle text-center">${index + 1}</td>
                  <td class="align-middle">${item.extendedProps.nama_lengkap}</td>
                  <td class="align-middle">${item.extendedProps.perusahaan}</td>
                  <td class="align-middle">${item.extendedProps.jenis_konsultasi}</td>
                  <td class="align-middle">
                    ${moment(item.start).format('D MMMM YYYY')}
                  </td>
                  <td class="align-middle">
                    ${moment(item.start).format('HH:mm')} - ${moment(item.end).format('HH:mm')}
                  </td>
                  <td class="align-middle text-center text-primary">
                    ${item.extendedProps.zoom_link ? `<a href="${item.extendedProps.zoom_link}" class="text-decoration-none" target="_blank">Join Zoom Meeting</a>` : 'Tidak ada'}
                  </td>
                </tr>`;
            });
          } else {
            myTableBody = `<tr><td colspan="7" class="text-center py-3">-- Anda Tidak Memiliki Jadwal Konsultasi --</td></tr>`;
          }
          $('#myEvent').html(myTableBody);

          // Cari jam operasional yang sesuai dengan tanggal yang dipilih
          var operasional = jamOperasional.find(op => {
            return clickedDate >= op.tanggal_mulai && clickedDate <= op.tanggal_selesai;
          });
          if (!operasional) {
            Swal.fire('Maaf', 'Jam operasional tidak tersedia pada tanggal yang dipilih\n Tolong sesuaikan dengan yang ada.', 'warning');
            return;
          }

          const minTime = moment(operasional.jam_mulai, 'HH:mm:ss').format('HH:mm');
          const maxTime = moment(operasional.jam_selesai, 'HH:mm:ss').format('HH:mm');

          const defaultTime = minTime;
          const duration = parseInt($("#jenis_konsultasi option:selected").data("duration")) || 60;

          // Menampilkan tanggal dan waktu pada form modal
          var startDate = moment(clickedDate + 'T' + defaultTime);
          var endDate = startDate.clone().add(duration, 'minutes');
          $('#tanggalWaktu_mulai').val(startDate.format('YYYY-MM-DD HH:mm'));
          $('#tanggalWaktu_selesai').val(endDate.format('YYYY-MM-DD HH:mm'));

          const startPicker = flatpickr('#tanggalWaktu_mulai', {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            minTime: minTime,
            maxTime: maxTime,
            altInput: true,
            altFormat: "j F Y H:i",
            locale: "id",
            onChange: function(_, dateStr) {
              updateEndDate(dateStr);
            }
          });

          const endPicker = flatpickr('#tanggalWaktu_selesai', {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            minTime: minTime,
            maxTime: maxTime,
            altInput: true,
            altFormat: "j F Y H:i",
            locale: "id",
            allowInput: false
          });

          // Update waktu selesai saat jenis konsultasi diubah
          $('#jenis_konsultasi').off('change').on('change', function() {
            const startDateStr = $('#tanggalWaktu_mulai').val();
            if (startDateStr) {
              updateEndDate(startDateStr);
            }
          });

          modalReset();
          $('#eventModalLabel').text('Buat Jadwal Konsultasi');
          $('#saveEventBtn').text('Simpan');
          $('#deleteEventBtn').hide();

          $('#scheduleModal').modal('show');
          $('#openEventModal').off('click').on('click', function() {
            $('#scheduleModal').modal('hide');
            $('#eventModal').modal('show');
          });
          $('#backToScheduleModal').off('click').on('click', function() {
            $('#eventModal').modal('hide');
            $('#scheduleModal').modal('show');
          });


          // Fungsi untuk menyimpan data yang di inputkan pada form penjadwalan
          $('#saveEventBtn').off('click').on('click', function() {
            let postData = {
              start_date: $('#tanggalWaktu_mulai').val(),
              end_date: $('#tanggalWaktu_selesai').val(),
              title: $('#booked').val(),
              nama_lengkap: $('#nama_lengkap').val(),
              perusahaan: $('#perusahaan').val(),
              jenis_konsultasi: $('#jenis_konsultasi').val(),
              durasi_konsultasi: $('#durasi_konsultasi').val(),
              deskripsi: $('#deskripsi').val(),
              _token: "{{ csrf_token() }}"
            };

            Swal.fire({
              title: 'Menyimpan...',
              text: 'Silakan tunggu.',
              allowEscapeKey: false,
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              }
            });

            $.ajax({
              url: "{{ route('kalender.store') }}",
              type: "POST",
              dataType: 'json',
              data: postData,
              success: function(response) {
                calendar.addEvent({
                  id: response.id,
                  user_id: response.user_id,
                  start: response.start_date,
                  end: response.end_date,
                  title: response.title,
                  extendedProps: {
                    nama_lengkap: response.nama_lengkap,
                    perusahaan: response.perusahaan,
                    jenis_konsultasi: response.jenis_konsultasi,
                    durasi_konsultasi: response.durasi_konsultasi,
                    deskripsi: response.deskripsi,
                    zoom_link: response.zoom_link,
                  },
                  backgroundColor: '#198754',
                  borderColor: '#198754',
                  display: 'block',
                });

                Swal.fire({
                  title: 'Sukses!',
                  html: 'Jadwal berhasil disimpan!',
                  icon: 'success',
                  confirmButtonText: 'OK'
                });

                $('#eventModal').modal('hide');
                calendar.refetchEvents();
              },
              error: function(error) {
                console.log("Error details:", error);
                if (error.responseJSON && error.responseJSON.errors) {
                  console.log(error.responseJSON.errors);
                }
                Swal.close();
              },
            });
          });

          updateEndDate($('#tanggalWaktu_mulai').val());

          // Fungsi untuk menghitung dan memperbarui waktu selesai
          function updateEndDate(startDateStr) {
            const selectedOption = $("#jenis_konsultasi option:selected");
            const selectedDuration = parseInt(selectedOption.data("duration")) || 60;
            const startDate = moment(startDateStr, "YYYY-MM-DD HH:mm");
            const endDate = startDate.clone().add(selectedDuration, 'minutes');

            if (endDate.format('HH:mm') > maxTime) {
              Swal.fire({
                icon: 'error',
                title: 'Waktu Konsultasi Melebihi Batas',
                text: `Jam selesai konsultasi (${endDate.format('HH:mm')}) melebihi jam operasional (${maxTime}). Silakan pilih waktu yang sesuai.`,
              });

              // Reset waktu selesai ke batas maksimal
              endPicker.setDate(moment(clickedDate + 'T' + maxTime).format("YYYY-MM-DD HH:mm"), false, "Y-m-d H:i");
              return;
            }

            endPicker.setDate(endDate.format("YYYY-MM-DD HH:mm"), false, "Y-m-d H:i");
          }
        },
        editable: true,
        eventDrop: function(info) { // Fungsi untuk handle pemindahan jadwal
          var start_date = moment(info.event.start).format('YYYY-MM-DD HH:mm');
          var end_date = moment(info.event.end).format('YYYY-MM-DD HH:mm');

          const loading = Swal.fire({
            title: 'Sedang memindahkan jadwal...',
            text: 'Mohon tunggu...',
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });

          $.ajax({
            url: "{{ route('kalender.update', '') }}" + '/' + info.event.id,
            type: "PATCH",
            dataType: 'json',
            data: {
              start_date,
              end_date,
              _token: "{{ csrf_token() }}"
            },
            success: function(response) {
              loading.close();
              Swal.fire({
                title: "Event Updated!",
                text: "Jadwal berhasil diubah!",
                icon: "success",
                confirmButtonText: "OK"
              });
            },
            error: function(error) {
              loading.close();
              console.log("Error details:", error);
              if (error.responseJSON && error.responseJSON.errors) {
                console.log(error.responseJSON.errors);
              }
              Swal.fire({
                title: "Error!",
                text: "Terjadi kesalahan saat mengubah jadwal.",
                icon: "error",
                confirmButtonText: "OK"
              });
            },
          });
        },
        eventClick: function(info) { // Fungsi untuk handle klik pada event
          if (info.event.extendedProps.user_id == currentUserId) {
            const clickedDate = moment(info.event.start).format('YYYY-MM-DD');
            var operasional = jamOperasional.find(op => {
              return clickedDate >= op.tanggal_mulai && clickedDate <= op.tanggal_selesai;
            });

            const minTime = moment(operasional.jam_mulai, 'HH:mm:ss').format('HH:mm');
            const maxTime = moment(operasional.jam_selesai, 'HH:mm:ss').format('HH:mm');

            var startDate = moment(info.event.start);
            var endDate = moment(info.event.end);
            const duration = parseInt(info.event.extendedProps.durasi_konsultasi) || 60;

            $('#tanggalWaktu_mulai').val(startDate.format('YYYY-MM-DD HH:mm'));
            $('#tanggalWaktu_selesai').val(endDate.format('YYYY-MM-DD HH:mm'));
            $('#nama_lengkap').val(info.event.extendedProps.nama_lengkap);
            $('#perusahaan').val(info.event.extendedProps.perusahaan);
            $('#jenis_konsultasi').val(info.event.extendedProps.jenis_konsultasi);
            $('#durasi_konsultasi').val(duration);
            $('#deskripsi').val(info.event.extendedProps.deskripsi);

            const startPicker = flatpickr("#tanggalWaktu_mulai", {
              enableTime: true,
              dateFormat: "Y-m-d H:i",
              time_24hr: true,
              minTime: minTime,
              maxTime: maxTime,
              altInput: true,
              altFormat: "j F Y H:i",
              locale: "id",
              onChange: function(_, dateStr) {
                updateEndDate(dateStr);
              }
            });

            const endPicker = flatpickr("#tanggalWaktu_selesai", {
              enableTime: true,
              dateFormat: "Y-m-d H:i",
              time_24hr: true,
              minTime: minTime,
              maxTime: maxTime,
              altInput: true,
              altFormat: "j F Y H:i",
              locale: "id",
              allowInput: false,
            });

            $('#jenis_konsultasi').off('change').on('change', function() {
              const startDateStr = $('#tanggalWaktu_mulai').val();
              if (startDateStr) {
                updateEndDate(startDateStr);
              }
            });

            if (info.event.id) {
              $('#eventModalLabel').text('Edit Jadwal Konsultasi');
              $('#saveEventBtn').text('Simpan Perubahan');
              $('#backToScheduleModal').hide();
              // Fungsi untuk handle penghapusan event
              $('#deleteEventBtn').show().off('click').on('click', function() {
                Swal.fire({
                  title: "Apakah anda yakin?",
                  text: "Setelah dihapus, Anda tidak dapat memulihkan jadwal ini!",
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonText: "Hapus",
                  cancelButtonText: "Batal",
                  reverseButtons: true
                }).then((result) => {
                  if (result.isConfirmed) {
                    Swal.fire({
                      title: 'Menghapus...',
                      text: 'Silakan tunggu.',
                      allowEscapeKey: false,
                      allowOutsideClick: false,
                      didOpen: () => {
                        Swal.showLoading();
                      }
                    });

                    $.ajax({
                      url: "{{ route('kalender.destroy', '') }}" + '/' + info.event.id,
                      type: "DELETE",
                      dataType: 'json',
                      data: {
                        _token: "{{ csrf_token() }}"
                      },
                      success: function(response) {
                        info.event.remove();
                        $('#eventModal').modal('hide');
                        Swal.fire("Sukses!", "Jadwal telah dihapus!", "success");
                      },
                      error: function(error) {
                        Swal.fire("Error!", "Terjadi kesalahan saat menghapus jadwal.", "error");
                      },
                    });
                  } else {
                    Swal.fire("Jadwal Anda aman!");
                  }
                });
              });
            } else {
              $('#deleteEventBtn').hide();
            }

            $('#eventModal').modal('show');
            // Fungsi untuk menyimpan data yang telah diubah
            $('#saveEventBtn').off('click').on('click', function() {
              let postData = {
                start_date: $('#tanggalWaktu_mulai').val(),
                end_date: $('#tanggalWaktu_selesai').val(),
                jenis_konsultasi: $('#jenis_konsultasi').val(),
                durasi_konsultasi: $('#durasi_konsultasi').val(),
                deskripsi: $('#deskripsi').val(),
                _token: "{{ csrf_token() }}"
              };

              Swal.fire({
                title: 'Menyimpan Perubahan...',
                text: 'Silakan tunggu.',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                  Swal.showLoading();
                }
              });

              $.ajax({
                url: "{{ route('kalender.update', '') }}" + '/' + info.event.id,
                type: "PATCH",
                dataType: 'json',
                data: postData,
                success: function(response) {
                  info.event.setStart(response.start_date);
                  info.event.setEnd(response.end_date);
                  info.event.setExtendedProp('jenis_konsultasi', response.jenis_konsultasi);
                  info.event.setExtendedProp('durasi_konsultasi', response.durasi_konsultasi);
                  info.event.setExtendedProp('deskripsi', response.deskripsi);

                  Swal.fire({
                    title: "Event Updated!",
                    text: "Jadwal berhasil diubah!",
                    icon: "success",
                    confirmButtonText: "OK"
                  });

                  $('#eventModal').modal('hide');
                  calendar.refetchEvents();
                },
                error: function(error) {
                  Swal.close();
                },
              });
            });

            updateEndDate($('#tanggalWaktu_mulai').val());

            function updateEndDate(startDateStr) {
              const selectedOption = $("#jenis_konsultasi option:selected");
              const selectedDuration = parseInt(selectedOption.data("duration")) || 60;
              const startDate = moment(startDateStr, "YYYY-MM-DD HH:mm");
              const endDate = startDate.clone().add(selectedDuration, 'minutes');

              if (endDate.format('HH:mm') > maxTime) {
                Swal.fire({
                  icon: 'error',
                  title: 'Waktu Konsultasi Melebihi Batas',
                  text: `Jam selesai konsultasi (${endDate.format('HH:mm')}) melebihi jam operasional (${maxTime}). Silakan pilih waktu yang sesuai.`,
                });

                // Reset waktu selesai ke batas maksimal
                endPicker.setDate(moment(clickedDate + 'T' + maxTime).format("YYYY-MM-DD HH:mm"), false, "Y-m-d H:i");
                return;
              }

              endPicker.setDate(endDate.format("YYYY-MM-DD HH:mm"), false, "Y-m-d H:i");
            }
          } else {
            Swal.fire({
              title: "Peringatan!",
              text: "Anda tidak memiliki akses!",
              icon: "warning",
              confirmButtonText: "OK",
            });
          }
        },
        selectAllow: function(selectInfo) { // Fungsi untuk membatasi seleksi hari
          var start = selectInfo.start;
          var end = selectInfo.end;
          var duration = moment(end).diff(moment(start), 'days');
          return duration === 1; // Hanya izinkan pemilihan satu hari
        },
      });
      calendar.render();

      $("#eventModal").on("hidden.bs.modal", function() {
        modalReset();
        $('#saveEventBtn').unbind();
      });

      $(".fc").css("background-color", "white");
    });

    function modalReset() {
      $('#jenis_konsultasi').val('');
      $('#deskripsi').val('');
      $('deleteEventBtn').hide();
    }

    document.getElementById('jenis_konsultasi').addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      const duration = selectedOption.getAttribute('data-duration');
      document.getElementById('durasi_konsultasi').value = duration;
    });
  </script>
@endsection
