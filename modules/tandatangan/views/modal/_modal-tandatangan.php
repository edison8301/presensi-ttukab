<div class="modal fade " id="modal-tandatangan" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Tandatangan</h4>
            </div>
            <div class="modal-body">
                <b-alert v-model="showErrorAlert" variant="danger" dismissible>
                    {{ message }}
                </b-alert>
                <b-alert v-model="showSuccessAlert" variant="success" dismissible>
                    {{ message }}
                </b-alert>
                <b-form>
                    <b-form-group label="Passphrase" label-for="passphrase">
                        <b-form-input type="password" id="passphrase" name="passphrase" v-model="passphrase" autocomplete="off"></b-form-input>
                    </b-form-group>
                </b-form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-flat" @click="saveTandatangan()">Simpan</button>
            </div>
        </div>
    </div>
</div>
