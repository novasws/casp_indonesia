/**
 * CASP Indonesia – Global JavaScript
 * Berisi helper CSRF, toast notification, dan utility umum.
 */

"use strict";

/* ============================================================
   CSRF HELPER
   Otomatis membaca meta tag csrf-token dan menyuntikkan
   ke setiap request fetch/XHR.
   ============================================================ */
window.CaspHttp = {
  csrfToken: () =>
    document.querySelector('meta[name="csrf-token"]')?.content ?? "",

  /**
   * Wrapper fetch dengan CSRF token dan JSON header siap pakai.
   * @param {string} url
   * @param {object} options
   * @returns {Promise<Response>}
   */
  async post(url, body = {}, options = {}) {
    return fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": this.csrfToken(),
        ...options.headers,
      },
      body: JSON.stringify(body),
      ...options,
    });
  },

  async get(url, options = {}) {
    return fetch(url, {
      method: "GET",
      headers: {
        Accept: "application/json",
        "X-CSRF-TOKEN": this.csrfToken(),
        ...options.headers,
      },
      ...options,
    });
  },
};

/* ============================================================
   TOAST NOTIFICATION
   Gunakan: CaspToast.show('Berhasil!', 'success')
   ============================================================ */
window.CaspToast = {
  container: null,

  init() {
    if (this.container) return;
    this.container = document.createElement("div");
    this.container.className = "toast-container";
    document.body.appendChild(this.container);
  },

  show(message, type = "default", duration = 3500) {
    this.init();
    const toast = document.createElement("div");
    toast.className = `toast ${type}`;
    toast.textContent = message;
    this.container.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = "0";
      toast.style.transition = "opacity 0.3s";
      setTimeout(() => toast.remove(), 300);
    }, duration);
  },

  success(msg) {
    this.show(msg, "success");
  },
  error(msg) {
    this.show(msg, "error");
  },
};

/* ============================================================
   FORMAT RUPIAH
   Gunakan: CaspUtil.rupiah(90000) → "Rp 90.000"
   ============================================================ */
window.CaspUtil = {
  rupiah(angka) {
    return "Rp " + Number(angka).toLocaleString("id-ID");
  },

  fmtTime(detik) {
    const m = Math.floor(detik / 60);
    const s = detik % 60;
    return String(m).padStart(2, "0") + ":" + String(s).padStart(2, "0");
  },

  nowWIB() {
    const d = new Date();
    return (
      d.getHours().toString().padStart(2, "0") +
      "." +
      d.getMinutes().toString().padStart(2, "0")
    );
  },

  /**
   * Debounce – batasi frekuensi pemanggilan fungsi.
   */
  debounce(fn, delay = 300) {
    let timer;
    return (...args) => {
      clearTimeout(timer);
      timer = setTimeout(() => fn(...args), delay);
    };
  },
};

/* ============================================================
   INIT: jalankan saat DOM siap
   ============================================================ */
document.addEventListener("DOMContentLoaded", () => {
  // Suntikkan CSRF ke semua form HTML biasa (non-Ajax)
  document.querySelectorAll("form:not([data-no-csrf])").forEach((form) => {
    if (!form.querySelector('input[name="_token"]')) {
      const input = document.createElement("input");
      input.type = "hidden";
      input.name = "_token";
      input.value = CaspHttp.csrfToken();
      form.appendChild(input);
    }
  });

  // Smooth scroll untuk link anchor (#...)
  document.querySelectorAll('a[href^="#"]').forEach((link) => {
    link.addEventListener("click", (e) => {
      const target = document.querySelector(link.getAttribute("href"));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: "smooth", block: "start" });
      }
    });
  });
});
