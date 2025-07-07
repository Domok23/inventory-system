require("./bootstrap");

import moment from "moment";

// --- Fungsi-fungsi utilitas ---
function ucfirst(string) {
    if (!string) return ""; // Pastikan string tidak null atau undefined
    return string.charAt(0).toUpperCase() + string.slice(1);
}

// --- Audio ---
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
function playNotificationSound() {
    if (!audioContext || !audioBuffer) return;
    // Buat sumber audio baru
    const source = audioContext.createBufferSource();
    source.buffer = audioBuffer;
    source.connect(audioContext.destination);
    source.start(0);
}

// --- Toast ---
function showToast(materialRequest, action, playSound = true) {
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
    } else {
        // Jika action tidak dikenali, jangan tampilkan toast
        return;
    }

    // Isi konten toast
    toastElement.querySelector(".toast-time").textContent = moment(
        materialRequest.created_at
    ).fromNow();
    toastElement.querySelector(".toast-body").innerHTML = message;

    // Tambahkan toast ke dalam container
    toastContainer.appendChild(toastElement);

    // Tampilkan toast dengan opsi autohide: true
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true, // Atur autohide sesuai kebutuhan
        delay: 10000, // Tampilkan selama 15 detik
    });
    toast.show();

    // Putar suara notifikasi jika diizinkan
    if (playSound) {
        playNotificationSound();
    }

    // Hapus toast dari DOM jika tombol silang diklik
    toastElement.addEventListener("hidden.bs.toast", () => {
        toastElement.remove();
    });
}

// --- DataTable & Select Color ---
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

    // Logika untuk tombol Goods Out, Edit, Delete
    // Ambil data user yang sedang login (pastikan variabel ini sudah di-set di window, misal: window.authUser)
    const authUser = window.authUser || {};
    const isLogisticAdmin = !!authUser.is_logistic_admin;
    const isSuperAdmin = !!authUser.is_super_admin;
    const isRequestOwner = authUser.username === materialRequest.requested_by;

    let actionColumn = `<div class="d-flex flex-nowrap gap-1">`;

    // Goods Out
    if (
        materialRequest.status === "approved" &&
        materialRequest.status !== "canceled" &&
        isLogisticAdmin
    ) {
        actionColumn += `
            <a href="/goods_out/create/${materialRequest.id}" class="btn btn-sm btn-success" title="Goods Out">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        `;
    }

    // Edit
    if (
        materialRequest.status !== "canceled" &&
        (isRequestOwner || isLogisticAdmin)
    ) {
        actionColumn += `
            <a href="/material_requests/${materialRequest.id}/edit" class="btn btn-sm btn-warning" title="Edit">
                <i class="bi bi-pencil-square"></i>
            </a>
        `;
    }

    // Delete
    if (
        materialRequest.status !== "canceled" &&
        (isRequestOwner || isSuperAdmin)
    ) {
        actionColumn += `
            <form action="/material_requests/${
                materialRequest.id
            }" method="POST" class="delete-form" style="display:inline;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="${$(
                    'meta[name="csrf-token"]'
                ).attr("content")}">
                <button type="button" class="btn btn-sm btn-danger btn-delete" title="Delete">
                    <i class="bi bi-trash3"></i>
                </button>
            </form>
        `;
    }

    // Jika status canceled, tampilkan info
    if (materialRequest.status === "canceled") {
        actionColumn = `<span class="text-muted">No actions available</span>`;
    } else {
        actionColumn += `</div>`;
    }

    const formattedDate = moment(materialRequest.created_at).format(
        "YYYY-MM-DD, HH:mm"
    );

    const rowData = [
        checkboxColumn, // Checkbox
        materialRequest.project?.name || "N/A", // Project
        materialRequest.inventory?.name || "N/A", // Material
        `${materialRequest.qty} ${materialRequest.inventory?.unit || ""}`, // Requested Qty
        `${materialRequest.qty - (materialRequest.processed_qty ?? 0)} ${
            materialRequest.inventory?.unit || ""
        }`, // Remaining Qty
        `${materialRequest.processed_qty ?? 0} ${
            materialRequest.inventory?.unit || ""
        }`, // Processed Qty
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

// --- Event DOMContentLoaded ---
document.addEventListener("DOMContentLoaded", () => {
    initializeAudio();

    // Listener real-time toast & suara: SELALU AKTIF di semua halaman
    window.Echo.channel("material-requests").listen(
        "MaterialRequestUpdated",
        (e) => {
            function handleRequest(request) {
                // Hanya update DataTable jika tabel material request ada
                const materialRequestTable = document.querySelector(
                    '#datatable[data-material-request-table="1"]'
                );
                if (materialRequestTable) {
                    if (e.action === "deleted") {
                        const table = $("#datatable").DataTable();
                        const row = table.row(`#row-${request.id}`);
                        if (row.node()) {
                            row.remove().draw();
                        }
                    } else {
                        updateDataTable(request);
                    }
                }
                // Toast & suara: SELALU tampil di semua halaman
                if (e.action !== "status") {
                    showToast(request, e.action, true);
                }
            }

            if (Array.isArray(e.materialRequest)) {
                e.materialRequest.forEach(handleRequest);
            } else {
                handleRequest(e.materialRequest);
            }
        }
    );

    // Hanya jalankan select color logic jika tabel material request ada
    const materialRequestTable = document.querySelector(
        '#datatable[data-material-request-table="1"]'
    );
    if (materialRequestTable) {
        // Terapkan fungsi ke semua elemen <select> dengan kelas .status-select
        const statusSelectElements =
            document.querySelectorAll(".status-select");
        statusSelectElements.forEach((selectElement) => {
            updateSelectColor(selectElement);
            selectElement.addEventListener("change", () => {
                updateSelectColor(selectElement);
            });
        });
    }
});

// --- Event DataTable redraw ---
$("#datatable").on("draw.dt", function () {
    const statusSelectElements = document.querySelectorAll(".status-select");
    statusSelectElements.forEach((selectElement) => {
        updateSelectColor(selectElement);
    });
});

// --- Audio resume on user interaction ---
document.body.addEventListener(
    "click",
    () => {
        if (audioContext && audioContext.state === "suspended") {
            audioContext.resume();
        }
    },
    { once: true }
);
