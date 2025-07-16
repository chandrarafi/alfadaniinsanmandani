/**
 * Countdown Timer untuk halaman pembayaran
 *
 * Menampilkan countdown timer untuk batas waktu pembayaran
 */

class CountdownTimer {
  /**
   * Inisialisasi countdown timer
   *
   * @param {string} elementId - ID elemen untuk menampilkan countdown
   * @param {string} containerId - ID elemen container countdown
   * @param {string} expiredAtStr - Waktu expired dalam format YYYY-MM-DD HH:MM:SS
   * @param {string} formId - ID form pembayaran yang akan dinonaktifkan jika waktu habis
   */
  constructor(elementId, containerId, expiredAtStr, formId = "formPembayaran") {
    this.countdownEl = document.getElementById(elementId);
    this.containerEl = document.getElementById(containerId);
    this.formEl = document.getElementById(formId);
    this.expiredAt = this.parseExpiredTime(expiredAtStr);
    this.interval = null;

    // Periksa apakah sudah expired saat inisialisasi
    this.checkIfAlreadyExpired();
  }

  /**
   * Parse waktu expired dengan format yang benar
   *
   * @param {string} expiredAtStr - Waktu expired dalam format YYYY-MM-DD HH:MM:SS
   * @returns {Date} - Objek Date yang sudah diparse
   */
  parseExpiredTime(expiredAtStr) {
    // Format waktu dari server: YYYY-MM-DD HH:MM:SS
    // Konversi ke format ISO dengan timezone Asia/Jakarta (+07:00)
    return new Date(expiredAtStr.replace(" ", "T") + "+07:00");
  }

  /**
   * Periksa apakah waktu sudah expired saat halaman dimuat
   */
  checkIfAlreadyExpired() {
    const now = new Date();
    const distance = this.expiredAt - now;

    if (distance <= 0) {
      this.handleExpired();
      return true;
    }
    return false;
  }

  /**
   * Mulai countdown timer
   */
  start() {
    if (!this.countdownEl) return;

    // Jika sudah expired, tidak perlu memulai countdown
    if (this.checkIfAlreadyExpired()) {
      return;
    }

    console.log("Expired at:", this.expiredAt.toISOString());

    this.interval = setInterval(() => this.update(), 1000);
    this.update(); // Update pertama kali
  }

  /**
   * Update tampilan countdown
   */
  update() {
    const now = new Date();
    const distance = this.expiredAt - now;

    console.log("Current time:", now.toISOString(), "Distance (ms):", distance);

    if (distance <= 0) {
      this.handleExpired();
      return;
    }

    const minutes = Math.floor(distance / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    this.countdownEl.textContent =
      (minutes < 10 ? "0" + minutes : minutes) +
      ":" +
      (seconds < 10 ? "0" + seconds : seconds);

    // Ubah warna jika waktu hampir habis (kurang dari 2 menit)
    if (distance < 2 * 60 * 1000) {
      this.countdownEl.classList.remove("text-yellow-800");
      this.countdownEl.classList.add("text-red-800");
    }
  }

  /**
   * Tangani ketika waktu habis
   */
  handleExpired() {
    clearInterval(this.interval);
    this.countdownEl.textContent = "00:00";

    // Ubah warna container
    this.containerEl.classList.remove("bg-yellow-50", "border-yellow-200");
    this.containerEl.classList.add("bg-red-50", "border-red-200");

    // Ubah warna teks
    this.countdownEl.classList.remove("text-yellow-800");
    this.countdownEl.classList.add("text-red-800");

    // Nonaktifkan form pembayaran jika ada
    if (this.formEl) {
      // Nonaktifkan semua input dan button di dalam form
      const inputs = this.formEl.querySelectorAll(
        "input, select, textarea, button"
      );
      inputs.forEach((input) => {
        input.disabled = true;
      });

      // Tambahkan overlay ke form untuk menunjukkan bahwa form tidak aktif
      const formContainer = this.formEl.closest(".border");
      if (formContainer) {
        formContainer.style.position = "relative";
        const overlay = document.createElement("div");
        overlay.style.position = "absolute";
        overlay.style.top = "0";
        overlay.style.left = "0";
        overlay.style.width = "100%";
        overlay.style.height = "100%";
        overlay.style.backgroundColor = "rgba(244, 63, 94, 0.1)";
        overlay.style.zIndex = "10";
        overlay.style.display = "flex";
        overlay.style.alignItems = "center";
        overlay.style.justifyContent = "center";

        const message = document.createElement("div");
        message.textContent = "Waktu pembayaran telah berakhir";
        message.style.padding = "1rem";
        message.style.backgroundColor = "white";
        message.style.color = "#dc2626";
        message.style.borderRadius = "0.5rem";
        message.style.fontWeight = "bold";

        overlay.appendChild(message);
        formContainer.appendChild(overlay);
      }
    }

    // Tampilkan pesan
    Swal.fire({
      icon: "error",
      title: "Waktu Habis",
      text: "Batas waktu pembayaran telah berakhir. Pendaftaran akan dibatalkan.",
      confirmButtonColor: "#4F46E5",
    }).then(
      function () {
        // Kirim request ke server untuk update status pendaftaran
        this.updatePendaftaranStatus();

        // Reload halaman setelah 2 detik
        setTimeout(() => {
          window.location.reload();
        }, 2000);
      }.bind(this)
    );
  }

  /**
   * Update status pendaftaran di server
   */
  updatePendaftaranStatus() {
    // Ambil ID pendaftaran dari URL
    const urlParts = window.location.pathname.split("/");
    const pendaftaranId = urlParts[urlParts.length - 1];

    if (!pendaftaranId) return;

    // Kirim request ke server untuk update status
    const baseUrl =
      document
        .querySelector('meta[name="base-url"]')
        ?.getAttribute("content") || "";
    fetch(`${baseUrl}/jamaah/update-pendaftaran-status/${pendaftaranId}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify({ status: "cancelled" }),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log("Status updated:", data);
      })
      .catch((error) => {
        console.error("Error updating status:", error);
      });
  }

  /**
   * Hentikan countdown timer
   */
  stop() {
    if (this.interval) {
      clearInterval(this.interval);
    }
  }
}
