<div class="modal fade " id="modal-log" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Log Berkas</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr v-for="log in items">
                        <td width="30%" class="text-center">{{ log.tanggal }}</td>
                        <td>{{ log.nama }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-flat" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
