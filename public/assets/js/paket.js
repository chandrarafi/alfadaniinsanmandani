$(document).ready(function () {
  // Format currency
  function formatRupiah(angka) {
    return new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR",
      minimumFractionDigits: 0,
    }).format(angka);
  }

  // Format date
  function formatDate(dateString) {
    if (!dateString) return "-";
    const date = new Date(dateString);
    return new Intl.DateTimeFormat("id-ID", {
      day: "2-digit",
      month: "long",
      year: "numeric",
    }).format(date);
  }

  // Menampilkan alert
  function showAlert(type, message) {
    const alertClass = type === "success" ? "alert-success" : "alert-danger";
    const icon =
      type === "success" ? "bxs-check-circle" : "bxs-message-square-x";
    const title = type === "success" ? "Sukses" : "Error";
    const borderClass = type === "success" ? "border-success" : "border-danger";
    const textClass = type === "success" ? "text-success" : "text-danger";

    const alertHtml = `
      <div class="alert border-0 border-start border-5 ${borderClass} alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
          <div class="font-35 ${textClass}"><i class="bx ${icon}"></i></div>
          <div class="ms-3">
            <h6 class="mb-0 ${textClass}">${title}</h6>
            <div>${message}</div>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    `;

    $("#alertContainer").html(alertHtml);

    // Auto hide after 5 seconds
    setTimeout(function () {
      $("#alertContainer .alert").alert("close");
    }, 5000);
  }

  // Inisialisasi DataTable
  const table = $("#paketTable").DataTable({
    processing: true,
    responsive: true,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json",
    },
    ajax: {
      url: baseUrl + "/admin/paket/getAll",
      type: "GET",
      dataSrc: function (json) {
        return json.data;
      },
      error: function (xhr, error, thrown) {
        console.error(xhr);
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Terjadi kesalahan saat memuat data paket",
        });
      },
    },
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          return meta.row + 1;
        },
      },
      { data: "idpaket" },
      { data: "namapaket" },
      { data: "namakategori" },
      {
        data: "waktuberangkat",
        render: function (data, type, row) {
          return formatDate(data);
        },
      },
      { data: "kuota" },
      {
        data: "harga",
        render: function (data, type, row) {
          return formatRupiah(data);
        },
      },
      {
        data: "status",
        render: function (data, type, row) {
          const statusClass = data == 1 ? "success" : "danger";
          const statusText = data == 1 ? "Aktif" : "Nonaktif";
          return `<span class="badge bg-${statusClass}">${statusText}</span>`;
        },
      },
      {
        data: null,
        render: function (data, type, row) {
          const statusBtn =
            row.status == 1
              ? `<button type="button" class="btn btn-sm btn-warning btn-status" data-id="${row.idpaket}" data-status="${row.status}" title="Nonaktifkan"><i class="bx bx-power-off"></i></button>`
              : `<button type="button" class="btn btn-sm btn-success btn-status" data-id="${row.idpaket}" data-status="${row.status}" title="Aktifkan"><i class="bx bx-power-off"></i></button>`;

          return `
            <div class="d-flex gap-1">
                <a href="${baseUrl}/admin/paket/edit/${row.idpaket}" class="btn btn-sm btn-primary" title="Edit"><i class="bx bx-edit"></i></a>
                ${statusBtn}
                <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="${row.idpaket}" data-nama="${row.namapaket}" title="Hapus"><i class="bx bx-trash"></i></button>
            </div>
          `;
        },
      },
    ],
    columnDefs: [
      {
        targets: [0, 7, 8],
        orderable: false,
      },
    ],
  });

  // Event klik tombol Status
  $("#paketTable").on("click", ".btn-status", function () {
    const id = $(this).data("id");
    const status = $(this).data("status");
    const newStatus = status == 1 ? "nonaktifkan" : "aktifkan";

    Swal.fire({
      title: "Konfirmasi",
      text: `Apakah Anda yakin ingin ${newStatus} paket ini?`,
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Ya, Lanjutkan",
      cancelButtonText: "Batal",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: baseUrl + "/admin/paket/changeStatus",
          type: "POST",
          data: { idpaket: id },
          dataType: "json",
          success: function (response) {
            if (response.status) {
              showAlert("success", response.message);
              table.ajax.reload();
            }
          },
          error: function (xhr, status, error) {
            showAlert(
              "error",
              xhr.responseJSON?.messages ||
                "Terjadi kesalahan saat mengubah status"
            );
          },
        });
      }
    });
  });

  // Event klik tombol Delete
  $("#paketTable").on("click", ".btn-delete", function () {
    const id = $(this).data("id");
    const nama = $(this).data("nama");

    Swal.fire({
      title: "Konfirmasi",
      text: `Apakah Anda yakin ingin menghapus paket "${nama}"?`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Ya, Hapus",
      cancelButtonText: "Batal",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: baseUrl + "/admin/paket/delete",
          type: "POST",
          data: { idpaket: id },
          dataType: "json",
          success: function (response) {
            if (response.status) {
              showAlert("success", response.message);
              table.ajax.reload();
            }
          },
          error: function (xhr, status, error) {
            showAlert(
              "error",
              xhr.responseJSON?.messages ||
                "Terjadi kesalahan saat menghapus data"
            );
          },
        });
      }
    });
  });
});
