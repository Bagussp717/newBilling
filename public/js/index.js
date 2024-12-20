/* **************************
 **                      **
 **        Uptime        **
 **                      **
 ************************** */

$(document).ready(function () {
    var firstLoad = true; // Variabel untuk menandai permintaan pertama

    function fetchData() {
        $.ajax({
            url: "/uptime/realtime",
            type: "GET",
            beforeSend: function () {
                // Menampilkan spinner hanya saat permintaan pertama
                if (firstLoad) {
                    $("#spinner1").removeClass("d-none"); // Pastikan spinner ditampilkan
                }
            },
            success: function (response) {
                // Mengganti elemen dengan data uptime aktual saat permintaan berhasil diselesaikan
                $("#uptime-load-placeholder").html(
                    '<h5 id="uptime-load" style="font-size: 16px; font-weight: semibold;" class="pt-2">' +
                        response.uptime +
                        "</h5>"
                );
                if (firstLoad) {
                    // Menyembunyikan spinner setelah data berhasil diambil pertama kali
                    $("#spinner1").addClass("d-none");
                    firstLoad = false; // Setelah permintaan pertama berhasil, atur firstLoad menjadi false
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                // Tindakan jika terjadi kesalahan saat mengambil data
            },
        });
    }

    // Panggil fetchData setiap 1 detik
    setInterval(fetchData, 1000);

    // Panggil fetchData sekali saat halaman dimuat
    fetchData();
});

/* **************************
 **                        **
 **        Cpu             **
 **                        **
 ************************** */
$(document).ready(function () {
    var firstLoad = true; // Variabel untuk menandai permintaan pertama
    var cpuChart; // Variabel untuk menyimpan referensi chart Highcharts
    Highcharts.setOptions({
        credits: {
            enabled: false,
        },
        global: {
            useUTC: false,
        },
        chart: {
            animation: Highcharts.svg,
            type: "pie",
        },
    });

    // Inisialisasi chart Highcharts
    cpuChart = new Highcharts.Chart({
        chart: {
            renderTo: "cpu-load-placeholder",
            height: 200,
            type: "pie",
            backgroundColor: null,
            margin: [30, 10, 30, 10],
            spacing: [0, 0, 0, 0],
        },
        title: {
            text: "",
        },
        subtitle: {
            text: "",
            align: "center",
            verticalAlign: "top",
            y: 20,
            style: {
                fontFamily: "YourWebsiteFont, sans-serif",
                fontSize: "12px",
                color: "#000000",
                marginBottom: "-100px",
            },
        },
        tooltip: {
            valueDecimals: 2,
            valueSuffix: "%",
        },
        options: {
            title: {
                display: false, // mengatur properti display menjadi false
            },
            // Konfigurasi lainnya
        },
        plotOptions: {
            // Menambahkan plotOptions untuk menentukan bahwa ini adalah donut chart
            pie: {
                borderWidth: 0,
                colorByPoint: true,
                size: "100%",
                innerSize: "80%",
                dataLabels: {
                    enabled: true,
                    crop: false,
                    distance: "-10%",
                    style: {
                        fontWeight: "semibold",
                        fontSize: "12px",
                    },
                    connectorWidth: 0,
                },
            },
        },
        colors: ["#F81100", "#00FF0D"],
        series: [
            {
                name: "CPU Usage",
                type: "pie",
                data: [], // data akan diperbarui dengan data yang diterima dari server
            },
        ],
    });

    function fetchData() {
        $.ajax({
            url: "/cpu/realtime",
            type: "GET",
            beforeSend: function () {
                // Menampilkan spinner hanya saat permintaan pertama
                if (firstLoad) {
                    $("#spinner").removeClass("hidden");
                }
            },
            success: function (response) {
                if (response.cpu !== undefined && cpuChart) {
                    // Mengonversi nilai string menjadi number
                    var cpuUsage = parseFloat(response.cpu);
                    if (!isNaN(cpuUsage)) {
                        var cpuFree = 100 - cpuUsage;
                        // Menyusun data dalam format yang sesuai untuk pie chart
                        var data = [
                            { name: "Used", y: cpuUsage },
                            { name: "Free", y: cpuFree },
                        ];
                        // Memperbarui data chart dengan data CPU yang diperoleh
                        cpuChart.series[0].setData(data);

                        // Update subtitle setelah memperbarui data chart
                        var subtitleText =
                            "CPU Used: " +
                            cpuUsage.toFixed(2) +
                            "%<br>CPU Free: " +
                            cpuFree.toFixed(2) +
                            "%";
                        cpuChart.setTitle(null, { text: subtitleText });
                    } else {
                        console.error("Error: Invalid CPU usage value");
                    }
                }
                firstLoad = false; // Setelah permintaan pertama berhasil, atur firstLoad menjadi false
            },
            error: function (xhr, status, error) {
                console.error(error);
                // Tindakan jika terjadi kesalahan saat mengambil data
            },
            complete: function () {
                // Menyembunyikan spinner setelah permintaan selesai, jika masih dalam mode firstLoad
                if (firstLoad) {
                    $("#spinner").addClass("hidden");
                }
            },
        });
    }

    // Panggil fetchData setiap 1 detik
    setInterval(fetchData, 1000);
});

/* **************************
 **                        **
 **      Memorychart       **
 **                        **
 ************************** */
$(document).ready(function () {
    var firstLoad = true; // Variabel untuk menandai permintaan pertama
    var memoryChart; // Variabel untuk menyimpan referensi chart Highcharts

    Highcharts.setOptions({
        credits: {
            enabled: false,
        },
        global: {
            useUTC: false,
        },
        chart: {
            animation: Highcharts.svg,
            type: "pie",
        },
    });

    // Inisialisasi chart Highcharts
    memoryChart = new Highcharts.Chart({
        chart: {
            renderTo: "memory-load-placeholder",
            height: 200,
            type: "pie",
            backgroundColor: null,
            margin: [30, 10, 30, 10],
            spacing: [0, 0, 0, 0],
        },
        title: {
            text: "",
        },
        tooltip: {
            valueDecimals: 2,
            valueSuffix: " GB",
        },
        plotOptions: {
            pie: {
                borderWidth: 0,
                colorByPoint: true,
                size: "100%",
                innerSize: "80%",
                dataLabels: {
                    enabled: true,
                    crop: false,
                    distance: "-10%",
                    style: {
                        fontWeight: "semibold",
                        fontSize: "12px",
                    },
                    connectorWidth: 0,
                    format: "{point.name}: {point.y:.2f} GB",
                },
            },
        },
        colors: ["#FF0000", "#00FF00"],
        series: [
            {
                name: "Memory Usage",
                type: "pie",
                data: [], // data akan diperbarui dengan data yang diterima dari server
            },
        ],
    });

    function fetchMemoryData() {
        $.ajax({
            url: "/free_memory/realtime",
            type: "GET",
            beforeSend: function () {
                if (firstLoad) {
                    $("#spinner-memory").removeClass("hidden");
                }
            },
            success: function (response) {
                if (response.freeMemoryGB !== undefined && memoryChart) {
                    var freeMemoryGB = parseFloat(response.freeMemoryGB);
                    var usedMemoryGB = parseFloat(response.usedMemoryGB);
                    var totalMemoryGB = parseFloat(response.totalMemoryGB);

                    if (
                        !isNaN(freeMemoryGB) &&
                        !isNaN(usedMemoryGB) &&
                        !isNaN(totalMemoryGB)
                    ) {
                        var data = [
                            { name: "Used", y: usedMemoryGB },
                            { name: "Free", y: freeMemoryGB },
                        ];

                        memoryChart.series[0].setData(data);

                        var subtitleText =
                            "Memory Used: " +
                            usedMemoryGB.toFixed(2) +
                            " GB<br>Memory Free: " +
                            freeMemoryGB.toFixed(2) +
                            " GB";
                        memoryChart.setTitle(null, { text: subtitleText });
                    } else {
                        console.error("Error: Invalid memory usage value");
                    }
                }
                firstLoad = false;
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
            complete: function () {
                if (firstLoad) {
                    $("#spinner-memory").addClass("hidden");
                }
            },
        });
    }

    setInterval(fetchMemoryData, 1000);
});

/* **************************
 **                        **
 **         HDD            **
 **                        **
 ************************** */

$(document).ready(function () {
    var firstLoad = true; // Variabel untuk menandai permintaan pertama
    var hddChart; // Variabel untuk menyimpan referensi chart Highcharts

    Highcharts.setOptions({
        credits: {
            enabled: false,
        },
        global: {
            useUTC: false,
        },
        chart: {
            animation: Highcharts.svg,
            type: "pie",
        },
    });

    // Inisialisasi chart Highcharts
    hddChart = new Highcharts.Chart({
        chart: {
            renderTo: "hdd-load-placeholder",
            height: 200,
            type: "pie",
            backgroundColor: null,
            margin: [30, 10, 30, 10],
            spacing: [0, 0, 0, 0],
        },
        title: {
            text: "",
        },
        tooltip: {
            valueDecimals: 2,
            valueSuffix: " GB",
        },
        plotOptions: {
            pie: {
                borderWidth: 0,
                colorByPoint: true,
                size: "100%",
                innerSize: "80%",
                dataLabels: {
                    enabled: true,
                    crop: false,
                    distance: "-10%",
                    style: {
                        fontWeight: "semibold",
                        fontSize: "12px",
                    },
                    connectorWidth: 0,
                    format: "{point.name}: {point.y:.2f} GB",
                },
            },
        },
        colors: ["#FF0000", "#00FF00"],
        series: [
            {
                name: "HDD Usage",
                type: "pie",
                data: [], // data akan diperbarui dengan data yang diterima dari server
            },
        ],
    });

    //Mengatur kelas Tailwind CSS untuk warna latar belakang
    var systemBackgroundColorClass =
        "bg-white dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70";
    $("#hdd-load-placeholder").addClass(systemBackgroundColorClass);

    function fetchHddData() {
        $.ajax({
            url: "/free_hdd_space/realtime",
            type: "GET",
            beforeSend: function () {
                if (firstLoad) {
                    $("#spinner-hdd").removeClass("hidden");
                }
            },
            success: function (response) {
                if (response.freeHddSpaceGB !== undefined && hddChart) {
                    var freeHddSpaceGB = parseFloat(response.freeHddSpaceGB);
                    var usedHddSpaceGB = parseFloat(response.usedHddSpaceGB);
                    var totalHddSpaceGB = parseFloat(response.totalHddSpaceGB);

                    if (
                        !isNaN(freeHddSpaceGB) &&
                        !isNaN(usedHddSpaceGB) &&
                        !isNaN(totalHddSpaceGB)
                    ) {
                        var data = [
                            { name: "Used", y: usedHddSpaceGB },
                            { name: "Free", y: freeHddSpaceGB },
                        ];

                        hddChart.series[0].setData(data);

                        var subtitleText =
                            "HDD Used: " +
                            usedHddSpaceGB.toFixed(2) +
                            " GB<br>HDD Free: " +
                            freeHddSpaceGB.toFixed(2) +
                            " GB";
                        hddChart.setTitle(null, { text: subtitleText });
                    } else {
                        console.error("Error: Invalid HDD usage value");
                    }
                }
                firstLoad = false;
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
            complete: function () {
                if (firstLoad) {
                    $("#spinner-hdd").addClass("hidden");
                }
            },
        });
    }

    setInterval(fetchHddData, 1000);
});

/* **************************
 **                        **
 **        Traffict        **
 **                        **
 ************************** */

$(document).ready(function () {
    var chart;
    var interval;

    Highcharts.setOptions({
        credits: {
            enabled: false,
        },
        global: {
            useUTC: true,
        },
        chart: {
            animation: Highcharts.svg,
            type: "areaspline",
        },
    });

    chart = new Highcharts.Chart({
        chart: {
            renderTo: "trafficMonitor",
            height: 300,
            animation: Highcharts.svg,
            backgroundColor: null,
            // backgroundColor: 'transparent',
        },
        title: {
            text: "",
            align: "center",
            margin: 15,
            style: {
                fontSize: "13px",
            },
        },
        xAxis: {
            type: "datetime",
            tickPixelInterval: 150,
            maxZoom: 20 * 1000,
            labels: {
                formatter: function () {
                    return Highcharts.dateFormat(
                        "%H:%M:%S",
                        this.value + 7 * 3600 * 1000
                    ); // Ubah sesuai dengan zona waktu Anda
                },
            },
        },
        yAxis: {
            minPadding: 0.2,
            maxPadding: 0.2,
            title: {
                text: "Traffic",
            },
            labels: {
                formatter: function () {
                    var bytes = this.value;
                    var sizes = ["bps", "kbps", "Mbps", "Gbps", "Tbps"];
                    if (bytes == 0) return "0 bps";
                    var i = Math.floor(Math.log(bytes) / Math.log(1024));
                    return (
                        parseFloat(bytes / Math.pow(1024, i)).toFixed(2) +
                        " " +
                        sizes[i]
                    );
                },
            },
        },
        tooltip: {
            formatter: function () {
                var s = [];
                $.each(this.points, function (i, point) {
                    var bytes = point.y;
                    var sizes = ["bps", "kbps", "Mbps", "Gbps", "Tbps"];
                    var i = Math.floor(Math.log(bytes) / Math.log(1024));
                    s.push(
                        '<span style="color:' +
                            point.series.color +
                            '">' +
                            point.series.name +
                            "</span>: <b>" +
                            parseFloat(bytes / Math.pow(1024, i)).toFixed(2) +
                            " " +
                            sizes[i] +
                            "</b><br/>"
                    );
                });
                s.push(
                    "Waktu: <b>" +
                        Highcharts.dateFormat(
                            "%H:%M:%S",
                            this.x + 7 * 3600 * 1000
                        ) +
                        "</b>"
                );
                return s.join("");
            },
            shared: true,
        },
        series: [
            {
                color: "#19ff19",
                name: "TX",
                data: [],
            },
            {
                color: "#e74c3c",
                name: "RX",
                data: [],
            },
        ],
    });

    //Mengatur kelas Tailwind CSS untuk warna latar belakang
    var systemBackgroundColorClass =
        "bg-white dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70";
    $("#trafficMonitor").addClass(systemBackgroundColorClass);

    // Memulai permintaan data
    // $('#interface').change(function() {
    //     var interface = $(this).val();
    //     clearInterval(interval);
    //     requestData(interface);
    //     interval = setInterval(function() {
    //         requestData(interface);
    //     }, 3000);
    // });

    function formatBytes(bytes) {
        var sizes = ["bps", "kbps", "Mbps", "Gbps", "Tbps"];
        if (bytes == 0) return "0 bps";
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        i = Math.min(i, 2); // Ensure the value does not exceed 2 (for Mbps)
        return Math.round(bytes / Math.pow(1024, i), 2) + " " + sizes[i];
    }

    function requestData(interface) {
        $.ajax({
            url: "/traffic/realtime/:traffic".replace(":traffic", interface),
            dataType: "json",
            success: function (data) {
                if (data.length > 0) {
                    var TX = parseInt(data[0].data);
                    var RX = parseInt(data[1].data);
                    var x = new Date().getTime();
                    var shift = chart.series[0].data.length > 19;
                    chart.series[0].addPoint([x, TX], true, shift);
                    chart.series[1].addPoint([x, RX], true, shift);

                    // Update table with TX and RX values
                    $("#txValue").html(
                        '<span"> TX: ' + formatBytes(TX) + "</span>"
                    );
                    $("#rxValue").html(
                        '<span"> RX: ' + formatBytes(RX) + "</span>"
                    );
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.error(
                    "Status: " + textStatus + " request: " + XMLHttpRequest
                );
                console.error("Error: " + errorThrown);
            },
        });
    }
    // Automatically call requestData with 'ether 1' on page load
    var defaultInterface = $("#interface").val();
    requestData(defaultInterface);
    interval = setInterval(function () {
        requestData(defaultInterface);
    }, 3000);

    // Handle interface change
    $("#interface").change(function () {
        var selectedInterface = $(this).val();
        clearInterval(interval);
        requestData(selectedInterface);
        interval = setInterval(function () {
            requestData(selectedInterface);
        }, 3000);
    });
});
