document.addEventListener("DOMContentLoaded", function () {
    // Lắng nghe sự kiện nhấn nút Hủy
    document.getElementById("btnCancel").addEventListener("click", function () {
        window.history.back();
    });

    // Xử lý hiển thị ảnh đã chọn
    const imageInput = document.getElementById("image");
    const imagePreview = document.getElementById("image-preview");
    let files = [];

    imageInput.addEventListener("change", function () {
        const newFiles = Array.from(this.files);
        files = files.concat(newFiles);

        imagePreview.innerHTML = ""; // Xóa nội dung cũ

        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const imgContainer = document.createElement("div");
                imgContainer.classList.add("img-container");

                const img = document.createElement("img");
                img.src = e.target.result;
                img.classList.add("img-fixed-size");

                const removeBtn = document.createElement("button");
                removeBtn.textContent = "Xóa";
                removeBtn.classList.add("btn", "btn-danger", "btn-sm", "remove-btn");
                removeBtn.addEventListener("click", function () {
                    files.splice(index, 1);
                    imageInput.files = new FileListItems(files);
                    imgContainer.remove();
                });

                imgContainer.appendChild(img);
                imgContainer.appendChild(removeBtn);
                imagePreview.appendChild(imgContainer);
            };
            reader.readAsDataURL(file);
        });
    });

    // Helper function to create a FileList from an array of files
    function FileListItems(files) {
        const b = new ClipboardEvent("").clipboardData || new DataTransfer();
        for (let i = 0, len = files.length; i < len; i++) b.items.add(files[i]);
        return b.files;
    }
});
