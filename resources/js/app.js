require("./bootstrap");

import moment from "moment";

window.Echo.channel("material-requests").listen(
    "MaterialRequestUpdated",
    (e) => {
        if (Array.isArray(e.materialRequest)) {
            // Jika menerima array material request (bulk create)
            e.materialRequest.forEach((request) => {
                updateDataTable(request);
                showToast(request, e.action);
            });
        } else {
            // Jika menerima single material request
            updateDataTable(e.materialRequest);
            showToast(e.materialRequest, e.action);
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
            <strong>${materialRequest.requested_by}</strong><br>
            New Material Request: <strong>${
                materialRequest.inventory?.name || "N/A"
            }</strong>
            for <strong>${materialRequest.project?.name || "N/A"}</strong><br>
            <a href="/material_requests/${
                materialRequest.id
            }/edit" class="text-primary">View More...</a>
        `;
    } else if (action === "updated") {
        message = `
            <strong>${materialRequest.requested_by}</strong><br>
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
            <strong>${materialRequest.requested_by}</strong><br>
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

function updateDataTable(materialRequest) {
    console.log("Updating datatable with:", materialRequest);

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
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="pending" ${
                        materialRequest.status === "pending" ? "selected" : ""
                    }>Pending</option>
                    <option value="approved" ${
                        materialRequest.status === "approved" ? "selected" : ""
                    }>Approved</option>
                    <option value="delivered" ${
                        materialRequest.status === "delivered" ? "selected" : ""
                    }>Delivered</option>
                </select>
            </form>
        `;
    }

    const formattedDate = moment(materialRequest.created_at).format(
        "DD-MM-YYYY, HH:mm"
    );

    const rowData = [
        "", // Kolom untuk checkbox (kosong jika tidak digunakan)
        materialRequest.project?.name || "N/A",
        materialRequest.inventory?.name || "N/A",
        `${materialRequest.qty} ${materialRequest.inventory?.unit || ""}`,
        `${materialRequest.requested_by} (${materialRequest.department})`,
        statusColumn, // Kolom status dengan logika tambahan
        materialRequest.remark || "-",
        formattedDate,
        `<div class="d-flex flex-wrap gap-1">
            <a href="/material_requests/${
                materialRequest.id
            }/edit" class="btn btn-sm btn-primary">Edit</a>
            <form action="/material_requests/${
                materialRequest.id
            }" method="POST" class="delete-form">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="${$(
                    'meta[name="csrf-token"]'
                ).attr("content")}">
                <button type="button" class="btn btn-sm btn-danger btn-delete">Delete</button>
            </form>
        </div>`,
    ];

    if (!row.node()) {
        table.row.add(rowData).draw();
        table.order([7, "desc"]).draw(); // Urutkan ulang tabel berdasarkan kolom `Requested At`
        return;
    }

    row.data(rowData).draw();
    table.order([7, "desc"]).draw(); // Urutkan ulang tabel setelah pembaruan
}

document.addEventListener("DOMContentLoaded", () => {
    initializeAudio();
});
