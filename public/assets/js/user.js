$(document).ready(function () {
  // Inisialisasi DataTable
  const table = $("#userTable").DataTable({
    processing: true,
    responsive: true,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json",
    },
    ajax: {
      url: baseUrl + "/admin/user/getAll",
      type: "GET",
      dataSrc: function (json) {
        return json.data;
      },
      error: function (xhr, error, thrown) {
        console.error(xhr);
        Swal.fire({
          icon: "error",
          title: "Error!",
          text: "Terjadi kesalahan saat memuat data user",
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
      { data: "username" },
      { data: "nama" },
      { data: "email" },
      {
        data: "role",
        render: function (data, type, row) {
          let badgeClass = "";
          switch (data) {
            case "admin":
              badgeClass = "primary";
              break;
            case "pimpinan":
              badgeClass = "info";
              break;
            case "jamaah":
              badgeClass = "success";
              break;
            default:
              badgeClass = "secondary";
          }
          return `<span class="badge bg-${badgeClass}">${data}</span>`;
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
          // Jangan tampilkan tombol status dan hapus untuk akun sendiri
          const currentUserId = sessionUserId; // Akan diisi dari view
          const isCurrentUser = row.id == currentUserId;

          const statusBtn = isCurrentUser
            ? ""
            : row.status == 1
            ? `<button type="button" class="btn btn-sm btn-warning btn-status" data-id="${row.id}" data-status="${row.status}" title="Nonaktifkan"><i class="bx bx-power-off"></i></button>`
            : `<button type="button" class="btn btn-sm btn-success btn-status" data-id="${row.id}" data-status="${row.status}" title="Aktifkan"><i class="bx bx-power-off"></i></button>`;

          const deleteBtn = isCurrentUser
            ? ""
            : `<button type="button" class="btn btn-sm btn-danger btn-delete" data-id="${row.id}" data-nama="${row.nama}" title="Hapus"><i class="bx bx-trash"></i></button>`;

          return `
            <div class="d-flex gap-1">
                <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="${row.id}" data-nama="${row.nama}" data-username="${row.username}" data-email="${row.email}" data-role="${row.role}" title="Edit"><i class="bx bx-edit"></i></button>
                ${statusBtn}
                ${deleteBtn}
            </div>
          `;
        },
      },
    ],
    columnDefs: [
      {
        targets: [0, 5, 6],
        orderable: false,
      },
    ],
  });

  // Reset form dan validasi
  function resetForm() {
    $("#formUser")[0].reset();
    $("#id").val("");
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").text("");
    $("#passwordHint").show();
  }

  // Event klik tombol Tambah User
  $("#btnAddUser").on("click", function () {
    resetForm();
    $("#modalTitle").text("Tambah User");
    $("#password, #confirm_password").attr("required", true);
    $("#userModal").modal("show");
  });

  // Event submit form
  $("#formUser").on("submit", function (e) {
    e.preventDefault();

    const id = $("#id").val();
    const url = id
      ? baseUrl + "/admin/user/update"
      : baseUrl + "/admin/user/save";
    const formData = $(this).serialize();

    $.ajax({
      url: url,
      type: "POST",
      data: formData,
      dataType: "json",
      beforeSend: function () {
        $("#btnSave")
          .prop("disabled", true)
          .html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
          );
      },
      complete: function () {
        $("#btnSave").prop("disabled", false).html("Simpan");
      },
      success: function (response) {
        if (response.status) {
          Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: response.message,
            timer: 1500,
            showConfirmButton: false,
          });

          $("#userModal").modal("hide");
          table.ajax.reload();
        }
      },
      error: function (xhr, status, error) {
        if (xhr.status === 400) {
          const errors = xhr.responseJSON.messages;
          if (errors) {
            // Reset semua validasi
            $(".is-invalid").removeClass("is-invalid");
            $(".invalid-feedback").text("");

            // Tampilkan pesan error untuk setiap field
            Object.keys(errors).forEach(function (key) {
              $(`#${key}`).addClass("is-invalid");
              $(`#${key}-feedback`).text(errors[key]);
            });
          }
        } else {
          Swal.fire({
            icon: "error",
            title: "Error!",
            text:
              xhr.responseJSON?.messages ||
              "Terjadi kesalahan saat menyimpan data",
          });
        }
      },
    });
  });

  // Event klik tombol Edit
  $("#userTable").on("click", ".btn-edit", function () {
    resetForm();

    const id = $(this).data("id");
    const nama = $(this).data("nama");
    const username = $(this).data("username");
    const email = $(this).data("email");
    const role = $(this).data("role");

    $("#id").val(id);
    $("#nama").val(nama);
    $("#username").val(username);
    $("#email").val(email);
    $("#role").val(role);

    // Password tidak wajib saat edit
    $("#password, #confirm_password").attr("required", false);
    $("#passwordHint").text("Kosongkan jika tidak ingin mengubah password");

    $("#modalTitle").text("Edit User");
    $("#userModal").modal("show");
  });

  // Event klik tombol Status
  $("#userTable").on("click", ".btn-status", function () {
    const id = $(this).data("id");
    const status = $(this).data("status");
    const newStatus = status == 1 ? "nonaktifkan" : "aktifkan";

    Swal.fire({
      title: "Konfirmasi",
      text: `Apakah Anda yakin ingin ${newStatus} user ini?`,
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Ya, Lanjutkan",
      cancelButtonText: "Batal",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: baseUrl + "/admin/user/changeStatus",
          type: "POST",
          data: { id: id },
          dataType: "json",
          success: function (response) {
            if (response.status) {
              Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: response.message,
                timer: 1500,
                showConfirmButton: false,
              });

              table.ajax.reload();
            }
          },
          error: function (xhr, status, error) {
            Swal.fire({
              icon: "error",
              title: "Error!",
              text:
                xhr.responseJSON?.messages ||
                "Terjadi kesalahan saat mengubah status",
            });
          },
        });
      }
    });
  });

  // Event klik tombol Delete
  $("#userTable").on("click", ".btn-delete", function () {
    const id = $(this).data("id");
    const nama = $(this).data("nama");

    Swal.fire({
      title: "Konfirmasi",
      text: `Apakah Anda yakin ingin menghapus user "${nama}"?`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Ya, Hapus",
      cancelButtonText: "Batal",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: baseUrl + "/admin/user/delete",
          type: "POST",
          data: { id: id },
          dataType: "json",
          success: function (response) {
            if (response.status) {
              Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: response.message,
                timer: 1500,
                showConfirmButton: false,
              });

              table.ajax.reload();
            }
          },
          error: function (xhr, status, error) {
            Swal.fire({
              icon: "error",
              title: "Error!",
              text:
                xhr.responseJSON?.messages ||
                "Terjadi kesalahan saat menghapus data",
            });
          },
        });
      }
    });
  });
});
