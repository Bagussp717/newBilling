// $(document).ready(function () {
//     let currentPagingType = getPagingType();
//     let isSearching = false;

//     function initializeDataTable(pagingType) {
//         if ($.fn.DataTable.isDataTable("#dataTable")) {
//             if (currentPagingType === pagingType) {
//                 return;
//             }

//             $("#dataTable").DataTable().destroy();
//         }

//         $("#dataTable").DataTable({
//             language: {
//                 searchPlaceholder: "Search...",
//                 search: "",
//             },
//             pagingType: pagingType,
//         });

//         currentPagingType = pagingType;
//     }

//     function getPagingType() {
//         return $(window).width() <= 767 ? "simple" : "simple_numbers";
//     }

//     initializeDataTable(currentPagingType);

//     // Deteksi jika input pencarian dalam fokus
//     $("#dataTable_filter input").on("focus", function () {
//         isSearching = true;
//     }).on("blur", function () {
//         isSearching = false;
//     });

//     $(window).resize(function () {
//         if (!isSearching) {
//             const newPagingType = getPagingType();
//             if (newPagingType !== currentPagingType) {
//                 initializeDataTable(newPagingType);
//             }
//         }
//     });
// });


