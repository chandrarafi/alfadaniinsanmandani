$(document).ready(function () {
  // Inisialisasi date picker
  $(".datepicker").pickadate({
    format: "yyyy-mm-dd",
    selectMonths: true,
    selectYears: true,
    clear: "Hapus",
    close: "OK",
    today: "Hari ini",
    closeOnSelect: true,
    closeOnClear: true,
  });

  // Preview foto sebelum upload
  $("#foto").change(function () {
    if (this.files && this.files[0]) {
      const reader = new FileReader();
      reader.onload = function (e) {
        $("#fotoPreviewContainer").removeClass("d-none");
        $("#fotoPreview").attr("src", e.target.result);
      };
      reader.readAsDataURL(this.files[0]);
    }
  });

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
  }

  // Reset validasi
  function resetValidation() {
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").text("");
  }

  // Event submit form
  $("#formPaket").on("submit", function (e) {
    e.preventDefault();
    resetValidation();

    // Gunakan FormData untuk mengirim data termasuk file
    const formData = new FormData(this);
    const url = isEdit
      ? baseUrl + "/admin/paket/update"
      : baseUrl + "/admin/paket/save";

    $.ajax({
      url: url,
      type: "POST",
      data: formData,
      dataType: "json",
      contentType: false,
      processData: false,
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
          }).then(() => {
            window.location.href = baseUrl + "/admin/paket";
          });
        }
      },
      error: function (xhr, status, error) {
        if (xhr.status === 400) {
          const errors = xhr.responseJSON.messages;
          if (errors) {
            // Tampilkan pesan error untuk setiap field
            Object.keys(errors).forEach(function (key) {
              $(`#${key}`).addClass("is-invalid");
              $(`#${key}-feedback`).text(errors[key]);
            });

            // Scroll ke error pertama
            const firstError = Object.keys(errors)[0];
            if (firstError) {
              $([document.documentElement, document.body]).animate(
                {
                  scrollTop: $(`#${firstError}`).offset().top - 100,
                },
                500
              );
            }
          }
        } else {
          showAlert(
            "error",
            xhr.responseJSON?.messages ||
              "Terjadi kesalahan saat menyimpan data"
          );
        }
      },
    });
  });
});
