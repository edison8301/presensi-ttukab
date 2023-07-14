function showModalViewBerkas(event, id) {
    console.log("Start window.showModalViewBerkas()");
    event.preventDefault();
    window.app.showModalViewBerkas(id);
}
function showModalTandatangan(event, id, nik) {
    console.log("Start window.showModalTandatangan()");
    event.preventDefault();
    window.app.showModalTandatangan(id, nik);
}
function showModalRiwayat(event, id) {
    console.log("Start window.showModalRiwayat()");
    event.preventDefault();
    window.app.showModalRiwayat(id);
}
function showModalLog(event, id) {
    console.log("Start window.showModalLog()");
    event.preventDefault();
    window.app.showModalLog(id);
}

const app = new Vue({
    el: '#app',
    data: {
        modal_title: '',
        id: '',
        nama: '',
        uraian: '',
        berkas_mentah: '',
        berkas_tandatangan: '',
        id_berkas_status: '',
        nip_tandatangan: '',
        nik: '',
        waktu_tandatangan: '',
        id_aplikasi: '',
        passphrase: '',
        status: '',
        base_url: '',
        data_verifikasi: [],
        link_berkas_mentah: '',
        link_berkas_tandatangan: '',
        message: '',
        showErrorAlert: false,
        showSuccessAlert: false,
        arrayId: [],
        items: null
    },
    mounted() {
        this.base_url = window.base_url;
    },
    methods: {
        showModalViewBerkas(id) {
            this.modal_title = 'Rincian Berkas';
            this.id = id;
            this.updateFormBerkas();
            $('#modal-view-berkas').modal('show');
        },
        showModalTandatangan(id, nik) {
            this.id = id;
            this.nik = nik;
            this.passphrase = '';
            this.modal_title = 'Tandatangan';
            $('#modal-tandatangan').modal('show')
        },
        showModalRiwayat(id) {
            this.modal_title = 'Riwayat Berkas';
            this.id = id;
            this.viewRiwayat();
            $('#modal-riwayat').modal('show');
        },
        showModalLog(id) {
            this.modal_title = 'Log Berkas';
            this.id = id;
            this.viewLog();
            $('#modal-log').modal('show');
        },
        updateFormBerkas() {
            let url = new URL(this.base_url + '/api/berkas/view');

            url.searchParams.append('id', this.id);

            axios.get(url.href).then(response => {
                var data = response.data;
                this.nama = data.nama;
                this.uraian = data.uraian;
                this.berkas_mentah = data.berkas_mentah;
                this.berkas_tandatangan = data.berkas_tandatangan;
                this.nip_tandatangan = data.nip_tandatangan;
                this.waktu_tandatangan = data.waktu_tandatangan;
                this.status = data.berkas_status.nama;
                this.data_verifikasi = data.many_verifikasi;
                this.link_berkas_mentah = this.base_url + '/berkas-mentah/' + this.berkas_mentah;
                this.link_berkas_tandatangan = this.base_url + '/berkas-tandatangan/' + this.berkas_tandatangan;
            }).catch(error => {
                console.log(error.response);
            });
        },
        viewRiwayat() {
            let url = new URL(this.base_url + '/api/riwayat/view');

            url.searchParams.append('id_berkas', this.id);

            axios.get(url.href).then(response => {
                console.log(response.data);
                this.items = response.data;
            });
        },
        viewLog() {
            let url = new URL(this.base_url + '/api/log-signing/view');

            url.searchParams.append('id_berkas', this.id);

            axios.get(url.href).then(response => {
                console.log(response.data);
                this.items = response.data;
            });
        },
        saveTandatangan() {
            console.log("Start saveTandatangan()");
            let url = new URL(this.base_url + '/api/berkas/tandatangan');

            axios.post(url.href, {
                id: this.id,
                nik: this.nik,
                passphrase: this.passphrase
            }).then(response => {
                console.log(response.data.message);
                this.showErrorAlert = false;
                this.showSuccessAlert = true;
                this.message = "Berkas berhasil ditandatangan";
                //this.$refs['modal-tandatangan-checkbox'].hide();
                setTimeout(function() {
                    window.location.reload();
                }, 2500);

                //$('#modal-tandatangan').modal('hide');
                //window.location.reload();
            }).catch(error => {
                this.showErrorAlert = true;
                this.message = error.response.data;
                this.passphrase = '';
            });
        },
    }
});

window.app = app;