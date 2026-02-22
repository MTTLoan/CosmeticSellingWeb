document.addEventListener("DOMContentLoaded", function () {
    // Lắng nghe sự kiện nhấn nút
    document.getElementById("btnExit").addEventListener("click", function () {
      window.history.back(); // Quay trở lại trang trước đó
    });
  });