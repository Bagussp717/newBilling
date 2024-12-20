<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/dist/simplebar.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>

<!-- DataTables -->
<script src="{{ asset('assets/libs/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables/dataTables.bootstrap4.min.js') }}"></script>
{{-- <script src="{{ asset('assets/libs/datatables/datatables-demo.js') }}"></script> --}}
<script>
    $(document).ready(function() {
        let currentPagingType = getPagingType();
        let isSearching = false;

        function initializeDataTable(pagingType) {
            if ($.fn.DataTable.isDataTable("#dataTable")) {
                if (currentPagingType === pagingType) {
                    return;
                }

                $("#dataTable").DataTable().destroy();
            }

            $("#dataTable").DataTable({
                language: {
                    searchPlaceholder: "Search Data...",
                    search: "",
                },
                pagingType: pagingType,
            });

            currentPagingType = pagingType;
        }

        function getPagingType() {
            return $(window).width() <= 767 ? "simple" : "simple_numbers";
        }

        initializeDataTable(currentPagingType);

        // Deteksi jika input pencarian dalam fokus
        $("#dataTable_filter input").on("focus", function() {
            isSearching = true;
        }).on("blur", function() {
            isSearching = false;
        });

        $(window).resize(function() {
            if (!isSearching) {
                const newPagingType = getPagingType();
                if (newPagingType !== currentPagingType) {
                    initializeDataTable(newPagingType);
                }
            }
        });
    });
</script>

<!-- Toastr CSS and JS -->
<link rel="stylesheet" href="{{ asset('assets/libs/toastr/toastr.min.css') }}">
<script src="{{ asset('assets/libs/toastr/toastr.min.js') }}"></script>


<!-- SweetAlert CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.0.1/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.0.1/dist/sweetalert2.all.min.js"></script>

<!-- Toastr Initialization -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.0.1/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.0.1/dist/sweetalert2.all.min.js"></script>

<!-- Select2 -->
<script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/select2/select2-bootstrap-5-theme.min.css') }}">
<script>
    $('#multiple-select-field').select2({
        theme: "bootstrap-5",
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        closeOnSelect: false,
    });
</script>

<!-- Maps -->
{{-- <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    var lat = -8.2117;
    var lng = 114.3676;

    // Inisialisasi peta dan set peta untuk terpusat di Banyuwangi
    var map = L.map('map').setView([lat, lng], 13);

    // Tambahkan tile layer (misalnya OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://semesta.co.id">Semesta Multitekno</a>'
    }).addTo(map);

    // Tambahkan marker untuk menunjukkan lokasi Banyuwangi
    var marker = L.marker([lat, lng]).addTo(map)
        .bindPopup('Banyuwangi, Indonesia')
        .openPopup();

    document.getElementById('get-location').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var long = position.coords.longitude;

                // Update peta dengan koordinat terkini
                map.setView([lat, long], 13);

                // Jika marker sudah ada, hapus marker lama
                if (marker) {
                    map.removeLayer(marker);
                }

                // Tambahkan marker baru di lokasi terkini
                marker = L.marker([lat, long]).addTo(map)
                    .bindPopup('Lokasi Terkini Anda')
                    .openPopup();

                // Update input tersembunyi
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = long;

                // Tampilkan koordinat di console (untuk debugging)
                console.log('Latitude: ' + lat);
                console.log('Longitude: ' + long);

                // alert('Lokasi Terkini: \nLatitude: ' + lat + '\nLongitude: ' + long);
            }, function(error) {
                console.error('Error: ' + error.message);
                alert('Gagal mendapatkan lokasi. Pastikan lokasi diaktifkan di browser Anda.');
            });
        } else {
            alert('Geolocation tidak didukung oleh browser ini.');
        }
    });
</script> --}}

<!-- Toastr Initialization -->
<script>
    $(document).ready(function() {
        toastr.options = {
            "closeButton": true
        };

        @if (Session::has('success'))
            toastr.success('{{ Session::get('success') }}', 'Success');
        @endif

        @if (Session::has('error'))
            toastr.error('{{ Session::get('error') }}', 'Error');
        @endif

        @if (Session::has('status'))
            toastr.success('{{ Session::get('status') }}', 'Success');
        @endif

        @if (Session::has('resent'))
            toastr.success('Email verifikasi telah terkirim ulang.', 'Success');
        @endif
    });
</script>

<script>
    function notificationBeforeDelete(event, el) {
        event.preventDefault();
        const form = $(el).closest('form'); // Find the form closest to the clicked link
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: 'Anda akan menghapus data ini!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#696cff',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Submit the form if confirmed
            }
        });
    }
</script>

<script>
    function formatRupiah(element) {
        // Ambil elemen input hidden
        const hiddenInput = document.getElementById('jml_bayar');
        // Hapus karakter non-digit
        let value = element.value.replace(/[^,\d]/g, "").toString();
        let split = value.split(",");
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;

        // Tampilkan nilai yang diformat di view
        element.value = "Rp " + rupiah;


        hiddenInput.value = value.replace(/\./g, "").replace(",", "");
    }
</script>

<script>
    function formatRupiahedit(element, hiddenInputId) {
        // Ambil elemen input hidden yang sesuai dengan ID yang diberikan
        const hiddenInput = document.getElementById(hiddenInputId);

        // Hapus karakter non-digit
        let value = element.value.replace(/[^,\d]/g, "").toString();
        let split = value.split(",");
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;

        // Tampilkan nilai yang diformat di view
        element.value = "Rp " + rupiah;

        // Set nilai yang tidak diformat pada input hidden
        hiddenInput.value = value.replace(/\./g, "").replace(",", "");
    }
</script>


<script>
    function formatTelepon(element) {

        let value = element.value.replace(/\D/g, "");


        if (!value.startsWith('62')) {
            value = '62' + value.replace(/^0+/, '');
        }


        element.value = value;
    }
</script>
