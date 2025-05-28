require('./bootstrap');

window.Echo.channel("material-requests").listen(
    "MaterialRequestUpdated",
    (e) => {
        console.log("Material Request Updated:", e.materialRequest);

        // Update datatable secara dinamis
        updateDataTable(e.materialRequest);
    }
);

function updateDataTable(materialRequest) {
    console.log("Updating datatable with:", materialRequest);
    const table = $("#datatable").DataTable(); // Ganti dengan ID datatable Anda
    const row = table.row(`#row-${materialRequest.id}`); // Cari baris berdasarkan ID

    if (row.length) {
        console.log("Row exists, updating...");
        row.data([
            materialRequest.inventory.name,
            materialRequest.project.name,
            materialRequest.qty,
            materialRequest.status,
            materialRequest.remark,
        ]).draw();
    } else {
        console.log("Row does not exist, adding new row...");
        table.row
            .add([
                materialRequest.inventory.name,
                materialRequest.project.name,
                materialRequest.qty,
                materialRequest.status,
                materialRequest.remark,
            ])
            .draw();
    }
}
