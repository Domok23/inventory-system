require("./bootstrap");

import moment from "moment";

window.Echo.channel("material-requests").listen(
    "MaterialRequestUpdated",
    (e) => {
        console.log("Material Request Updated:", e.materialRequest);
        updateDataTable(e.materialRequest);
    }
);

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
        console.log(
            `Row with ID #row-${materialRequest.id} not found. Adding new row.`
        );
        table.row.add(rowData).draw();
        table.order([7, "desc"]).draw(); // Urutkan ulang tabel berdasarkan kolom `Requested At`
        return;
    }

    console.log("Row exists, updating...");
    row.data(rowData).draw();
    table.order([7, "desc"]).draw(); // Urutkan ulang tabel setelah pembaruan
}
