<div class="modal fade " id="modal-view-berkas" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Rincian Berkas</h4>
            </div>
            <div class="modal-body">
                <table class="table no-border">
                    <tr>
                        <th style="width: 200px;">Nama</th>
                        <td style="width: 20px;">:</td>
                        <td>{{ nama }}</td>
                    </tr>
                    <tr>
                        <th>Uraian</th>
                        <td>:</td>
                        <td>{{ uraian }}</td>
                    </tr>
                    <tr>
                        <th>Berkas Mentah</th>
                        <td>:</td>
                        <td>
                            <a target="_blank" v-bind:href="link_berkas_mentah">
                                <i class="fa fa-download"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Berkas Tandatangan</th>
                        <td>:</td>
                        <td>
                            <div v-if="berkas_tandatangan != null">
                                <a target="_blank" v-bind:href="link_berkas_tandatangan">
                                    <i class="fa fa-download"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>NIP Tandatangan</th>
                        <td>:</td>
                        <td>{{ nip_tandatangan }}</td>
                    </tr>
                    <tr>
                        <th>Waktu Tandatangan</th>
                        <td>:</td>
                        <td>{{ waktu_tandatangan }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>:</td>
                        <td>{{ status }}</td>
                    </tr>
                </table>
                <br>
                <table class="table table-bordered table-sm">
                    <thead class="table-dark">
                        <tr>
                            <th style="text-align: center; width: 10px; vertical-align: middle;">No</th>
                            <th style="vertical-align: middle;">Nama / Nip Verifikasi</th>
                            <th style="text-align: center; vertical-align: middle;">Jabatan</th>
                            <th style="text-align: center; vertical-align: middle;">Keterangan</th>
                            <th style="text-align: center; vertical-align: middle;">Waktu Verifikasi</th>
                            <th style="text-align: center; vertical-align: middle;">Status</th>
                        </tr>
                    </thead>
                    <tr v-for="verifikasi in data_verifikasi">
                        <td style="text-align: center; vertical-align: middle;">@{{ verifikasi.urutan }}</td>
                        <td style="vertical-align: middle;">
                            {{ verifikasi.nama_verifikasi }}<br>
                            {{ verifikasi.nip_verifikasi }}
                        </td>
                        <td style="text-align: center; vertical-align: middle;">@{{ verifikasi.jabatan_verifikasi }}</td>
                        <td style="vertical-align: middle;">@{{ verifikasi.keterangan_verifikasi }}</td>
                        <td style="text-align: center; vertical-align: middle;">@{{ verifikasi.waktu_verifikasi }}</td>
                        <td style="text-align: center; vertical-align: middle;">
                            {{ verifikasi.verifikasi_status.nama }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-flat" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
