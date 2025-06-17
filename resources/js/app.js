require("./bootstrap");

import moment from "moment";

function ucfirst(string) {
    if (!string) return ""; // Pastikan string tidak null atau undefined
    return string.charAt(0).toUpperCase() + string.slice(1);
}

window.Echo.channel("material-requests").listen(
    "MaterialRequestUpdated",
    (e) => {
        if (Array.isArray(e.materialRequest)) {
            e.materialRequest.forEach((request) => {
                updateDataTable(request);
                showToast(request, e.action);
                playNotificationSound();
            });
        } else {
            updateDataTable(e.materialRequest);
            showToast(e.materialRequest, e.action);
            playNotificationSound();
        }
    }
);

let audioContext;
let audioBuffer;

function initializeAudio() {
    // Inisialisasi AudioContext
    audioContext = new (window.AudioContext || window.webkitAudioContext)();

    // Ambil file audio dan decode menjadi buffer
    fetch("/sounds/notification.mp3") // Pastikan path ini sesuai dengan lokasi file audio Anda
        .then((response) => response.arrayBuffer())
        .then((data) => audioContext.decodeAudioData(data))
        .then((buffer) => {
            audioBuffer = buffer;
        })
        .catch((error) => {
            console.error("Failed to load audio file:", error);
        });
}

document.body.addEventListener(
    "click",
    () => {
        if (audioContext && audioContext.state === "suspended") {
            audioContext.resume();
        }
    },
    { once: true }
);

function playNotificationSound() {
    if (!audioContext || !audioBuffer) return;

    // Buat sumber audio baru
    const source = audioContext.createBufferSource();
    source.buffer = audioBuffer;
    source.connect(audioContext.destination);
    source.start(0);
}

function showToast(materialRequest, action) {
    const toastContainer = document.getElementById("toast-container");
    const toastTemplate = document.getElementById("toast-template");

    // Clone elemen template toast
    const toastElement = toastTemplate.cloneNode(true);
    toastElement.classList.remove("d-none"); // Tampilkan toast
    toastElement.classList.add("toast"); // Tambahkan kelas toast

    // Tentukan pesan berdasarkan jenis aksi
    let message = "";
    if (action === "created") {
        message = `
            <strong>${ucfirst(materialRequest.requested_by)} (${ucfirst(
            materialRequest.department
        )})</strong><br>
            New Request: <strong>${
                materialRequest.inventory?.name || "N/A"
            }</strong>
            for <strong>${materialRequest.project?.name || "N/A"}</strong><br>
            <a href="/material_requests/${
                materialRequest.id
            }/edit" class="text-primary">View More...</a>
        `;
    } else if (action === "updated") {
        message = `
            <strong>${ucfirst(materialRequest.requested_by)} (${ucfirst(
            materialRequest.department
        )})</strong><br>
            Material Request: <strong>${
                materialRequest.inventory?.name || "N/A"
            }</strong>
            for <strong>${
                materialRequest.project?.name || "N/A"
            }</strong> has been updated.<br>
            <a href="/material_requests/${
                materialRequest.id
            }/edit" class="text-primary">View More...</a>
        `;
    } else if (action === "deleted") {
        message = `
            <strong>${ucfirst(materialRequest.requested_by)} (${ucfirst(
            materialRequest.department
        )})</strong><br>
            Material Request: <strong>${
                materialRequest.inventory?.name || "N/A"
            }</strong>
            for <strong>${
                materialRequest.project?.name || "N/A"
            }</strong> has been deleted.
        `;
    }

    // Isi konten toast
    toastElement.querySelector(".toast-time").textContent = moment(
        materialRequest.created_at
    ).fromNow();
    toastElement.querySelector(".toast-body").innerHTML = message;

    // Tambahkan toast ke dalam container
    toastContainer.appendChild(toastElement);

    // Tampilkan toast dengan opsi autohide: false
    const toast = new bootstrap.Toast(toastElement, {
        autohide: false, // Toast tidak akan hilang otomatis
    });
    toast.show();

    // Putar suara notifikasi
    playNotificationSound();

    // Hapus toast dari DOM jika tombol silang diklik
    toastElement.addEventListener("hidden.bs.toast", () => {
        toastElement.remove();
    });
}

function updateSelectColor(selectElement) {
    const selectedValue = selectElement.value;
    if (selectedValue === "pending") {
        selectElement.classList.add("status-pending");
    } else if (selectedValue === "approved") {
        selectElement.classList.add("status-approved");
    } else if (selectedValue === "delivered") {
        selectElement.classList.add("status-delivered");
    } else if (selectedValue === "canceled") {
        selectElement.classList.add("status-canceled");
    }
}

// Terapkan fungsi ke semua elemen <select> dengan kelas .status-select
document.addEventListener("DOMContentLoaded", () => {
    const statusSelectElements = document.querySelectorAll(".status-select");
    statusSelectElements.forEach((selectElement) => {
        // Perbarui warna saat halaman dimuat
        updateSelectColor(selectElement);

        // Perbarui warna saat nilai berubah
        selectElement.addEventListener("change", () => {
            updateSelectColor(selectElement);
        });
    });
});

function updateDataTable(materialRequest) {
    const table = $("#datatable").DataTable();
    const row = table.row(`#row-${materialRequest.id}`);

    // Logika untuk kolom status
    let statusColumn = materialRequest.status;
    if (["admin_logistic", "super_admin"].includes(authUserRole)) {
        statusColumn = `
           <form method="POST" action="/material_requests/${
               materialRequest.id
           }">
               <input type="hidden" name="_token" value="${$(
                   'meta[name="csrf-token"]'
               ).attr("content")}">
               <input type="hidden" name="_method" value="PUT">
               <select name="status" class="form-select form-select-sm status-select" onchange="this.form.submit()">
                   <option value="pending" ${
                       materialRequest.status === "pending" ? "selected" : ""
                   }>Pending</option>
                   <option value="approved" ${
                       materialRequest.status === "approved" ? "selected" : ""
                   }>Approved</option>
                   <option value="delivered" ${
                       materialRequest.status === "delivered" ? "selected" : ""
                   }>Delivered</option>
                   <option value="canceled" ${
                       materialRequest.status === "canceled" ? "selected" : ""
                   }>Canceled</option>
               </select>
           </form>
       `;
    } else {
        const badgeClass =
            materialRequest.status === "pending"
                ? "text-bg-warning"
                : materialRequest.status === "approved"
                ? "text-bg-primary"
                : materialRequest.status === "delivered"
                ? "text-bg-success"
                : materialRequest.status === "canceled"
                ? "text-bg-danger"
                : "";

        statusColumn = `<span class="badge ${badgeClass}">${ucfirst(
            materialRequest.status
        )}</span>`;
    }

    // Logika untuk checkbox
    let checkboxColumn = "";
    if (materialRequest.status === "approved") {
        checkboxColumn = `<input type="checkbox" class="select-row" value="${materialRequest.id}">`;
    }

    // Logika untuk tombol Goods Out
    let actionColumn = `
        <div class="d-flex flex-wrap gap-1">
            <a href="/material_requests/${materialRequest.id}/edit" class="btn btn-sm btn-primary">Edit</a>
    `;
    if (materialRequest.status === "approved") {
        actionColumn += `
            <a href="/goods_out/create/${materialRequest.id}" class="btn btn-sm btn-success">Goods Out</a>
        `;
    }
    if (materialRequest.status === "canceled") {
        actionColumn = `<span class="text-muted">No actions available</span>`;
    }
    actionColumn += `
            <form action="/material_requests/${
                materialRequest.id
            }" method="POST" class="delete-form">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="${$(
                    'meta[name="csrf-token"]'
                ).attr("content")}">
                <button type="button" class="btn btn-sm btn-danger btn-delete">Delete</button>
            </form>
        </div>
    `;

    const formattedDate = moment(materialRequest.created_at).format(
        "YYYY-MM-DD, HH:mm"
    );

    const rowData = [
        checkboxColumn, // Checkbox
        materialRequest.project?.name || "N/A", // Project
        materialRequest.inventory?.name || "N/A", // Material
        `${materialRequest.qty} ${materialRequest.inventory?.unit || ""}`, // Requested Qty
        `${materialRequest.processed_qty} ${
            materialRequest.inventory?.unit || ""
        }`, // Processed Qty
        `${materialRequest.qty - materialRequest.processed_qty} ${
            materialRequest.inventory?.unit || ""
        }`, // Remaining Qty
        `${ucfirst(materialRequest.requested_by)} (${ucfirst(
            materialRequest.department
        )})`, // Requested By
        formattedDate, // Requested At (format lokal)
        statusColumn, // Status
        materialRequest.remark || "-", // Remark
        actionColumn, // Action
    ];

    if (!row.node()) {
        table.row.add(rowData).draw();
        table.order([7, "desc"]).draw(); // Urutkan ulang tabel berdasarkan kolom `Requested At`
        return;
    }

    row.data(rowData).draw();
    table.order([7, "desc"]).draw(); // Urutkan ulang tabel setelah pembaruan

    // Perbarui warna elemen <select> setelah elemen ditambahkan
    const selectElement = row.node().querySelector(".status-select");
    if (selectElement) {
        updateSelectColor(selectElement);
    } else {
        console.error("Select element not found in row:", row.node()); // Debug log
    }
}

document.addEventListener("DOMContentLoaded", () => {
    initializeAudio();
});

$("#datatable").on("draw.dt", function () {
    const statusSelectElements = document.querySelectorAll(".status-select");
    statusSelectElements.forEach((selectElement) => {
        updateSelectColor(selectElement);
    });
});
