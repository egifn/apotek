@section('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script>    
    $(document).ready(function() {
            //INISIASI DATERANGEPICKER
            $('#tanggal').daterangepicker({
             
            })
    })
</script>

<script type="text/javascript">
  //===Select Transaksi Penjualan====//
  fetchAllDataPembelian();
  function fetchAllDataPembelian() {
    $.ajax({
      type: "GET",
      url: "{{ route('transaksi_pembelian/getDataPembelian') }}",
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 0;
        response.data.forEach(pembelian => {
          let total = pembelian.total;

          //membuat format rupiah Harga//
          var reverse_total = total.toString().split('').reverse().join(''),
          ribuan_total  = reverse_total.match(/\d{1,3}/g);
          total_rupiah = ribuan_total.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          no = no + 1
          tabledata += `<tr>`;
          tabledata += `<td>` +no+ `</td>`;
          tabledata += `<td>${pembelian.kode_pembelian}</td>`;
          tabledata += `<td>${pembelian.jenis_surat_pesanan}</td>`;
          tabledata += `<td>${pembelian.tgl_pembelian}</td>`;
          tabledata += `<td>${pembelian.pembelian}</td>`;
          tabledata += `<td>${pembelian.nama_supplier}</td>`;
          tabledata += `<td align="right">Rp. ${total_rupiah}</td>`; //total
          tabledata += `<td>${pembelian.jenis_transaksi}</td>`;
          if(pembelian.status_pembelian == 0) {
              tabledata += `<td align="center"><span class="badge bg-secondary"> Pesan</span></td>`;
          }else{
              tabledata += `<td align="center">
                <button type="button" 
                  data-id="${pembelian.kode_pembelian}" 
                  data-tgl="${pembelian.tgl_pembelian}"
                  data-jsp="${pembelian.jenis_surat_pesanan}"
                  data-pembelian="${pembelian.pembelian}"
                  data-supplier="${pembelian.nama_supplier}"
                  data-jtrans="${pembelian.jenis_transaksi}"
                  data-termin="${pembelian.termin}"
                  data-tgljt="${pembelian.tgl_jatuh_tempo}"
                  id="button_view_penerimaan" class="badge bg-success">Terima
                </button>
                </td>`;
          }
          if(pembelian.status_pembayaran == 0) {
              tabledata += `<td align="center"><span class="badge bg-danger"> Hutang</span></td>`;
          }else{
              tabledata += `<td align="center"><span class="badge bg-success"> Lunas</span></td>`;
          }
          tabledata += `<td hidden>${pembelian.name}</td>`;
          tabledata += `<td hidden>${pembelian.nama_cabang}</td>`;
          if(pembelian.status_pembelian == 0) {
            tabledata += `<td align="center">
              <button type="button" 
            data-id="${pembelian.kode_pembelian}" 
            data-tgl="${pembelian.tgl_pembelian}"
            data-jsp="${pembelian.jenis_surat_pesanan}"
            data-pembelian="${pembelian.pembelian}"
            data-supplier="${pembelian.nama_supplier}"
            data-jtrans="${pembelian.jenis_transaksi}"
            data-termin="${pembelian.termin}"
            data-tgljt="${pembelian.tgl_jatuh_tempo}"
            id="button_view_pembelian" class="btn btn-success btn-sm"><i class="bi bi-eye-fill"></i></button>&nbsp;
              <button type="button" data-id="${pembelian.kode_pembelian}" data-jsp="${pembelian.jenis_surat_pesanan}" id="button_print_pembelian" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button>&nbsp;
              <button type="button"
            data-id="${pembelian.kode_pembelian}" 
            data-tgl="${pembelian.tgl_pembelian}"
            data-jsp="${pembelian.jenis_surat_pesanan}"
            data-pembelian="${pembelian.pembelian}"
            data-kd_supplier="${pembelian.kode_supplier}"
            data-supplier="${pembelian.nama_supplier}"
            data-jtrans="${pembelian.jenis_transaksi}"
            data-termin="${pembelian.termin}"
            data-tgljt="${pembelian.tgl_jatuh_tempo}"
            id="button_penerimaan" class="btn btn-warning btn-sm"><i class="bi bi-save"></i></button></td>`;
          }else{
            tabledata += `<td align="center">
              <button type="button" 
            data-id="${pembelian.kode_pembelian}" 
            data-tgl="${pembelian.tgl_pembelian}"
            data-jsp="${pembelian.jenis_surat_pesanan}"
            data-pembelian="${pembelian.pembelian}"
            data-supplier="${pembelian.nama_supplier}"
            data-jtrans="${pembelian.jenis_transaksi}"
            data-termin="${pembelian.termin}"
            data-tgljt="${pembelian.tgl_jatuh_tempo}"
            id="button_view_pembelian" class="btn btn-success btn-sm"><i class="bi bi-eye-fill"></i></button>&nbsp;
              <button type="button" data-id="${pembelian.kode_pembelian}" data-jsp="${pembelian.jenis_surat_pesanan}" id="button_print_pembelian" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button>&nbsp;
              <button type="button"
            data-id="${pembelian.kode_pembelian}" 
            data-tgl="${pembelian.tgl_pembelian}"
            data-jsp="${pembelian.jenis_surat_pesanan}"
            data-pembelian="${pembelian.pembelian}"
            data-kd_supplier="${pembelian.kode_supplier}"
            data-supplier="${pembelian.nama_supplier}"
            data-jtrans="${pembelian.jenis_transaksi}"
            data-termin="${pembelian.termin}"
            data-tgljt="${pembelian.tgl_jatuh_tempo}"
            id="button_penerimaan" class="btn btn-secondary btn-sm" disabled><i class="bi bi-save"></i></button></td>`;
          }
          
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  }
  //===End Select Transaksi Penjualan====//

  //=== Pencarian berdasarkan tanggal ====//
  $("#button_cari_tanggal").click(function(){
    let tgl_cari = $("#tanggal").val();
    $.ajax({
      type: "GET",
      url: "{{ route('transaksi_pembelian/cari.cari') }}",
      data: {
        tgl_cari: tgl_cari
      },
      dataType: "json",
      success: function(response) {
        let tabledata;
        let no = 0;
        response.data.forEach(pembelian => {
          let total = pembelian.total;

          //membuat format rupiah Harga//
          var reverse_total = total.toString().split('').reverse().join(''),
          ribuan_total  = reverse_total.match(/\d{1,3}/g);
          total_rupiah = ribuan_total.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          no = no + 1
          tabledata += `<tr>`;
          tabledata += `<td>` +no+ `</td>`;
          tabledata += `<td>${pembelian.kode_pembelian}</td>`;
          tabledata += `<td>${pembelian.jenis_surat_pesanan}</td>`;
          tabledata += `<td>${pembelian.tgl_pembelian}</td>`;
          tabledata += `<td>${pembelian.pembelian}</td>`;
          tabledata += `<td>${pembelian.nama_supplier}</td>`;
          tabledata += `<td align="right">Rp. ${total_rupiah}</td>`; //total
          tabledata += `<td>${pembelian.jenis_transaksi}</td>`;
          if(pembelian.status_pembelian == 0) {
              tabledata += `<td align="center"><span class="badge bg-secondary"> Pesan</span></td>`;
          }else{
              tabledata += `<td align="center">
                <button type="button" 
                  data-id="${pembelian.kode_pembelian}" 
                  data-tgl="${pembelian.tgl_pembelian}"
                  data-jsp="${pembelian.jenis_surat_pesanan}"
                  data-pembelian="${pembelian.pembelian}"
                  data-supplier="${pembelian.nama_supplier}"
                  data-jtrans="${pembelian.jenis_transaksi}"
                  data-termin="${pembelian.termin}"
                  data-tgljt="${pembelian.tgl_jatuh_tempo}"
                  id="button_view_penerimaan" class="badge bg-success">Terima
                </button>
                </td>`;
          }

          if(pembelian.status_pembayaran == 0) {
              tabledata += `<td align="center"><span class="badge bg-danger"> Hutang</span></td>`;
          }else{
              tabledata += `<td align="center"><span class="badge bg-success"> Lunas</span></td>`;
          }
          tabledata += `<td hidden>${pembelian.name}</td>`;
          tabledata += `<td hidden>${pembelian.nama_cabang}</td>`;
          if(pembelian.status_pembelian == 0) {
            tabledata += `<td align="center">
              <button type="button" 
            data-id="${pembelian.kode_pembelian}" 
            data-tgl="${pembelian.tgl_pembelian}"
            data-jsp="${pembelian.jenis_surat_pesanan}"
            data-pembelian="${pembelian.pembelian}"
            data-supplier="${pembelian.nama_supplier}"
            data-jtrans="${pembelian.jenis_transaksi}"
            data-termin="${pembelian.termin}"
            data-tgljt="${pembelian.tgl_jatuh_tempo}"
            id="button_view_pembelian" class="btn btn-success btn-sm"><i class="bi bi-eye-fill"></i></button>&nbsp;
              <button type="button" data-id="${pembelian.kode_pembelian}" data-jsp="${pembelian.jenis_surat_pesanan}" id="button_print_pembelian" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button>&nbsp;
              <button type="button"
            data-id="${pembelian.kode_pembelian}" 
            data-tgl="${pembelian.tgl_pembelian}"
            data-jsp="${pembelian.jenis_surat_pesanan}"
            data-pembelian="${pembelian.pembelian}"
            data-kd_supplier="${pembelian.kode_supplier}"
            data-supplier="${pembelian.nama_supplier}"
            data-jtrans="${pembelian.jenis_transaksi}"
            data-termin="${pembelian.termin}"
            data-tgljt="${pembelian.tgl_jatuh_tempo}"
            id="button_penerimaan" class="btn btn-warning btn-sm"><i class="bi bi-save"></i></button></td>`;
          }else{
            tabledata += `<td align="center">
              <button type="button" 
            data-id="${pembelian.kode_pembelian}" 
            data-tgl="${pembelian.tgl_pembelian}"
            data-jsp="${pembelian.jenis_surat_pesanan}"
            data-pembelian="${pembelian.pembelian}"
            data-supplier="${pembelian.nama_supplier}"
            data-jtrans="${pembelian.jenis_transaksi}"
            data-termin="${pembelian.termin}"
            data-tgljt="${pembelian.tgl_jatuh_tempo}"
            id="button_view_pembelian" class="btn btn-success btn-sm"><i class="bi bi-eye-fill"></i></button>&nbsp;
              <button type="button" data-id="${pembelian.kode_pembelian}" data-jsp="${pembelian.jenis_surat_pesanan}" id="button_print_pembelian" class="btn btn-primary btn-sm"><i class="bi bi-printer-fill"></i></button>&nbsp;
              <button type="button"
            data-id="${pembelian.kode_pembelian}" 
            data-tgl="${pembelian.tgl_pembelian}"
            data-jsp="${pembelian.jenis_surat_pesanan}"
            data-pembelian="${pembelian.pembelian}"
            data-kd_supplier="${pembelian.kode_supplier}"
            data-supplier="${pembelian.nama_supplier}"
            data-jtrans="${pembelian.jenis_transaksi}"
            data-termin="${pembelian.termin}"
            data-tgljt="${pembelian.tgl_jatuh_tempo}"
            id="button_penerimaan" class="btn btn-secondary btn-sm" disabled><i class="bi bi-save"></i></button></td>`;
          }
          
          tabledata += `</tr>`;
        });
        $("#tabledata").html(tabledata);
      }
    });
  });
  //=== End Pencarian berdasarkan tanggal ====//

  //=== view detail Penerimaan ===============//
  $(document).on("click", "#button_view_penerimaan", function(e) {
    e.preventDefault();
    let kode_pembelian = $(this).data('id');
    let tgl_pembelian = $(this).data('tgl');
    let jenis_surat_pesanan = $(this).data('jsp');
    let pembelian = $(this).data('pembelian');
    let Supplier = $(this).data('supplier');
    let jenis_transaksi = $(this).data('jtrans');
    let termin = $(this).data('termin');
    let tgl_jatuh_tempo = $(this).data('tgljt');

    $.ajax({
      type: "GET",
      url: "{{ route('transaksi_pembelian/getViewPenerimanPembelian') }}",
      data: {
        kode_pembelian: kode_pembelian
      },
      dataType: "json",
      success: function(response) {
        $(".penerimaan_no_sp").text(kode_pembelian);
        $(".penerimaan_jenis").text(jenis_transaksi);
        $(".penerimaan_j_sp").text(jenis_surat_pesanan);
        $(".penerimaan_termin").text(termin);
        $(".penerimaan_pembelian").text(pembelian);
        $(".penerimaan_tgl_jt").text(tgl_jatuh_tempo);
        $(".penerimaan_supplier").text(Supplier);
        let penerimaan_tbl_detail;
        let no = 0;
        let subtotal = 0;
        response.data.forEach(penerimaan => {
          let harga = penerimaan.harga_beli;
          let diskon_rp = penerimaan.diskon_rp;
          let ppn_rp = penerimaan.ppn_rp;
          let total = penerimaan.subtotal;

          //membuat format rupiah Harga//
          var reverse_harga = harga.toString().split('').reverse().join(''),
          ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
          harga_rupiah = ribuan_harga.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          //membuat format rupiah Diskon_rp//
          var reverse_diskon_rp = diskon_rp.toString().split('').reverse().join(''),
          ribuan_diskon_rp  = reverse_diskon_rp.match(/\d{1,3}/g);
          diskon_rp_rupiah = ribuan_diskon_rp.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          //membuat format rupiah ppn_rp//
          var reverse_ppn_rp = ppn_rp.toString().split('').reverse().join(''),
          ribuan_ppn_rp  = reverse_ppn_rp.match(/\d{1,3}/g);
          ppn_rp_rupiah = ribuan_ppn_rp.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          //membuat format rupiah Total//
          var reverse_total = total.toString().split('').reverse().join(''),
          ribuan_total  = reverse_total.match(/\d{1,3}/g);
          total_rupiah = ribuan_total.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          no = no + 1
          penerimaan_tbl_detail += `<tr>`;
          penerimaan_tbl_detail += `<td>` +no+ `</td>`;
          penerimaan_tbl_detail += `<td>${penerimaan.kode_produk}</td>`;
          penerimaan_tbl_detail += `<td>${penerimaan.nama_produk}</td>`;
          penerimaan_tbl_detail += `<td align="right">${harga_rupiah}</td>`;
          penerimaan_tbl_detail += `<td align="right">${penerimaan.jml_beli}</td>`;
          penerimaan_tbl_detail += `<td align="right">${penerimaan.jml_terima/1}</td>`;
          penerimaan_tbl_detail += `<td>${penerimaan.nama_unit}</td>`; //total
          penerimaan_tbl_detail += `<td align="right">${penerimaan.diskon_persen}%</td>`;
          penerimaan_tbl_detail += `<td align="right">${diskon_rp_rupiah}</td>`;
          penerimaan_tbl_detail += `<td align="right">${penerimaan.ppn_persen}%</td>`;
          penerimaan_tbl_detail += `<td align="right">${ppn_rp_rupiah}</td>`;
          penerimaan_tbl_detail += `<td align="right">${total_rupiah}</td>`;
          penerimaan_tbl_detail += `</tr>`;
          let temp_total = `${penerimaan.subtotal}`;
          subtotal = subtotal + parseInt(temp_total);
        });

        //membuat format rupiah subtotal//
        var reverse_subtotal = subtotal.toString().split('').reverse().join(''),
        ribuan_subtotal  = reverse_subtotal.match(/\d{1,3}/g);
        subtotal_rupiah = ribuan_subtotal.join(',').split('').reverse().join('');
        //End membuat format rupiah//

        $("#penerimaan_tbl_detail").html(penerimaan_tbl_detail);
        $(".penerimaan_f_subtotal").text(subtotal_rupiah);
      }
    });
    $('#modalViewPenerimaan').modal('show');
  });
  //== end view detail Penerimaan ===========//

  //===View Data Pembelian===============//
  $(document).on("click", "#button_view_pembelian", function(e) {
    e.preventDefault();
    let kode_pembelian = $(this).data('id');
    let tgl_pembelian = $(this).data('tgl');
    let jenis_surat_pesanan = $(this).data('jsp');
    let pembelian = $(this).data('pembelian');
    let Supplier = $(this).data('supplier');
    let jenis_transaksi = $(this).data('jtrans');
    let termin = $(this).data('termin');
    let tgl_jatuh_tempo = $(this).data('tgljt');

    $.ajax({
      type: "GET",
      url: "{{ route('transaksi_pembelian/getViewPembelian') }}",
      data: {
        kode_pembelian: kode_pembelian
      },
      dataType: "json",
      success: function(response) {
        $(".no_sp").text(kode_pembelian);
        $(".jenis").text(jenis_transaksi);
        $(".j_sp").text(jenis_surat_pesanan);
        $(".termin").text(termin);
        $(".pembelian").text(pembelian);
        $(".tgl_jt").text(tgl_jatuh_tempo);
        $(".supplier").text(Supplier);
        let tbl_detail;
        let no = 0;
        let subtotal = 0;
        response.data.forEach(detail => {
          let harga = detail.harga;
          let diskon_rp = detail.diskon_item_rp;
          let ppn_rp = detail.ppn_item_rp;
          let total = detail.total;

          //membuat format rupiah Harga//
          var reverse_harga = harga.toString().split('').reverse().join(''),
          ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
          harga_rupiah = ribuan_harga.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          //membuat format rupiah Diskon_rp//
          var reverse_diskon_rp = diskon_rp.toString().split('').reverse().join(''),
          ribuan_diskon_rp  = reverse_diskon_rp.match(/\d{1,3}/g);
          diskon_rp_rupiah = ribuan_diskon_rp.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          //membuat format rupiah ppn_rp//
          var reverse_ppn_rp = ppn_rp.toString().split('').reverse().join(''),
          ribuan_ppn_rp  = reverse_ppn_rp.match(/\d{1,3}/g);
          ppn_rp_rupiah = ribuan_ppn_rp.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          //membuat format rupiah Total//
          var reverse_total = total.toString().split('').reverse().join(''),
          ribuan_total  = reverse_total.match(/\d{1,3}/g);
          total_rupiah = ribuan_total.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          no = no + 1
          tbl_detail += `<tr>`;
          tbl_detail += `<td>` +no+ `</td>`;
          tbl_detail += `<td>${detail.kode_produk}</td>`;
          tbl_detail += `<td>${detail.nama_produk}</td>`;
          tbl_detail += `<td align="right">${harga_rupiah}</td>`;
          tbl_detail += `<td align="right">${detail.qty_beli}</td>`;
          tbl_detail += `<td>${detail.nama_unit}</td>`; //total
          tbl_detail += `<td align="right">${detail.diskon_item}%</td>`;
          tbl_detail += `<td align="right">${diskon_rp_rupiah}</td>`;
          tbl_detail += `<td align="right">${detail.ppn_item}%</td>`;
          tbl_detail += `<td align="right">${ppn_rp_rupiah}</td>`;
          tbl_detail += `<td align="right">${total_rupiah}</td>`;
          tbl_detail += `</tr>`;
          let temp_total = `${detail.total}`;
          subtotal = subtotal + parseInt(temp_total);
        });

        //membuat format rupiah subtotal//
        var reverse_subtotal = subtotal.toString().split('').reverse().join(''),
        ribuan_subtotal  = reverse_subtotal.match(/\d{1,3}/g);
        subtotal_rupiah = ribuan_subtotal.join(',').split('').reverse().join('');
        //End membuat format rupiah//

        $("#tbl_detail").html(tbl_detail);
        $(".f_subtotal").text(subtotal_rupiah);
      }
    });
    $('#modalViewPembelian').modal('show');
  });
   //===End View Data Pembelian===============//

  //===View Data Penerimaan===============//
  $(document).on("click", "#button_penerimaan", function(e) {
    e.preventDefault();
    let kode_pembelian = $(this).data('id');
    let tgl_pembelian = $(this).data('tgl');
    let jenis_surat_pesanan = $(this).data('jsp');
    let pembelian = $(this).data('pembelian');
    let kd_supplier = $(this).data('kd_supplier')
    let Supplier = $(this).data('supplier');
    let jenis_transaksi = $(this).data('jtrans');
    let termin = $(this).data('termin');
    let tgl_jatuh_tempo = $(this).data('tgljt');

    $.ajax({
      type: "GET",
      url: "{{ route('transaksi_pembelian/getViewPembelian') }}",
      data: {
        kode_pembelian: kode_pembelian
      },
      dataType: "json",
      success: function(response) {
        $(".no_sp_terima").text(kode_pembelian);
        $(".jenis_terima").text(jenis_transaksi);
        $(".j_sp_terima").text(jenis_surat_pesanan);
        $(".termin_terima").text(termin);
        $(".pembelian_terima").text(pembelian);
        $(".tgl_jt_terima").text(tgl_jatuh_tempo);
        $(".kd_supplier").text(kd_supplier);
        $(".supplier_terima").text(Supplier);
        let tbl_detail_terima;
        let no = 0;
        let subtotal = 0;
        response.data.forEach(detail => {
          let harga = detail.harga;
          //membuat format rupiah Harga//
          var reverse_harga = harga.toString().split('').reverse().join(''),
          ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
          harga_rupiah = ribuan_harga.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          let harga_jual_lama = detail.harga_jual_lama;
          //membuat format rupiah Harga//
          var reverse_harga_jual_lama = harga_jual_lama.toString().split('').reverse().join(''),
          ribuan_harga_jual_lama  = reverse_harga_jual_lama.match(/\d{1,3}/g);
          harga_jual_lama_rupiah = ribuan_harga_jual_lama.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          let total = detail.total;
          //membuat format rupiah total//
          var reverse_total = total.toString().split('').reverse().join(''),
          ribuan_total  = reverse_total.match(/\d{1,3}/g);
          total_rupiah = ribuan_total.join(',').split('').reverse().join('');
          //End membuat format total//

          let margin_rp = detail.margin_rp;
          //membuat format rupiah total//
          var reverse_margin_rp = margin_rp.toString().split('').reverse().join(''),
          ribuan_margin_rp  = reverse_margin_rp.match(/\d{1,3}/g);
          margin_rp_rupiah = ribuan_margin_rp.join(',').split('').reverse().join('');
          //End membuat format total//

          no = no + 1
          //temp_kode_produk = detail.kode_produk

          tbl_detail_terima += '<tr>';
          tbl_detail_terima += '<td>' +no+ '</td>';
          tbl_detail_terima += '<td class="kode_produk">' +detail.kode_produk+ '</td>';
          tbl_detail_terima += '<td>' +detail.nama_produk+ '</td>';

          tbl_detail_terima += '<td class="harga_beli_lama" align="right">' +harga_rupiah+ '</td>';
          tbl_detail_terima += '<td class="margin_persen_lama" align="right" hidden>' +detail.margin_persen_lama+ '</td>';
          tbl_detail_terima += '<td class="margin_rp_lama" align="right" hidden>' +detail.margin_rp_lama+ '</td>';
          tbl_detail_terima += '<td class="harga_jual_lama" align="right">' +harga_jual_lama_rupiah+ '</td>';

          tbl_detail_terima += '<td align="right"><input type="text" class="form-control" style="width:100px;height:27px;text-align:right;" name="batch[]' + no +'" id="batch[]' + no +'" onkeyup="jumlah(' + no + ');" value=""></td>';
          tbl_detail_terima += '<td class="no_batch" id="no_batch' + no + '" align="right" contenteditable="true" hidden>0</td>';
          
          tbl_detail_terima += '<td align="right"><input type="date" class="form-control" style="width:130px;height:27px;text-align:right;" name="exp[]' + no +'" id="exp[]' + no +'" onchange="jumlah(' + no + ');" value=""></td>';
          tbl_detail_terima += '<td class="exp" id="exp' + no + '" align="right" contenteditable="true" hidden></td>';

          tbl_detail_terima += '<td align="right"><input type="text" class="form-control" style="width:100px;height:27px;text-align:right;" name="harga[]' + no +'" id="harga[]' + no +'" onkeyup="jumlah(' + no + ');" value="' +harga_rupiah+ '"></td>';
          tbl_detail_terima += '<td class="harga" id="harga' + no + '" align="right" contenteditable="true" hidden>' +detail.harga+ '</td>'; //'<td class="harga" align="right">' +detail.harga+ '</td>';
          
          tbl_detail_terima += '<td align="right"><input type="number" class="form-control" style="width:70px;height:27px;text-align:right;" name="harga_margin_persen[]' + no +'" id="harga_margin_persen[]' + no +'" onclick="jumlah(' + no + ');" onkeyup="jumlah(' + no + ');" value="' +detail.margin_persen+ '"></td>';
          tbl_detail_terima += '<td class="harga_margin_persen" id="harga_margin_persen' + no + '" align="right" contenteditable="true" hidden>' +detail.margin_persen+ '</td>'; //'<td class="harga" align="right">' +detail.harga+ '</td>';

          tbl_detail_terima += '<td align="right"><input type="text" class="form-control" style="width:100px;height:27px;text-align:right;" name="harga_margin[]' + no +'" id="harga_margin[]' + no +'" onkeyup="jumlah(' + no + ');" value="' +margin_rp_rupiah+ '"></td>';
          tbl_detail_terima += '<td class="harga_margin" id="harga_margin' + no + '" align="right" contenteditable="true" hidden>' +detail.margin_rp+ '</td>'; //'<td class="harga" align="right">' +detail.harga+ '</td>';

          tbl_detail_terima += '<td class="qty" id="qty' + no + '" align="right">' +detail.qty_beli+ '</td>';
          tbl_detail_terima += '<td class="qty_kecil" id="qty_kecil' + no + '" align="right" hidden>' +detail.qty_beli+ '</td>';

          tbl_detail_terima += '<td align="right"><input type="number" class="form-control" style="width:70px;height:27px;text-align:right;" name="jml[]' + no +'" id="jml[]' + no +'" onclick="jumlah(' + no + ');" onkeyup="jumlah(' + no + ');" value="' +detail.qty_beli+ '"></td>';
          tbl_detail_terima += '<td class="qty_terima" id="qty_terima' + no + '" align="right" contenteditable="true" hidden>' +detail.qty_kecil+ '</td>';
          
          tbl_detail_terima += '<td class="id_produk_unit" hidden>' +detail.id+ '</td>';
          tbl_detail_terima += '<td>' +detail.nama_unit+ '</td>';
          tbl_detail_terima += '<td class="qty_unit_kecil" id="qty_unit_kecil' + no + '" align="right" contenteditable="true" hidden>' +detail.qty_unit_kecil+ '</td>';

          tbl_detail_terima += '<td align="right" class="tambah_diskon"><input type="number" class="form-control" style="width:70px;height:27px;text-align:right;" name="diskon_persen[]' + no +'" id="diskon_persen[]' + no +'" onclick="jumlah(' + no + ');" onkeyup="jumlah(' + no + ');" value="' +detail.diskon_item+ '"></td>';
          tbl_detail_terima += '<td class="tambah_diskon_temp" id="tambah_diskon_temp' + no + '" align="right" contenteditable="true" hidden>' +detail.diskon_item+ '</td>';

          tbl_detail_terima += '<td align="right"><input type="text" class="form-control" style="width:70px;height:27px;text-align:right;" name="diskon_rp[]' + no +'" id="diskon_rp[]' + no +'" onclick="jumlah(' + no + ');" onkeyup="jumlah(' + no + ');" value="' +detail.diskon_item_rp+ '"></td>';
          tbl_detail_terima += '<td class="diskon_rp" id="diskon_rp' + no + '" align="right" contenteditable="true" hidden>' +detail.diskon_item_rp+ '</td>';

          tbl_detail_terima += '<td align="right"><input type="number" class="form-control" style="width:70px;height:27px;text-align:right;" name="ppn_persen[]' + no +'" id="ppn_persen[]' + no +'" onclick="jumlah(' + no + ');" onkeyup="jumlah(' + no + ');" value="' +detail.ppn_item+ '"></td>';
          tbl_detail_terima += '<td class="ppn_persen" id="ppn_persen' + no + '" align="right" contenteditable="true" hidden>' +detail.ppn_item+ '</td>';
          
          tbl_detail_terima += '<td align="right"><input type="text" class="form-control" style="width:70px;height:27px;text-align:right;" name="ppn_rp[]' + no +'" id="ppn_rp[]' + no +'" onclick="jumlah(' + no + ');" onkeyup="jumlah(' + no + ');" value="' +detail.ppn_item_rp+ '"></td>';
          tbl_detail_terima += '<td class="ppn_rp" id="ppn_rp' + no + '" align="right" contenteditable="true" hidden>' +detail.ppn_item_rp+ '</td>';
          
          tbl_detail_terima += '<td class="total" id="total' + no + '" align="right" contenteditable="true">' +total_rupiah+ '</td>';
          tbl_detail_terima += '</tr>';
          let temp_total = `${detail.total}`;
          subtotal = subtotal + parseInt(temp_total);
        });
        $("#tbl_detail_terima").html(tbl_detail_terima);
         
        //membuat format rupiah total//
        var reverse_subtotal = subtotal.toString().split('').reverse().join(''),
          ribuan_subtotal  = reverse_subtotal.match(/\d{1,3}/g);
          subtotal_rupiah = ribuan_subtotal.join(',').split('').reverse().join('');
        //End membuat format total//
        $(".f_subtotal_terima").text(subtotal_rupiah);
        $(".temp_f_subtotal_terima").text(subtotal_rupiah);
        
      }
    });
    $('#modalPenerimaanPembelian').modal('show');
  });
  //===End View Data Pembelian===============//

  let total_sum = 0;

  let sum_subtotal_1 = 0;
  let sum_subtotal_2 = 0;
  let sum_subtotal_3 = 0;
  let sum_subtotal_4 = 0;
  let sum_subtotal_5 = 0;
  let sum_subtotal_6 = 0;
  let sum_subtotal_7 = 0;
  let sum_subtotal_8 = 0;
  let sum_subtotal_9 = 0;
  let sum_subtotal_10 = 0;
  let sum_subtotal_11 = 0;
  let sum_subtotal_12 = 0;
  let sum_subtotal_13 = 0;
  let sum_subtotal_14 = 0;
  let sum_subtotal_15 = 0;
  let sum_subtotal_16 = 0;
  let sum_subtotal_17 = 0;
  let sum_subtotal_18 = 0;
  let sum_subtotal_19 = 0;
  let sum_subtotal_20 = 0;
  let sum_subtotal_21 = 0;
  let sum_subtotal_22 = 0;
  let sum_subtotal_23 = 0;
  let sum_subtotal_24 = 0;
  let sum_subtotal_25 = 0;
  let sum_subtotal_26 = 0;
  let sum_subtotal_27 = 0;
  let sum_subtotal_28 = 0;
  let sum_subtotal_29 = 0;
  let sum_subtotal_30 = 0;
  let sum_subtotal_31 = 0;
  let sum_subtotal_32 = 0;
  let sum_subtotal_33 = 0;
  let sum_subtotal_34 = 0;
  let sum_subtotal_35 = 0;
  let sum_subtotal_36 = 0;
  let sum_subtotal_37 = 0;
  let sum_subtotal_38 = 0;
  let sum_subtotal_39 = 0;
  let sum_subtotal_40 = 0;
  function jumlah(no) {
    $("input[name='harga[]" +no+ "']").maskMoney({thousands:',', decimal:'.', precision:0});
    $("input[name='harga_margin[]" +no+ "']").maskMoney({thousands:',', decimal:'.', precision:0});

    var no_batch = $("input[name='batch[]" +no+ "']").val();
    $('#no_batch' + no +'').text(no_batch);

    var exp = $("input[name='exp[]" +no+ "']").val();
    $('#exp' + no +'').text(exp);

    var harga = $("input[name='harga[]" +no+ "']").val();
    $('#harga' + no +'').text(harga);

    var harga_margin_persen = $("input[name='harga_margin_persen[]" +no+ "']").val();
    $('#harga_margin_persen' + no +'').text(harga_margin_persen);

    var harga_margin = $("input[name='harga_margin[]" +no+ "']").val();
    $('#harga_margin' + no +'').text(harga_margin);

    var qty_terima = $("input[name='jml[]" +no+ "']").val() * $('#qty_unit_kecil' + no +'').text();
    $('#qty_terima' + no +'').text(qty_terima);

    var diskon_persen = $("input[name='diskon_persen[]" +no+ "']").val();
    $('#tambah_diskon_temp' + no +'').text(diskon_persen);

    var diskon_rp =  $("input[name='diskon_rp[]" +no+ "']").val();
    $('#diskon_rp' + no +'').text(diskon_rp);

    var ppn_persen = $("input[name='ppn_persen[]" +no+ "']").val();
    $('#ppn_persen' + no +'').text(ppn_persen);

    var ppn_rp = $("input[name='ppn_rp[]" +no+ "']").val();
    $('#ppn_rp' + no +'').text(ppn_rp);

    // untuk mendapatkan margin_rp //
    var tambah_margin_persen = ($("input[name='harga_margin_persen[]" +no+ "']").val());
        //menghilangka format rupiah//
        var ambil_harga = $("input[name='harga[]" +no+ "']").val();
        var temp_ambil_harga = ambil_harga.replace(/[.](?=.*?\.)/g, '');
        var hasil_temp_ambil_harga = (temp_ambil_harga.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah//
    var hasil_tambah_margin_persen =  Math.round((tambah_margin_persen / 100)*hasil_temp_ambil_harga);
    $('#harga_margin' + no +'').text(hasil_tambah_margin_persen);
     //membuat format rupiah//
     var reverse_hasil_tambah_margin_persen = hasil_tambah_margin_persen.toString().split('').reverse().join(''),
        ribuan_reverse_hasil_tambah_margin_persen  = reverse_hasil_tambah_margin_persen.match(/\d{1,3}/g);
        hasil_ribuan_reverse_hasil_tambah_margin_persen = ribuan_reverse_hasil_tambah_margin_persen.join(',').split('').reverse().join('');
      //End membuat format rupiah//
    $("input[name='harga_margin[]" +no+ "']").val(hasil_ribuan_reverse_hasil_tambah_margin_persen);

    // untuk mendapatkan diskon_rp //
    var tambah_diskon = ($("input[name='diskon_persen[]" +no+ "']").val());
    var jml_terima = $("input[name='jml[]" +no+ "']").val();
    var temp_tambah_diskon_rp =  Math.round((tambah_diskon / 100)*(hasil_temp_ambil_harga*jml_terima));
    $('#diskon_rp' + no +'').text(temp_tambah_diskon_rp)
    //membuat format rupiah//
    var reverse_temp_tambah_diskon_rp = temp_tambah_diskon_rp.toString().split('').reverse().join(''),
      ribuan_reverse_temp_tambah_diskon_rp  = reverse_temp_tambah_diskon_rp.match(/\d{1,3}/g);
      hasil_ribuan_reverse_temp_tambah_diskon_rp = ribuan_reverse_temp_tambah_diskon_rp.join(',').split('').reverse().join('');
    //End membuat format rupiah//
    $("input[name='diskon_rp[]" +no+ "']").val(hasil_ribuan_reverse_temp_tambah_diskon_rp);
    // untuk end mendapatkan diskon_rp //

    // untuk mendapatkan ppn_rp //
    var tambah_ppn = ($("input[name='ppn_persen[]" +no+ "']").val());
    var temp_tambah_ppn_rp =  Math.round((tambah_ppn / 100)*(hasil_temp_ambil_harga*jml_terima-temp_tambah_diskon_rp));
    $('#ppn_rp' + no +'').text(temp_tambah_ppn_rp)
    //membuat format rupiah//
    var reverse_temp_tambah_ppn_rp = temp_tambah_ppn_rp.toString().split('').reverse().join(''),
      ribuan_reverse_temp_tambah_ppn_rp  = reverse_temp_tambah_ppn_rp.match(/\d{1,3}/g);
      hasil_ribuan_reverse_temp_tambah_ppn_rp = ribuan_reverse_temp_tambah_ppn_rp.join(',').split('').reverse().join('');
    //End membuat format rupiah//
    $("input[name='ppn_rp[]" +no+ "']").val(hasil_ribuan_reverse_temp_tambah_ppn_rp);
    // untuk End mendapatkan ppn_rp //

    var temp_total = hasil_temp_ambil_harga * jml_terima - temp_tambah_diskon_rp + temp_tambah_ppn_rp;
    //membuat format rupiah//
    var reverse = temp_total.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_subtotal = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//
    $('#total' + no +'').text(hasil_subtotal);

    if(no == 1){
      sum_subtotal_1 = temp_total;
    }else if(no == 2){
      sum_subtotal_2 = temp_total;
    }else if(no == 3){
      sum_subtotal_3 = temp_total;
    }else if(no == 4){
      sum_subtotal_4 = temp_total;
    }else if(no == 5){
      sum_subtotal_5 = temp_total;
    }else if(no == 6){
      sum_subtotal_6 = temp_total;
    }else if(no == 7){
      sum_subtotal_7 = temp_total;
    }else if(no == 8){
      sum_subtotal_8 = temp_total;
    }else if(no == 9){
      sum_subtotal_9 = temp_total;
    }else if(no == 10){
      sum_subtotal_10 = temp_total;
    }else if(no == 11){
      sum_subtotal_11 = temp_total;
    }else if(no == 12){
      sum_subtotal_12 = temp_total;
    }else if(no == 13){
      sum_subtotal_13 = temp_total;
    }else if(no == 14){
      sum_subtotal_14 = temp_total;
    }else if(no == 15){
      sum_subtotal_15 = temp_total;
    }else if(no == 16){
      sum_subtotal_16 = temp_total;
    }else if(no == 17){
      sum_subtotal_17 = temp_total;
    }else if(no == 18){
      sum_subtotal_18 = temp_total;
    }else if(no == 19){
      sum_subtotal_19 = temp_total;
    }else if(no == 20){
      sum_subtotal_20 = temp_total;

    }else if(no == 21){
      sum_subtotal_21 = temp_total;
    }else if(no == 22){
      sum_subtotal_22 = temp_total;
    }else if(no == 23){
      sum_subtotal_23 = temp_total;
    }else if(no == 24){
      sum_subtotal_24 = temp_total;
    }else if(no == 25){
      sum_subtotal_25 = temp_total;
    }else if(no == 26){
      sum_subtotal_26 = temp_total;
    }else if(no == 27){
      sum_subtotal_27 = temp_total;
    }else if(no == 28){
      sum_subtotal_28 = temp_total;
    }else if(no == 29){
      sum_subtotal_29 = temp_total;
    }else if(no == 30){
      sum_subtotal_30 = temp_total;
    }else if(no == 31){
      sum_subtotal_31 = temp_total;
    }else if(no == 32){
      sum_subtotal_32 = temp_total;
    }else if(no == 33){
      sum_subtotal_33 = temp_total;
    }else if(no == 34){
      sum_subtotal_34 = temp_total;
    }else if(no == 35){
      sum_subtotal_35 = temp_total;
    }else if(no == 36){
      sum_subtotal_36 = temp_total;
    }else if(no == 37){
      sum_subtotal_37 = temp_total;
    }else if(no == 38){
      sum_subtotal_38 = temp_total;
    }else if(no == 39){
      sum_subtotal_39 = temp_total;
    }else if(no == 40){
      sum_subtotal_40 = temp_total;
    }

    total_sum = (sum_subtotal_1+sum_subtotal_2+sum_subtotal_3+sum_subtotal_4+sum_subtotal_5+sum_subtotal_6+sum_subtotal_7+sum_subtotal_8+sum_subtotal_9+sum_subtotal_10+
                sum_subtotal_11+sum_subtotal_12+sum_subtotal_13+sum_subtotal_14+sum_subtotal_15+sum_subtotal_16+sum_subtotal_17+sum_subtotal_18+sum_subtotal_19+sum_subtotal_20+
                sum_subtotal_21+sum_subtotal_22+sum_subtotal_23+sum_subtotal_24+sum_subtotal_25+sum_subtotal_26+sum_subtotal_27+sum_subtotal_28+sum_subtotal_29+sum_subtotal_30+
                sum_subtotal_31+sum_subtotal_32+sum_subtotal_33+sum_subtotal_34+sum_subtotal_35+sum_subtotal_36+sum_subtotal_37+sum_subtotal_38+sum_subtotal_39+sum_subtotal_40);

    var reverse = total_sum.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_total_sum = ribuan.join(',').split('').reverse().join('');

    // $('.f_subtotal_terima').text(hasil_total_sum);
    // $('.temp_f_subtotal_terima').text(hasil_total_sum);

    ////PERULANGAN UNTUK MENJUMLAH SUM TOTAL////
    var table = document.getElementById("datatabel_terima"), sumHsl = 0;
    for(var t = 1; t < table.rows.length; t++)
    {
        var sub_total = table.rows[t].cells[32].innerHTML;
        // //menghilangka format rupiah harga//
        var sub_total_non_format = sub_total.replace(/[.](?=.*?\.)/g, '');
        var sub_total_hasil = parseInt(sub_total_non_format.replace(/[^0-9.]/g,''));
        // //End menghilangka format rupiah harga//

        sumHsl = sumHsl + parseInt(sub_total_hasil);
        //membuat format rupiah total//
        var format_sumHsl = sumHsl.toString().split('').reverse().join(''),
            ribuan  = format_sumHsl.match(/\d{1,3}/g);
            hasil_format_sumHsl = ribuan.join(',').split('').reverse().join('');
        //End membuat format rupiah total//
        //$('#f_subtotal').val(hasil_format_sumHsl);

        $('.f_subtotal_terima').text(hasil_format_sumHsl);
        $('.temp_f_subtotal_terima').text(hasil_format_sumHsl);
    }
  }

  $("input[name='f_diskon_terima_rupiah']").maskMoney({thousands:',', decimal:'.', precision:0});
  
  $("input[name='f_diskon_terima_rupiah']").keyup(function(e){
    var f_subtotal = $(".f_subtotal_terima").text();
    //menghilangka format rupiah//
        var temp_f_subtotal_terima = f_subtotal.replace(/[.](?=.*?\.)/g, '');
        var hasil_temp_f_subtotal_terima = parseInt(temp_f_subtotal_terima.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//

    var f_diskon_rupiah = ($(this).val());
    //menghilangka format rupiah//
      var temp_f_diskon_rupiah = f_diskon_rupiah.replace(/[.](?=.*?\.)/g, '');
        var hasil_temp_f_diskon_rupiah = parseInt(temp_f_diskon_rupiah.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//

    var f_subtotal_hasil = hasil_temp_f_subtotal_terima - hasil_temp_f_diskon_rupiah;
    //membuat format rupiah//
    var reverse = f_subtotal_hasil.toString().split('').reverse().join(''),
            ribuan  = reverse.match(/\d{1,3}/g);
            hasil_f_subtotal_hasil = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//

    // cari diskon_persen//
    var hasil_persen = (hasil_temp_f_diskon_rupiah / hasil_temp_f_subtotal_terima) * 100;
    $("#f_diskon_terima_persen").val(hasil_persen);
    // Endcari diskon_persen//

    $(".temp_f_subtotal_terima").text(hasil_f_subtotal_hasil);
    //$(".f_subtotal_terima").text(hasil_f_subtotal_hasil);
  })
  
  $("input[name='f_diskon_terima_persen']").keyup(function(e){
    var f_subtotal = $(".f_subtotal_terima").text();
    //menghilangka format rupiah//
        var temp_f_subtotal_terima = f_subtotal.replace(/[.](?=.*?\.)/g, '');
        var hasil_temp_f_subtotal_terima = parseInt(temp_f_subtotal_terima.replace(/[^0-9.]/g,''));
    //End menghilangka format rupiah//

    var f_diskon_persen = ($(this).val());

    var hasil_rupiah = Math.round((f_diskon_persen / 100) * hasil_temp_f_subtotal_terima);
    //membuat format rupiah//
    var reverse = hasil_rupiah.toString().split('').reverse().join(''),
      ribuan  = reverse.match(/\d{1,3}/g);
      hasil_hasil_rupiah = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//
    $("#f_diskon_terima_rupiah").val(hasil_hasil_rupiah);

    var f_subtotal_hasil = hasil_temp_f_subtotal_terima - hasil_rupiah;
    //membuat format rupiah//
    var reverse = f_subtotal_hasil.toString().split('').reverse().join(''),
            ribuan  = reverse.match(/\d{1,3}/g);
            hasil_f_subtotal_hasil = ribuan.join(',').split('').reverse().join('');
    //End membuat format rupiah//

    $(".temp_f_subtotal_terima").text(hasil_f_subtotal_hasil);
    //$(".f_subtotal_terima").text(hasil_f_subtotal_hasil);
  })

  //===PDF Data Pembelian===============//
  $(document).on("click", "#button_print_pembelian", function(e) {
    e.preventDefault();
    let kode_pembelian = $(this).data('id');
    let jenis_sp = $(this).data('jsp');
    //transaksi_pembelian/pdf.pdf
    $.ajax({
      type: "GET",
      url: "{{ route('transaksi_pembelian/pdf.pdf') }}",
      data: {
        kode_pembelian: kode_pembelian,
        jenis_sp: jenis_sp
      },
      dataType: "json",
      success: function(response) {
        
      }
    });
    let mywindow = window.open("{{ route('transaksi_pembelian/pdf.pdf') }}?kode_pembelian=" + kode_pembelian + "", '_blank');
  });
  //===End PDF Data Pembelian===============//

  //====Simpan Penerimaan===================//
  $('#button_terima_barang').click(function(e) {
    if ($("#no_faktur_terima").val() == ""){
        alert("No Faktur harus diisi. No Faktur tidak boleh kosong");
        $("#no_faktur_terima").focus();
        return (false);
      }

    e.preventDefault();
    let kode_pembelian = $(".no_sp_terima").text();
    let no_faktur = $("#no_faktur_terima").val();
    let diskon_all_rupiah = $("#f_diskon_terima_rupiah").val();
    let diskon_all_persen = $("#f_diskon_terima_persen").val();
    let subtotal = $(".temp_f_subtotal_terima").text();
    let kd_supplier = $(".kd_supplier").text();

    // untuk Detail //
    let kode_produk = []
    let harga_beli_lama = []
    let margin_persen_lama = []
    let margin_rp_lama = []
    let harga_jual_lama = []
    let no_batch = []
    let tgl_kadaluarsa = []
    let harga_beli = []
    let harga_margin = []
    let margin_persen = []
    let margin_rupiah = []
    let jml_pesan = []
    let jml_terima = []
    let id_produk_unit = []
    let diskon_persen = []
    let diskon_rupiah = []
    let ppn_persen = []
    let ppn_rupiah = []
    let total = []

    $('.kode_produk').each(function() {
      kode_produk.push($(this).text())
    })

    $('.harga_beli_lama').each(function() {
      harga_beli_lama.push($(this).text())
    })
    $('.margin_persen_lama').each(function() {
      margin_persen_lama.push($(this).text())
    })
    $('.margin_rp_lama').each(function() {
      margin_rp_lama.push($(this).text())
    })
    $('.harga_jual_lama').each(function() {
      harga_jual_lama.push($(this).text())
    })

    $('.no_batch').each(function() {
      no_batch.push($(this).text())
    })
    $('.exp').each(function() {
      tgl_kadaluarsa.push($(this).text())
    })
    $('.harga').each(function() {
      harga_beli.push($(this).text())
    })
    $('.harga_margin').each(function() {
      harga_margin.push($(this).text())
    })
    $('.harga_margin_persen').each(function() {
      margin_persen.push($(this).text())
    })
    $('.harga_margin').each(function() {
      margin_rupiah.push($(this).text())
    })
    $('.qty_kecil').each(function() {
      jml_pesan.push($(this).text())
    })
    $('.qty_terima').each(function() {
      jml_terima.push($(this).text())
    })
    $('.id_produk_unit').each(function() {
      id_produk_unit.push($(this).text())
    })
    $('.tambah_diskon_temp').each(function() {
      diskon_persen.push($(this).text())
    })
    $('.diskon_rp').each(function() {
      diskon_rupiah.push($(this).text())
    })
    $('.ppn_persen').each(function() {
      ppn_persen.push($(this).text())
    })
    $('.ppn_rp').each(function() {
      ppn_rupiah.push($(this).text())
    })
    $('.total').each(function() {
      total.push($(this).text())
    })
    // Enduntuk Detail //
    
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type: "POST",
      url: "{{ route('pembelian/terima') }}",
      data: {
        kode_pembelian: kode_pembelian,
        no_faktur: no_faktur,
        diskon_all_rupiah: diskon_all_rupiah,
        diskon_all_persen: diskon_all_persen,
        subtotal: subtotal,
        kd_supplier: kd_supplier,

        // untuk Detail //
        kode_produk: kode_produk,
        harga_beli_lama: harga_beli_lama,
        margin_persen_lama: margin_persen_lama,
        margin_rp_lama: margin_rp_lama,
        harga_jual_lama: harga_jual_lama,
        no_batch: no_batch,
        tgl_kadaluarsa: tgl_kadaluarsa,
        harga_beli: harga_beli,
        harga_margin: harga_margin,
        margin_persen: margin_persen,
        margin_rupiah: margin_rupiah,
        jml_pesan: jml_pesan,
        jml_terima: jml_terima,
        id_produk_unit: id_produk_unit,
        diskon_persen: diskon_persen,
        diskon_rupiah: diskon_rupiah,
        ppn_persen: ppn_persen,
        ppn_rupiah: ppn_rupiah,
        total: total,
      },
      success: function(response) {
        if(response.res === true) {
          $("#modalPenerimaanPembelian").modal('hide');
          window.location.href = "{{ route('pembelian.index')}}";
        }else{
          Swal.fire("Gagal!", "Data unit gagal disimpan.", "error");
        }
      }
    });

  });
  //====End Simpan Penerimaan===================//

</script>
@stop

@extends('layouts.apotek.admin')

@section('title')
    <title>Pembelian</title>
@endsection

@section('content')

<main id="main" class="main">
  <!-- HIDE SIDEBAR -->
  <style type="text/css">
    .sidebar {
        left: -300px;
    }

    .toggle-sidebar #main,
    .toggle-sidebar #footer {
        margin-left: 0;
    }

    main,
    #footer {
        margin-left: 0px !important;
    }

</style>
<!-- END HIDE SIDEBAR -->
	<div class="pagetitle">
      <h1 class="d-flex justify-content-between align-items-center">
        Transaksi Pembelian
        <a href="{{ route('transaksi_pembelian.create') }}" class="btn btn-success btn-sm float-right"><i class="bi bi-plus-square"></i>&nbsp; Tambah Transaksi</a>
        <!-- <button type="button" class="btn btn-success btn-sm right">Tambah Transaksi</button> -->
      </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Transaksi</li>
          <li class="breadcrumb-item active">Pembelian</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <br>
                  <div class="row mb-3">
                    <div class="col-4">
                      {{-- <button class="btn btn-success" name="button_excel" id="button_excel" value="excel" type="submit" ><i class="bi bi-file-earmark-excel"></i> Excel</button>
                      <button class="btn btn-danger" name="button_pdf" id="button_pdf" value="pdf" type="submit"><i class="bi bi-file-earmark-pdf"></i> Pdf</button> --}}
                    </div>
                    <div class="col-2"></div>
                    <div class="col-3">
                      {{-- <input type="text" class="form-control" name="cari" id="cari" placeholder="Cari..."/> --}}
                    </div>
                    <div class="col-3">
                      <div class="input-group mb-3">
                          <input type="text" class="form-control" name="tanggal" id="tanggal" value="{{ request()->tanggal }}">
                          <button type="button" class="btn btn-secondary" name="button_cari_tanggal" id="button_cari_tanggal" value="tgl"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width: 100%;">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>No SP</th>
                              <th>Jenis SP</th>
                              <th>Tanggal</th>
                              <th>Pembelian</th>
                              <th>Supplier</th>
                              <th>Total</th>
                              <th>Jenis Transaksi</th>
                              <th>status Pembelian</th>
                              <th>status bayar</th>
                              <th hidden>Id User Input</th>
                              <th hidden>User Input</th>  
                              <th hidden>Id Apotek</th>
                              <th hidden>Nama Apotek</th>
                              <th>Opsi</th>
                          </tr>
                      </thead>
                      <tbody id="tabledata">
                          
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
        </div>
      </div>
    </section>

    <div class="modal fade" id="modalViewPenerimaan">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Detail Penerimaan Pembelian</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row mb-3">
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">No Surat Pesanan: </label> 
                <label for="inputNama" class="form-label penerimaan_no_sp" style="font-weight: bold;"></label> 
              </div>
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Jenis Transaksi: </label> 
                <label for="inputNama" class="form-label penerimaan_jenis" style="font-weight: bold;"></label>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Jenis Surat Pesanan: </label> 
                <label for="inputNama" class="form-label penerimaan_j_sp" style="font-weight: bold;"></label>
              </div>
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Termin: </label> 
                <label for="inputNama" class="form-label penerimaan_termin" style="font-weight: bold;"></label>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Kode Pembelian: </label> 
                <label for="inputNama" class="form-label penerimaan_pembelian" style="font-weight: bold;"></label>
              </div>
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Tgl. Jatuh Tempo: </label> 
                <label for="inputNama" class="form-label penerimaan_tgl_jt" style="font-weight: bold;"></label>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Supplier: </label> 
                <label for="inputNama" class="form-label penerimaan_supplier" style="font-weight: bold;"></label>
              </div>
            </div>

            <table id="datatabel" class="table table-striped table-bordered" style="width: 100%;">
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Kode Produk</th>
                      <th>Nama Produk</th>
                      <th>Harga Satuan</th>
                      <th>Jml Beli</th>
                      <th>Jml Terima</th>
                      <th>Satuan</th>
                      <th>Diskon (%)</th>
                      <th hidden>diskon_temp</th>
                      <th>Diskon (Rp)</th>
                      <th hidden>diskon_rp_temp</th>
                      <th>PPN (%)</th>
                      <th hidden>ppn_temp</th>
                      <th>PPN (Rp)</th>
                      <th hidden>ppn_rp_temp</th>
                      <th style="text-align: right;">Total</th>
                  </tr>
              </thead>
              <tbody id="penerimaan_tbl_detail" class="penerimaan_tbl_detail">

              </tbody>
              <tfoot>
                  <tr>
                      <td colspan="10"></td>
                      <td><b>Subtotal:</b></td>
                      <td class="penerimaan_f_subtotal" align="right" style="font-weight: bold;">
                        0
                      </td>
                      
                  </tr>
              </tfoot>
            </table>
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalViewPembelian">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Detail Transaksi Pembelian</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row mb-3">
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">No Surat Pesanan: </label> 
                <label for="inputNama" class="form-label no_sp" style="font-weight: bold;"></label> 
              </div>
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Jenis Transaksi: </label> 
                <label for="inputNama" class="form-label jenis" style="font-weight: bold;"></label>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Jenis Surat Pesanan: </label> 
                <label for="inputNama" class="form-label j_sp" style="font-weight: bold;"></label>
              </div>
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Termin: </label> 
                <label for="inputNama" class="form-label termin" style="font-weight: bold;"></label>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Kode Pembelian: </label> 
                <label for="inputNama" class="form-label pembelian" style="font-weight: bold;"></label>
              </div>
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Tgl. Jatuh Tempo: </label> 
                <label for="inputNama" class="form-label tgl_jt" style="font-weight: bold;"></label>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Supplier: </label> 
                <label for="inputNama" class="form-label supplier" style="font-weight: bold;"></label>
              </div>
            </div>

            <table id="datatabel" class="table table-striped table-bordered" style="width: 100%;">
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Kode Produk</th>
                      <th>Nama Produk</th>
                      <th>Harga Satuan</th>
                      <th>Jml Beli</th>
                      <th hidden>jml_temp</th>
                      <th>Satuan</th>
                      <th>Diskon (%)</th>
                      <th hidden>diskon_temp</th>
                      <th>Diskon (Rp)</th>
                      <th hidden>diskon_rp_temp</th>
                      <th>PPN (%)</th>
                      <th hidden>ppn_temp</th>
                      <th>PPN (Rp)</th>
                      <th hidden>ppn_rp_temp</th>
                      <th style="text-align: right;">Total</th>
                  </tr>
              </thead>
              <tbody id="tbl_detail" class="tbl_detail">

              </tbody>
              <tfoot>
                  <tr>
                      <td colspan="9"></td>
                      <td><b>Subtotal:</b></td>
                      <td class="f_subtotal" align="right" style="font-weight: bold;">
                        0
                      </td>
                      
                  </tr>
              </tfoot>
            </table>
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalPenerimaanPembelian">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Penerimaan Pembelian dari Supplier</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row mb-3">
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">No Surat Pesanan: </label> 
                <label for="inputNama" class="form-label no_sp_terima" style="font-weight: bold;"></label> 
              </div>
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Jenis Transaksi: </label> 
                <label for="inputNama" class="form-label jenis_terima" style="font-weight: bold;"></label>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Jenis Surat Pesanan: </label> 
                <label for="inputNama" class="form-label j_sp_terima" style="font-weight: bold;"></label>
              </div>
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Termin: </label> 
                <label for="inputNama" class="form-label termin_terima" style="font-weight: bold;"></label>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Kode Pembelian: </label> 
                <label for="inputNama" class="form-label pembelian_terima" style="font-weight: bold;"></label>
              </div>
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Tgl. Jatuh Tempo: </label> 
                <label for="inputNama" class="form-label tgl_jt_terima" style="font-weight: bold;"></label>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-6">
                <label for="inputNama" class="form-label">Supplier: </label> 
                <label for="inputNama" class="form-label supplier_terima" style="font-weight: bold;"></label>
                <label for="inputNama" class="form-label kd_supplier" style="font-weight: bold;" hidden></label>
              </div>
              <div class="col-sm-6">
                <div class="row mb-3">
                  &nbsp;&nbsp;&nbsp;
                  No Faktur: 
                  <div class="col-sm-3">
                    <input type="text" name="no_faktur_terima" id="no_faktur_terima" class="form-control" placeholder="Masukan No Faktur..." value="" style="height: 30px; font-size: 14px;" required>
                  </div>
                </div>
              </div>
              
            </div>

            <table id="datatabel_terima" class="table table-striped table-bordered" style="width: 100%;">
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Kode Produk</th>
                      <th>Nama Produk</th>
                      <th>Harga Beli (Lama)</th>
                      <th hidden>Margin Persen (Lama)</th>
                      <th hidden>Margin Rp (Lama)</th>
                      <th>Harga Jual (Lama)</th>
                      <th>No Batch</th>
                      <th>Tgl Kadaluarsa</th>
                      <th>Harga Beli Satuan</th>
                      <th>Margin (%)</th>
                      <th>Margin (Rp)</th>
                      <th>Jml Pesan</th>
                      <th>jml terima</th>
                      <th>Satuan</th>
                      <th>Diskon (%)</th>
                      <th hidden>diskon_temp</th>
                      <th>Diskon (Rp)</th>
                      <th hidden>diskon_rp_temp</th>
                      <th>PPN (%)</th>
                      <th hidden>ppn_temp</th>
                      <th>PPN (Rp)</th>
                      <th hidden>ppn_rp_temp</th>
                      <th style="text-align: right;">Total</th>
                  </tr>
              </thead>
              <tbody id="tbl_detail_terima" class="tbl_detail_terima">

              </tbody>
              <tfoot>
                <tr>
                  <td colspan="13"></td>
                  <td></td>
                  <td></td>
                  
              </tr>
              <tr>
                <td></td>
                <td><b>Diskon(Rp):</b></td>
                <td class="f_diskon_rp" align="right" style="font-weight: bold;">
                  <input type="text"
                        style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;"
                        class="form-control" name="f_diskon_terima_rupiah" id="f_diskon_terima_rupiah" onkeyup="diskon();" value="0"/>
                </td>
                <td></td>
                <td><b>Diskon(%):</b></td>
                <td class="f_diskon_rp" align="right" style="font-weight: bold;">
                  <input type="text"
                        style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;"
                        class="form-control" name="f_diskon_terima_persen" id="f_diskon_terima_persen" onkeyup="diskon_persen();" value="0"/>
                </td>
                
                <td colspan="10"></td>
                <td><b>Subtotal:</b></td>
                <td class="f_subtotal_terima" align="right" style="font-weight: bold;" hidden>
                  0
                </td>
                <td class="temp_f_subtotal_terima" align="right" style="font-weight: bold;">
                  0
                </td>
                      
              </tr>
              </tfoot>
            </table>
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" id="button_terima_barang"><i class="bi bi-save"></i> Terima</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
          </div>
        </div>
      </div>
    </div>

</main>
@endsection



@section('js')
  
    
@endsection()