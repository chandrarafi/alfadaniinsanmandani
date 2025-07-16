/**
 * Script untuk memeriksa pendaftaran yang sudah expired
 *
 * Dijalankan setiap 1 menit untuk memeriksa pendaftaran yang sudah expired
 * dan membatalkannya secara otomatis
 */

class ExpiredChecker {
  /**
   * Inisialisasi checker
   *
   * @param {string} url - URL endpoint untuk memeriksa pendaftaran expired
   * @param {number} interval - Interval waktu dalam milidetik (default: 60000 = 1 menit)
   */
  constructor(url, interval = 60000) {
    this.url = url;
    this.interval = interval;
    this.timer = null;
  }

  /**
   * Mulai pemeriksaan berkala
   */
  start() {
    // Jalankan pemeriksaan pertama kali
    this.check();

    // Set interval untuk pemeriksaan berkala
    this.timer = setInterval(() => this.check(), this.interval);

    console.log("ExpiredChecker started with interval:", this.interval, "ms");
  }

  /**
   * Periksa pendaftaran yang sudah expired
   */
  check() {
    console.log("Checking for expired pendaftaran...");

    fetch(this.url)
      .then((response) => response.json())
      .then((data) => {
        if (data.status) {
          console.log("Expired check result:", data.message);
        } else {
          console.error("Failed to check expired pendaftaran:", data.message);
        }
      })
      .catch((error) => {
        console.error("Error checking expired pendaftaran:", error);
      });
  }

  /**
   * Hentikan pemeriksaan berkala
   */
  stop() {
    if (this.timer) {
      clearInterval(this.timer);
      this.timer = null;
      console.log("ExpiredChecker stopped");
    }
  }
}

// Inisialisasi dan jalankan checker jika halaman ini diakses oleh admin
document.addEventListener("DOMContentLoaded", function () {
  // Cek apakah user adalah admin
  const isAdmin = document.body.classList.contains("admin-role");

  if (isAdmin) {
    const baseUrl =
      document
        .querySelector('meta[name="base-url"]')
        ?.getAttribute("content") || "";
    const expiredChecker = new ExpiredChecker(
      `${baseUrl}/admin/check-expired-pendaftaran`
    );
    expiredChecker.start();
  }
});
