@extends('layouts.app', ['judul' => 'Kalender Order'])

@section('isi')

<style>

.tabel-booking{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}

.tabel-booking th{
    background:#f5f5f5;
    padding:12px;
    border:1px solid #ddd;
    text-align:left;
}

.tabel-booking td{
    padding:12px;
    border:1px solid #ddd;
}

.modal-konten{
    background:#fff;
    padding:20px;
    border-radius:10px;
}

</style>

<div class="baris-atas">
    <div class="judul-halaman">
        Kalender Order
    </div>
</div>

<div class="kartu-form">
    <div id="calendar"></div>
</div>

<!-- MODAL -->
<div class="modal" id="modalKalender">

    <div class="modal-konten" style="max-width:1000px;">

        <div class="modal-judul">
            Booking Tanggal :
            <span id="tanggalDipilih"></span>
        </div>

        <div style="overflow-x:auto;">

            <table class="tabel-booking">

                <thead>
                    <tr>
                        <th>Nama Customer</th>
                        <th>Jam Ambil</th>
                        <th>Durasi</th>
                        <th>Mobil</th>
                        <th>Plat Nomor</th>
                        
                    </tr>
                </thead>

                <tbody id="isiBooking"></tbody>

            </table>

        </div>

        <div class="modal-baris" style="margin-top:20px;">
            <button onclick="tutupModalKalender()">
                Tutup
            </button>
        </div>

    </div>

</div>

@endsection

@push('skrip')

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet'>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

<script>

document.addEventListener('DOMContentLoaded', function() {

    var semuaEvents = @json($events);

    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',

        locale: 'id',

        height: 700,

        events: semuaEvents,

        dateClick: function(info) {

            tampilBookingTanggal(info.dateStr);
        },

        eventClick: function(info) {

            tampilBookingTanggal(info.event.startStr);
        }

    });

    calendar.render();

    function tampilBookingTanggal(tanggal){

        document.getElementById('tanggalDipilih')
            .innerHTML = tanggal;

        let html = '';

        let hasil = semuaEvents.filter(function(event){

            return event.start === tanggal;
        });

        if(hasil.length === 0){

            html += `
                <tr>
                    <td colspan="5" style="text-align:center;">
                        Tidak ada booking
                    </td>
                </tr>
            `;
        }

        hasil.forEach(function(event){

            html += `
                <tr>
                    <td>${event.extendedProps.nama}</td>
                    <td>${event.extendedProps.jam}</td>
                    <td>${event.extendedProps.durasi}</td>
                    <td>${event.extendedProps.mobil}</td>
                    <td>${event.extendedProps.plat}</td>
                </tr>
            `;
        });

        document.getElementById('isiBooking').innerHTML = html;

        document.getElementById('modalKalender')
            .classList.add('tampil');
    }

});

function tutupModalKalender(){

    document.getElementById('modalKalender')
        .classList.remove('tampil');
}

</script>

@endpush