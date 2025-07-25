@section('js')
<script type="text/javascript">
    //Jika Super admin
    $("#pilih_lokasi").change(function() {
    let value = $("#pilih_lokasi").val();

    fetchAllJmlPenjualan();
    function fetchAllJmlPenjualan() {
      $.ajax({
        type: "GET",
        url: "{{ route('home/getDataJmlPenjualan') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(data) {
          console.log(data);
          $('.jumlah_penjualan').text(data.data.jml_penjualan); 
        }
      });
    }

    fetchAllTtlPenjualan();
    function fetchAllTtlPenjualan() {
      $.ajax({
        type: "GET",
        url: "{{ route('home/getDataTtlPenjualan') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(data) {
          console.log(data);

          var total = data.data.ttl_penjualan;
          if(total == null){
            var total_penjualan = 0;
            $('.total_penjualan').text(total_penjualan);
          }else{
            //membuat format rupiah//
            var reverse = data.data.ttl_penjualan.toString().split('').reverse().join(''),
                ribuan  = reverse.match(/\d{1,3}/g);
                total_penjualan = ribuan.join(',').split('').reverse().join('');
            //End membuat format rupiah//
            $('.total_penjualan').text(total_penjualan);
            // temp_total_penjualan = parseInt(temp_total_penjualan) + parseInt(data.data.ttl_penjualan);
          }
        }
      });
    }

    fetchAllJmlReturPenjualan();
    function fetchAllJmlReturPenjualan() {
      $.ajax({
        type: "GET",
        url: "{{ route('home/getDataJmlReturPenjualan') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(data) {
          console.log(data);
          $('.jumlah_retur_penjualan').text(data.data.jml_retur); 
        }
      });
    }

    fetchAllTtlReturPenjualan();
    function fetchAllTtlReturPenjualan() {
      $.ajax({
        type: "GET",
        url: "{{ route('home/getDataTtlReturPenjualan') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(data) {
          console.log(data);

          var total = data.data.ttl_retur_penjualan;
          if(total == null){
            var total_ribuan_ttl_retur = 0;
            $('.total_retur_penjualan').text(total_ribuan_ttl_retur);
          }else{
            //membuat format rupiah//
            var reverse_ttl_retur = data.data.ttl_retur_penjualan.toString().split('').reverse().join(''),
                ribuan_ttl_retur  = reverse_ttl_retur.match(/\d{1,3}/g);
                total_ribuan_ttl_retur = ribuan_ttl_retur.join(',').split('').reverse().join('');
            //End membuat format rupiah//
            $('.total_retur_penjualan').text(total_ribuan_ttl_retur);
            // temp_total_retur = data.data.ttl_retur_penjualan;
          }
        }
      });
    }

    fetchAllPendapatanUser();
    function fetchAllPendapatanUser() {
      $.ajax({
        type: "GET",
        url: "{{ route('home/getDataPendapatanUser') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(response) {
          let tabledata_staff;
          let no = 0;
          let hasil_pendapatan = 0;
          let hasil_retur = 0;
          let hasil_total_pendapatan = 0;
          response.data.forEach(penjualan => {
            var ttl_penjualan = penjualan.ttl_penjualan;
            var ttl_retur = penjualan.ttl_retur;
            var total_pendapatan = penjualan.total_pendapatan;

            //membuat format rupiah//
            var reverse_ttl_penjualan = ttl_penjualan.toString().split('').reverse().join(''),
                ribuan_ttl_penjualan  = reverse_ttl_penjualan.match(/\d{1,3}/g);
                total_ttl_penjualan = ribuan_ttl_penjualan.join(',').split('').reverse().join('');

            var reverse_ttl_retur = ttl_retur.toString().split('').reverse().join(''),
                ribuan_ttl_retur  = reverse_ttl_retur.match(/\d{1,3}/g);
                total_ttl_retur = ribuan_ttl_retur.join(',').split('').reverse().join('');

            var reverse_total_pendapatan = total_pendapatan.toString().split('').reverse().join(''),
                ribuan_total_pendapatan  = reverse_total_pendapatan.match(/\d{1,3}/g);
                total_total_pendapatan = ribuan_total_pendapatan.join(',').split('').reverse().join('');
            //End membuat format rupiah//

            no = no + 1
            tabledata_staff += `<tr>`;
            tabledata_staff += `<td>` +no+ `</td>`;
            tabledata_staff += `<td hidden>${penjualan.id}</td>`;
            tabledata_staff += `<td>${penjualan.name}</td>`;
            tabledata_staff += `<td align="right">${total_ttl_penjualan}</td>`;
            tabledata_staff += `<td align="right">${total_ttl_retur}</td>`;
            tabledata_staff += `<td align="right">${total_total_pendapatan}</td>`;
            tabledata_staff += `</tr>`;

            hasil_pendapatan = hasil_pendapatan + parseInt(ttl_penjualan);
            hasil_retur = hasil_retur + parseInt(ttl_retur);
            hasil_total_pendapatan = hasil_total_pendapatan + parseInt(total_pendapatan);
          });
          $("#tabledata_staff").html(tabledata_staff);

          //membuat format rupiah total//
          var reverse_hasil_pendapatan = hasil_pendapatan.toString().split('').reverse().join(''),
            ribuan_reverse_hasil_pendapatan  = reverse_hasil_pendapatan.match(/\d{1,3}/g);
            total_ribuan_reverse_hasil_pendapatan = ribuan_reverse_hasil_pendapatan.join(',').split('').reverse().join('');
          //End membuat format total//
          
          //membuat format rupiah total//
          var reverse_hasil_retur = hasil_retur.toString().split('').reverse().join(''),
            ribuan_reverse_hasil_retur  = reverse_hasil_retur.match(/\d{1,3}/g);
            total_ribuan_reverse_hasil_retur = ribuan_reverse_hasil_retur.join(',').split('').reverse().join('');
          //End membuat format total//
          
          //membuat format rupiah total//
          var reverse_hasil_total_pendapatan = hasil_total_pendapatan.toString().split('').reverse().join(''),
            ribuan_reverse_hasil_total_pendapatan  = reverse_hasil_total_pendapatan.match(/\d{1,3}/g);
            total_ribuan_reverse_hasil_total_pendapatan = ribuan_reverse_hasil_total_pendapatan.join(',').split('').reverse().join('');
          //End membuat format total//

          $(".f_pendapatan").text(total_ribuan_reverse_hasil_pendapatan);
          $(".f_retur").text(total_ribuan_reverse_hasil_retur);
          $(".f_total_pendapatan").text(total_ribuan_reverse_hasil_total_pendapatan);
        }
      });
    }

    //========== PIUTANG PANEL ================//
    fetchAllPiutangPanel();
    function fetchAllPiutangPanel() {
      $.ajax({
        type: "GET",
        url: "{{ route('home/getDataPiutangPanel') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(response) {
          let tabledata_piutang_panel;
          let no = 0;
          let hasil_piutang_panel = 0;
          let hasil_harga_faktur = 0;
          response.data.forEach(piutang => {
            var ttl_piutang = piutang.total_bayar;
            //membuat format rupiah//
            var reverse_ttl_piutang = ttl_piutang.toString().split('').reverse().join(''),
                ribuan_ttl_piutang  = reverse_ttl_piutang.match(/\d{1,3}/g);
                total_ttl_piutang = ribuan_ttl_piutang.join(',').split('').reverse().join('');
            //End membuat format rupiah//

            var ttl_harga_faktur = piutang.subtotal;
            //membuat format rupiah//
            var reverse_ttl_harga_faktur = ttl_harga_faktur.toString().split('').reverse().join(''),
                ribuan_ttl_harga_faktur  = reverse_ttl_harga_faktur.match(/\d{1,3}/g);
                total_ttl_harga_faktur = ribuan_ttl_harga_faktur.join(',').split('').reverse().join('');
            //End membuat format rupiah//

            no = no + 1
            tabledata_piutang_panel += `<tr>`;
            tabledata_piutang_panel += `<td>` +no+ `</td>`;
            tabledata_piutang_panel += `<td>${piutang.kode_penjualan}</td>`;
            tabledata_piutang_panel += `<td>${piutang.tgl_penjualan}</td>`;
            tabledata_piutang_panel += `<td>${piutang.nama_pembeli}</td>`;
            tabledata_piutang_panel += `<td>${piutang.no_tlp}</td>`;
            tabledata_piutang_panel += `<td>${piutang.termin} Hari</td>`;
            tabledata_piutang_panel += `<td>${piutang.tgl_jatuh_tempo}</td>`;
            tabledata_piutang_panel += `<td align="right">${total_ttl_piutang}</td>`;
            tabledata_piutang_panel += `<td align="right">${total_ttl_harga_faktur}</td>`;
            if(piutang.jatuh_tempo <= '2'){
              tabledata_piutang_panel += `<td style="background:red; color:white;">${piutang.jatuh_tempo} Hari</td>`;
            }else{
              tabledata_piutang_panel += `<td>${piutang.jatuh_tempo} Hari</td>`;
            }
            tabledata_piutang_panel += `<td align="center">
              <button type="button" 
              data-id="${piutang.kode_penjualan}" 
              data-tgl="${piutang.tgl_penjualan}"
              data-jenis="${piutang.jenis_penjualan}"
              data-nama_pembeli="${piutang.nama_pembeli}"
              data-tlp="${piutang.no_tlp}"
              data-termin="${piutang.termin}"
              data-jt="${piutang.tgl_jatuh_tempo}"
              data-ttl="${piutang.total_bayar}"
              id="button_bayar" class="btn btn-warning btn-sm"><i class="bi bi-cash-coin"></i></button>`;
            tabledata_piutang_panel += `</tr>`;

            hasil_piutang_panel = hasil_piutang_panel + parseInt(ttl_piutang);
            hasil_harga_faktur = hasil_harga_faktur + parseInt(ttl_harga_faktur);
          });
          $("#tabledata_piutang_panel").html(tabledata_piutang_panel);

          //membuat format rupiah total//
          var reverse_hasil_piutang_panel = hasil_piutang_panel.toString().split('').reverse().join(''),
            ribuan_reverse_hasil_piutang_panel  = reverse_hasil_piutang_panel.match(/\d{1,3}/g);
            total_ribuan_reverse_hasil_piutang_panel = ribuan_reverse_hasil_piutang_panel.join(',').split('').reverse().join('');

          var reverse_hasil_harga_faktur = hasil_harga_faktur.toString().split('').reverse().join(''),
            ribuan_reverse_hasil_harga_faktur  = reverse_hasil_harga_faktur.match(/\d{1,3}/g);
            total_ribuan_reverse_hasil_harga_faktur = ribuan_reverse_hasil_harga_faktur.join(',').split('').reverse().join('');
          //End membuat format total//

          $(".f_piutang_panel").text(total_ribuan_reverse_hasil_piutang_panel);
          $(".f_harga_faktur").text(total_ribuan_reverse_hasil_harga_faktur)
        }
      });
    }
    //========== END PIUTANG PANEL ================//

    fetchAllJmlPembelian();
    function fetchAllJmlPembelian() {
      $.ajax({
        type: "GET",
        url: "{{ route('home/getDataJmlPembelian') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(data) {
          console.log(data);
          $('.jumlah_pembelian').text(data.data.jml_pembelian); 
        }
      });
    }

    fetchAllTtlPembelian();
    function fetchAllTtlPembelian() {
      $.ajax({
        type: "GET",
        url: "{{ route('home/getDataTtlPembelian') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(data) {
          console.log(data);

          var total = data.data.ttl_pembelian;
          if(total == null){
            var total_pembelian = 0;
            $('.total_pembelian').text(total_pembelian);
          }else{
            //membuat format rupiah//
            var reverse = data.data.ttl_pembelian.toString().split('').reverse().join(''),
                ribuan  = reverse.match(/\d{1,3}/g);
                total_pembelian = ribuan.join(',').split('').reverse().join('');
            //End membuat format rupiah//
            $('.total_pembelian').text(total_pembelian);
            // temp_total_bayar_supplier = data.data.ttl_pembelian;
          }
        }
      });
    }

    fetchAllJmlKunjungan();
    function fetchAllJmlKunjungan() {
      $.ajax({
        type: "GET",
        url: "{{ route('home/getDataJmlKunjungan') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(data) {
          console.log(data);
          $('.jumlah_kunjungan').text(data.data.jml_kunjungan); 
        }
      });
    }
    // jumlah_periksa
    fetchAllJmlKunjunganSelesai();
    function fetchAllJmlKunjunganSelesai() {
      $.ajax({
        type: "GET",
        url: "{{ route('home/getDataJmlKunjunganSelesai') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(data) {
          console.log(data);
          $('.jumlah_periksa').text(data.data.jml_kunjungan); 
        }
      });
    }

    fetchAllPendapatanKlinik();
    function fetchAllPendapatanKlinik() {
      $.ajax({
        type: "GET",
        url: "{{ route('home/getDataPendapatanKunjungan') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(data) {
          console.log(data);

          var total = data.data.pendapatan_klinik;
          if(total == null){
            var total_pendapatan_klinik = 0;
            $('.pendapatan_klinik').text(total_pendapatan_klinik);
          }else{
            //membuat format rupiah//
            var reverse = data.data.pendapatan_klinik.toString().split('').reverse().join(''),
                ribuan  = reverse.match(/\d{1,3}/g);
                total_pendapatan_klinik = ribuan.join(',').split('').reverse().join('');
            //End membuat format rupiah//
            $('.pendapatan_klinik').text(total_pendapatan_klinik);
          }
        }
      });
    }

    fetchAllPendapatanPanel();
    function fetchAllPendapatanPanel() {
      $.ajax({
        type: "GET",
        url: "{{ route('home/getDataTtlPenjualanPanel') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(data) {
          console.log(data);

          var total = data.data.ttl_penjualan_panel;
          if(total == null){
            var total_pendapatan_panel = 0;
            $('.pendapatan_panel').text(total_pendapatan_panel);
          }else{
            //membuat format rupiah//
            var reverse = data.data.ttl_penjualan_panel.toString().split('').reverse().join(''),
                ribuan  = reverse.match(/\d{1,3}/g);
                total_pendapatan_panel = ribuan.join(',').split('').reverse().join('');
            //End membuat format rupiah//
            $('.pendapatan_panel').text(total_pendapatan_panel);
          }
        }
      });
    }

    fetchAllTotalPendapatan();
    function fetchAllTotalPendapatan() {
      //====Pendapatan Apotek=====//
      $.ajax({
        type: "GET",
        url: "{{ route('home/getDataTotalPendapatan') }}",
        data: {
          value: value
        },
        dataType: "json",
        success: function(response) {
          response.data.forEach(pendapatan => {
            if(pendapatan.ttl_penjualan == null){
              var temp_total_penjualan = 0;
            }else{
              var temp_total_penjualan = parseInt(pendapatan.ttl_penjualan);
            }

            if(pendapatan.ttl_retur == null){
              var temp_total_retur = 0;
            }else{
              var temp_total_retur = parseInt(pendapatan.ttl_retur);
            }

            if(pendapatan.ttl_pembayaran_supplier == null){
              var temp_total_pembayaran_supplier = 0;
            }else{
              var temp_total_pembayaran_supplier = parseInt(pendapatan.ttl_pembayaran_supplier);
            }

            if(pendapatan.ttl_pendapatan_klinik == null){
              var temp_total_pendapatan_klinik = 0;
            }else{
              var temp_total_pendapatan_klinik = parseInt(pendapatan.ttl_pendapatan_klinik);
            }

            var total_pendapatan_temp = temp_total_penjualan - temp_total_retur - temp_total_pembayaran_supplier + temp_total_pendapatan_klinik;
            
            if(total_pendapatan_temp == null){
              var total_pendapatan = 0;
              $('.total_pendapatan').text(total_pendapatan);
            }else{
              //membuat format rupiah//
              var reverse = total_pendapatan_temp.toString().split('').reverse().join(''),
                  ribuan  = reverse.match(/\d{1,3}/g);
                  total_pendapatan = ribuan.join(',').split('').reverse().join('');
              //End membuat format rupiah//
              
              $('.total_pendapatan').text(total_pendapatan);
            }
          });
        }
      });
    }


    });
    //End Jika Super admin

  //Jika Admin Biasa

  fetchAllJmlPenjualan();
  function fetchAllJmlPenjualan() {
    $.ajax({
      type: "GET",
      url: "{{ route('home/getDataJmlPenjualan') }}",
      dataType: "json",
      success: function(data) {
        console.log(data);
        $('.jumlah_penjualan').text(data.data.jml_penjualan); 
      }
    });
  }

  fetchAllTtlPenjualan();
  function fetchAllTtlPenjualan() {
    $.ajax({
      type: "GET",
      url: "{{ route('home/getDataTtlPenjualan') }}",
      dataType: "json",
      success: function(data) {
        console.log(data);

        var total = data.data.ttl_penjualan;
        if(total == null){
          var total_penjualan = 0;
          $('.total_penjualan').text(total_penjualan);
        }else{
          //membuat format rupiah//
          var reverse = data.data.ttl_penjualan.toString().split('').reverse().join(''),
              ribuan  = reverse.match(/\d{1,3}/g);
              total_penjualan = ribuan.join(',').split('').reverse().join('');
          //End membuat format rupiah//
          $('.total_penjualan').text(total_penjualan);
          // temp_total_penjualan = parseInt(temp_total_penjualan) + parseInt(data.data.ttl_penjualan);
        }
      }
    });
  }

  fetchAllJmlReturPenjualan();
  function fetchAllJmlReturPenjualan() {
    $.ajax({
      type: "GET",
      url: "{{ route('home/getDataJmlReturPenjualan') }}",
      dataType: "json",
      success: function(data) {
        console.log(data);
        $('.jumlah_retur_penjualan').text(data.data.jml_retur); 
      }
    });
  }

  fetchAllTtlReturPenjualan();
  function fetchAllTtlReturPenjualan() {
    $.ajax({
      type: "GET",
      url: "{{ route('home/getDataTtlReturPenjualan') }}",
      dataType: "json",
      success: function(data) {
        console.log(data);

        var total = data.data.ttl_retur_penjualan;
        if(total == null){
          var total_ribuan_ttl_retur = 0;
          $('.total_retur_penjualan').text(total_ribuan_ttl_retur);
        }else{
          //membuat format rupiah//
          var reverse_ttl_retur = data.data.ttl_retur_penjualan.toString().split('').reverse().join(''),
              ribuan_ttl_retur  = reverse_ttl_retur.match(/\d{1,3}/g);
              total_ribuan_ttl_retur = ribuan_ttl_retur.join(',').split('').reverse().join('');
          //End membuat format rupiah//
          $('.total_retur_penjualan').text(total_ribuan_ttl_retur);
          // temp_total_retur = data.data.ttl_retur_penjualan;
        }
      }
    });
  }

  fetchAllPendapatanUser();
  function fetchAllPendapatanUser() {
    $.ajax({
      type: "GET",
      url: "{{ route('home/getDataPendapatanUser') }}",
      dataType: "json",
      success: function(response) {
        let tabledata_staff;
        let no = 0;
        let hasil_pendapatan = 0;
        let hasil_retur = 0;
        let hasil_total_pendapatan = 0;
        response.data.forEach(penjualan => {
          var ttl_penjualan = penjualan.ttl_penjualan;
          var ttl_retur = penjualan.ttl_retur;
          var total_pendapatan = penjualan.total_pendapatan;

          //membuat format rupiah//
          var reverse_ttl_penjualan = ttl_penjualan.toString().split('').reverse().join(''),
              ribuan_ttl_penjualan  = reverse_ttl_penjualan.match(/\d{1,3}/g);
              total_ttl_penjualan = ribuan_ttl_penjualan.join(',').split('').reverse().join('');

          var reverse_ttl_retur = ttl_retur.toString().split('').reverse().join(''),
              ribuan_ttl_retur  = reverse_ttl_retur.match(/\d{1,3}/g);
              total_ttl_retur = ribuan_ttl_retur.join(',').split('').reverse().join('');

          var reverse_total_pendapatan = total_pendapatan.toString().split('').reverse().join(''),
              ribuan_total_pendapatan  = reverse_total_pendapatan.match(/\d{1,3}/g);
              total_total_pendapatan = ribuan_total_pendapatan.join(',').split('').reverse().join('');
          //End membuat format rupiah//

          no = no + 1
          tabledata_staff += `<tr>`;
          tabledata_staff += `<td>` +no+ `</td>`;
          tabledata_staff += `<td hidden>${penjualan.id}</td>`;
          tabledata_staff += `<td>${penjualan.name}</td>`;
          tabledata_staff += `<td align="right">${total_ttl_penjualan}</td>`;
          tabledata_staff += `<td align="right">${total_ttl_retur}</td>`;
          tabledata_staff += `<td align="right">${total_total_pendapatan}</td>`;
          tabledata_staff += `</tr>`;

          hasil_pendapatan = hasil_pendapatan + parseInt(ttl_penjualan);
          hasil_retur = hasil_retur + parseInt(ttl_retur);
          hasil_total_pendapatan = hasil_total_pendapatan + parseInt(total_pendapatan);
        });
        $("#tabledata_staff").html(tabledata_staff);

        //membuat format rupiah total//
        var reverse_hasil_pendapatan = hasil_pendapatan.toString().split('').reverse().join(''),
          ribuan_reverse_hasil_pendapatan  = reverse_hasil_pendapatan.match(/\d{1,3}/g);
          total_ribuan_reverse_hasil_pendapatan = ribuan_reverse_hasil_pendapatan.join(',').split('').reverse().join('');
        //End membuat format total//
        
        //membuat format rupiah total//
        var reverse_hasil_retur = hasil_retur.toString().split('').reverse().join(''),
          ribuan_reverse_hasil_retur  = reverse_hasil_retur.match(/\d{1,3}/g);
          total_ribuan_reverse_hasil_retur = ribuan_reverse_hasil_retur.join(',').split('').reverse().join('');
        //End membuat format total//
        
        //membuat format rupiah total//
        var reverse_hasil_total_pendapatan = hasil_total_pendapatan.toString().split('').reverse().join(''),
          ribuan_reverse_hasil_total_pendapatan  = reverse_hasil_total_pendapatan.match(/\d{1,3}/g);
          total_ribuan_reverse_hasil_total_pendapatan = ribuan_reverse_hasil_total_pendapatan.join(',').split('').reverse().join('');
        //End membuat format total//

        $(".f_pendapatan").text(total_ribuan_reverse_hasil_pendapatan);
        $(".f_retur").text(total_ribuan_reverse_hasil_retur);
        $(".f_total_pendapatan").text(total_ribuan_reverse_hasil_total_pendapatan);
      }
    });
  }

  //========== PIUTANG PANEL ================//
//   fetchAllPiutangPanel();
//   function fetchAllPiutangPanel() {
//     $.ajax({
//       type: "GET",
//       url: "{{ route('home/getDataPiutangPanel') }}",
//       dataType: "json",
//       success: function(response) {
//         let tabledata_piutang_panel;
//         let no = 0;
//         let hasil_piutang_panel = 0;
//         response.data.forEach(piutang => {
//           var ttl_piutang = piutang.total_bayar;

//           //membuat format rupiah//
//           var reverse_ttl_piutang = ttl_piutang.toString().split('').reverse().join(''),
//               ribuan_ttl_piutang  = reverse_ttl_piutang.match(/\d{1,3}/g);
//               total_ttl_piutang = ribuan_ttl_piutang.join(',').split('').reverse().join('');
//           //End membuat format rupiah//

//           no = no + 1
//           tabledata_piutang_panel += `<tr>`;
//           tabledata_piutang_panel += `<td>` +no+ `</td>`;
//           tabledata_piutang_panel += `<td>${piutang.kode_penjualan}</td>`;
//           tabledata_piutang_panel += `<td>${piutang.tgl_penjualan}</td>`;
//           tabledata_piutang_panel += `<td>${piutang.nama_pembeli}</td>`;
//           tabledata_piutang_panel += `<td>${piutang.no_tlp}</td>`;
//           tabledata_piutang_panel += `<td>${piutang.termin}</td>`;
//           tabledata_piutang_panel += `<td>${piutang.tgl_jatuh_tempo}</td>`;
//           tabledata_piutang_panel += `<td align="right">${total_ttl_piutang}</td>`;
//           tabledata_piutang_panel += `<td align="center">
//             <button type="button" 
//             data-id="${piutang.kode_penjualan}" 
//             data-tgl="${piutang.tgl_penjualan}"
//             data-jenis="${piutang.jenis_penjualan}"
//             data-nama_pembeli="${piutang.nama_pembeli}"
//             data-tlp="${piutang.no_tlp}"
//             data-termin="${piutang.termin}"
//             data-jt="${piutang.tgl_jatuh_tempo}"
//             data-ttl="${piutang.total_bayar}"
//             id="button_bayar" class="btn btn-warning btn-sm"><i class="bi bi-cash-coin"></i></button>`;
//           tabledata_piutang_panel += `</tr>`;

//           hasil_piutang_panel = hasil_piutang_panel + parseInt(ttl_piutang);
//         });
//         $("#tabledata_piutang_panel").html(tabledata_piutang_panel);

//         //membuat format rupiah total//
//         var reverse_hasil_piutang_panel = hasil_piutang_panel.toString().split('').reverse().join(''),
//           ribuan_reverse_hasil_piutang_panel  = reverse_hasil_piutang_panel.match(/\d{1,3}/g);
//           total_ribuan_reverse_hasil_piutang_panel = ribuan_reverse_hasil_piutang_panel.join(',').split('').reverse().join('');
//         //End membuat format total//

//         $(".f_piutang_panel").text(total_ribuan_reverse_hasil_piutang_panel);
//       }
//     });
//   }

    fetchAllPiutangPanel();
    function fetchAllPiutangPanel() {
        $.ajax({
          type: "GET",
          url: "{{ route('home/getDataPiutangPanel') }}",
          dataType: "json",
          success: function(response) {
            let tabledata_piutang_panel;
            let no = 0;
            let hasil_piutang_panel = 0;
            let hasil_harga_faktur = 0;
            response.data.forEach(piutang => {
              var ttl_piutang = piutang.total_bayar;
              //membuat format rupiah//
              var reverse_ttl_piutang = ttl_piutang.toString().split('').reverse().join(''),
                  ribuan_ttl_piutang  = reverse_ttl_piutang.match(/\d{1,3}/g);
                  total_ttl_piutang = ribuan_ttl_piutang.join(',').split('').reverse().join('');
              //End membuat format rupiah//
    
              var ttl_harga_faktur = piutang.subtotal;
              //membuat format rupiah//
              var reverse_ttl_harga_faktur = ttl_harga_faktur.toString().split('').reverse().join(''),
                  ribuan_ttl_harga_faktur  = reverse_ttl_harga_faktur.match(/\d{1,3}/g);
                  total_ttl_harga_faktur = ribuan_ttl_harga_faktur.join(',').split('').reverse().join('');
              //End membuat format rupiah//
    
              no = no + 1
              tabledata_piutang_panel += `<tr>`;
              tabledata_piutang_panel += `<td>` +no+ `</td>`;
              tabledata_piutang_panel += `<td>${piutang.kode_penjualan}</td>`;
              tabledata_piutang_panel += `<td>${piutang.tgl_penjualan}</td>`;
              tabledata_piutang_panel += `<td>${piutang.nama_pembeli}</td>`;
              tabledata_piutang_panel += `<td>${piutang.no_tlp}</td>`;
              tabledata_piutang_panel += `<td>${piutang.termin} Hari</td>`;
              tabledata_piutang_panel += `<td>${piutang.tgl_jatuh_tempo}</td>`;
              tabledata_piutang_panel += `<td align="right">${total_ttl_piutang}</td>`;
              tabledata_piutang_panel += `<td align="right">${total_ttl_harga_faktur}</td>`;
              if(piutang.jatuh_tempo <= '2'){
                tabledata_piutang_panel += `<td style="background:red; color:white;">${piutang.jatuh_tempo} Hari</td>`;
              }else{
                tabledata_piutang_panel += `<td>${piutang.jatuh_tempo} Hari</td>`;
              }
              tabledata_piutang_panel += `<td align="center">
                <button type="button" 
                data-id="${piutang.kode_penjualan}" 
                data-tgl="${piutang.tgl_penjualan}"
                data-jenis="${piutang.jenis_penjualan}"
                data-nama_pembeli="${piutang.nama_pembeli}"
                data-tlp="${piutang.no_tlp}"
                data-termin="${piutang.termin}"
                data-jt="${piutang.tgl_jatuh_tempo}"
                data-ttl="${piutang.total_bayar}"
                id="button_bayar" class="btn btn-warning btn-sm"><i class="bi bi-cash-coin"></i></button>`;
              tabledata_piutang_panel += `</tr>`;
    
              hasil_piutang_panel = hasil_piutang_panel + parseInt(ttl_piutang);
              hasil_harga_faktur = hasil_harga_faktur + parseInt(ttl_harga_faktur);
            });
            $("#tabledata_piutang_panel").html(tabledata_piutang_panel);
    
            //membuat format rupiah total//
            var reverse_hasil_piutang_panel = hasil_piutang_panel.toString().split('').reverse().join(''),
              ribuan_reverse_hasil_piutang_panel  = reverse_hasil_piutang_panel.match(/\d{1,3}/g);
              total_ribuan_reverse_hasil_piutang_panel = ribuan_reverse_hasil_piutang_panel.join(',').split('').reverse().join('');
    
            var reverse_hasil_harga_faktur = hasil_harga_faktur.toString().split('').reverse().join(''),
              ribuan_reverse_hasil_harga_faktur  = reverse_hasil_harga_faktur.match(/\d{1,3}/g);
              total_ribuan_reverse_hasil_harga_faktur = ribuan_reverse_hasil_harga_faktur.join(',').split('').reverse().join('');
            //End membuat format total//
    
            $(".f_piutang_panel").text(total_ribuan_reverse_hasil_piutang_panel);
            $(".f_harga_faktur").text(total_ribuan_reverse_hasil_harga_faktur)
          }
        });
    }
  //========== END PIUTANG PANEL ================//

  fetchAllJmlPembelian();
  function fetchAllJmlPembelian() {
    $.ajax({
      type: "GET",
      url: "{{ route('home/getDataJmlPembelian') }}",
      dataType: "json",
      success: function(data) {
        console.log(data);
        $('.jumlah_pembelian').text(data.data.jml_pembelian); 
      }
    });
  }

  fetchAllTtlPembelian();
  function fetchAllTtlPembelian() {
    $.ajax({
      type: "GET",
      url: "{{ route('home/getDataTtlPembelian') }}",
      dataType: "json",
      success: function(data) {
        console.log(data);

        var total = data.data.ttl_pembelian;
        if(total == null){
          var total_pembelian = 0;
          $('.total_pembelian').text(total_pembelian);
        }else{
          //membuat format rupiah//
          var reverse = data.data.ttl_pembelian.toString().split('').reverse().join(''),
              ribuan  = reverse.match(/\d{1,3}/g);
              total_pembelian = ribuan.join(',').split('').reverse().join('');
          //End membuat format rupiah//
          $('.total_pembelian').text(total_pembelian);
          // temp_total_bayar_supplier = data.data.ttl_pembelian;
        }
      }
    });
  }

  fetchAllJmlKunjungan();
  function fetchAllJmlKunjungan() {
    $.ajax({
      type: "GET",
      url: "{{ route('home/getDataJmlKunjungan') }}",
      dataType: "json",
      success: function(data) {
        console.log(data);
        $('.jumlah_kunjungan').text(data.data.jml_kunjungan); 
      }
    });
  }
  // jumlah_periksa
  fetchAllJmlKunjunganSelesai();
  function fetchAllJmlKunjunganSelesai() {
    $.ajax({
      type: "GET",
      url: "{{ route('home/getDataJmlKunjunganSelesai') }}",
      dataType: "json",
      success: function(data) {
        console.log(data);
        $('.jumlah_periksa').text(data.data.jml_kunjungan); 
      }
    });
  }

  fetchAllPendapatanKlinik();
  function fetchAllPendapatanKlinik() {
    $.ajax({
      type: "GET",
      url: "{{ route('home/getDataPendapatanKunjungan') }}",
      dataType: "json",
      success: function(data) {
        console.log(data);

        var total = data.data.pendapatan_klinik;
        if(total == null){
          var total_pendapatan_klinik = 0;
          $('.pendapatan_klinik').text(total_pendapatan_klinik);
        }else{
          //membuat format rupiah//
          var reverse = data.data.pendapatan_klinik.toString().split('').reverse().join(''),
              ribuan  = reverse.match(/\d{1,3}/g);
              total_pendapatan_klinik = ribuan.join(',').split('').reverse().join('');
          //End membuat format rupiah//
          $('.pendapatan_klinik').text(total_pendapatan_klinik);
        }
      }
    });
  }
  
  fetchAllPendapatanPanel();
  function fetchAllPendapatanPanel() {
    $.ajax({
      type: "GET",
      url: "{{ route('home/getDataTtlPenjualanPanel') }}",
      dataType: "json",
      success: function(data) {
        console.log(data);

        var total = data.data.ttl_penjualan_panel;
        if(total == null){
          var total_pendapatan_panel = 0;
          $('.pendapatan_panel').text(total_pendapatan_panel);
        }else{
          //membuat format rupiah//
          var reverse = data.data.ttl_penjualan_panel.toString().split('').reverse().join(''),
              ribuan  = reverse.match(/\d{1,3}/g);
              total_pendapatan_panel = ribuan.join(',').split('').reverse().join('');
          //End membuat format rupiah//
          $('.pendapatan_panel').text(total_pendapatan_panel);
        }
      }
    });
  }

  fetchAllTotalPendapatan();
  function fetchAllTotalPendapatan() {
    //====Pendapatan Apotek=====//
    $.ajax({
      type: "GET",
      url: "{{ route('home/getDataTotalPendapatan') }}",
      dataType: "json",
      
      success: function(response) {
        response.data.forEach(pendapatan => {
          if(pendapatan.ttl_penjualan == null){
            var temp_total_penjualan = 0;
          }else{
            var temp_total_penjualan = parseInt(pendapatan.ttl_penjualan);
          }

          if(pendapatan.ttl_retur == null){
            var temp_total_retur = 0;
          }else{
            var temp_total_retur = parseInt(pendapatan.ttl_retur);
          }

          if(pendapatan.ttl_pembayaran_supplier == null){
            var temp_total_pembayaran_supplier = 0;
          }else{
            var temp_total_pembayaran_supplier = parseInt(pendapatan.ttl_pembayaran_supplier);
          }

          if(pendapatan.ttl_pendapatan_klinik == null){
            var temp_total_pendapatan_klinik = 0;
          }else{
            var temp_total_pendapatan_klinik = parseInt(pendapatan.ttl_pendapatan_klinik);
          }

          var total_pendapatan_temp = temp_total_penjualan - temp_total_retur + temp_total_pendapatan_klinik;
          
          if(total_pendapatan_temp == null){
            var total_pendapatan = 0;
            $('.total_pendapatan').text(total_pendapatan);
          }else{
            //membuat format rupiah//
            var reverse = total_pendapatan_temp.toString().split('').reverse().join(''),
                ribuan  = reverse.match(/\d{1,3}/g);
                total_pendapatan = ribuan.join(',').split('').reverse().join('');
            //End membuat format rupiah//
            
            $('.total_pendapatan').text(total_pendapatan);
          }
        });
      }
    });
  }

  //===Select terlaris====//
  fetchAllTerlaris();
  function fetchAllTerlaris() {
    $.ajax({
      type: "GET",
      url: "{{ route('home/getDataTerlaris') }}",
      dataType: "json",
      success: function(response) {
        let tabledata_terlaris;
        let no = 0;
        response.data.forEach(penjualan => {
          no = no + 1
          tabledata_terlaris += `<tr>`;
          tabledata_terlaris += `<td>` +no+ `</td>`;
          tabledata_terlaris += `<td>${penjualan.kode_produk}</td>`;
          tabledata_terlaris += `<td>${penjualan.nama_produk}</td>`;
          tabledata_terlaris += `<td>${penjualan.jml_jual_terkecil}</td>`;
          tabledata_terlaris += `<td>${penjualan.nama_unit}</td>`;
          tabledata_terlaris += `</tr>`;
        });
        $("#tabledata_terlaris").html(tabledata_terlaris);
      }
    });
  }
  //===End Select terlaris====//

  //===Select terlaris====//
  fetchAllHabis();
  function fetchAllHabis() {
    $.ajax({
      type: "GET",
      url: "{{ route('home/getDataHabis') }}",
      dataType: "json",
      success: function(response) {
        let tabledata_habis;
        let no = 0;
        response.data.forEach(habis => {
          no = no + 1
          tabledata_habis += `<tr>`;
          tabledata_habis += `<td>` +no+ `</td>`;
          tabledata_habis += `<td>${habis.kode_produk}</td>`;
          tabledata_habis += `<td>${habis.nama_produk}</td>`;
          tabledata_habis += `<td>${habis.qty}</td>`;
          tabledata_habis += `<td>${habis.qty_min}</td>`;
          tabledata_habis += `</tr>`;
        });
        $("#tabledata_habis").html(tabledata_habis);
      }
    });
  }
  //===End Select terlaris====//

  $(document).on("click", "#button_bayar", function(e) {
    e.preventDefault();
    let kode_transaksi = $(this).data('id');
    let jenis_transaksi = $(this).data('jenis');
    let nama_pembeli = $(this).data('nama_pembeli');
    let no_tlp = $(this).data('tlp');

    $("#kode_transaksi").val(kode_transaksi);
    $("#jenis_transaksi").val(jenis_transaksi);
    $("#nama_pembeli").val(nama_pembeli);
    $("#no_tlp").val(no_tlp);
    
    $.ajax({
      type: "GET",
      url: "{{ route('home/getDataPembayaranPanel') }}",
      data: {
        kode_transaksi: kode_transaksi
      },
      dataType: "json",
      success: function(response) {
        let data_obat;
        let no = 0;
        response.data.forEach(detail_obat => {
          let harga = detail_obat.harga;
          //membuat format rupiah total//
          var reverse_harga = harga.toString().split('').reverse().join(''),
          ribuan_harga  = reverse_harga.match(/\d{1,3}/g);
          temp_harga = ribuan_harga.join(',').split('').reverse().join('');
          //End membuat format total//

          let diskon_rp = detail_obat.diskon_rp;
          //membuat format rupiah total//
          var reverse_diskon_rp = diskon_rp.toString().split('').reverse().join(''),
          ribuan_diskon_rp  = reverse_diskon_rp.match(/\d{1,3}/g);
          temp_diskon_rp = ribuan_diskon_rp.join(',').split('').reverse().join('');
          //End membuat format total//

          let ppn_rp = detail_obat.ppn_rp;
          //membuat format rupiah total//
          var reverse_ppn_rp = ppn_rp.toString().split('').reverse().join(''),
          ribuan_ppn_rp  = reverse_ppn_rp.match(/\d{1,3}/g);
          temp_ppn_rp = ribuan_ppn_rp.join(',').split('').reverse().join('');
          //End membuat format total//

          let biaya_tambahan = detail_obat.biaya_tambahan;
          //membuat format rupiah total//
          var reverse_biaya_tambahan = biaya_tambahan.toString().split('').reverse().join(''),
          ribuan_biaya_tambahan  = reverse_biaya_tambahan.match(/\d{1,3}/g);
          temp_biaya_tambahan = ribuan_biaya_tambahan.join(',').split('').reverse().join('');
          //End membuat format total//

          let tuslah = detail_obat.tuslah;
          //membuat format rupiah total//
          var reverse_tuslah = tuslah.toString().split('').reverse().join(''),
          ribuan_tuslah  = reverse_tuslah.match(/\d{1,3}/g);
          temp_tuslah = ribuan_tuslah.join(',').split('').reverse().join('');
          //End membuat format total//

          let embalase = detail_obat.embalase;
          //membuat format rupiah total//
          var reverse_embalase = embalase.toString().split('').reverse().join(''),
          ribuan_embalase  = reverse_embalase.match(/\d{1,3}/g);
          temp_embalase = ribuan_embalase.join(',').split('').reverse().join('');
          //End membuat format total//

          let total = detail_obat.total;
          //membuat format rupiah total//
          var reverse_total = total.toString().split('').reverse().join(''),
          ribuan_total  = reverse_total.match(/\d{1,3}/g);
          temp_total = ribuan_total.join(',').split('').reverse().join('');
          //End membuat format total//

          no = no + 1
          data_obat += '<tr>';
          data_obat += '<td>' + no + '</td>';
          data_obat += '<td class="kode_produk">' +detail_obat.kode_produk+ '</td>';    
          data_obat += '<td class="nama_produk">' +detail_obat.nama_produk+ '</td>';
          data_obat += '<td class="stok" hidden>' +detail_obat.stok+ '</td>';
          data_obat += '<td class="harga" id="harga' + no +'" align="right">' +temp_harga+ '</td>';
          data_obat += '<td class="qty">' +detail_obat.qty+ '</td>';
          data_obat += '<td class="satuan">' +detail_obat.nama_unit+ '</td>';
          data_obat += '<td class="diskon_persen" id="diskon_persen' + no +'" align="right">' +detail_obat.diskon+ '</td>';
          data_obat += '<td class="diskon_rp" id="diskon_rp' + no +'" align="right">' +temp_diskon_rp+ '</td>';
          data_obat += '<td class="ppn_persen" id="ppn_persen' + no +'" align="right">' +detail_obat.ppn+ '</td>';
          data_obat += '<td class="ppn_rp" id="ppn_rp' + no +'" align="right">' +temp_ppn_rp+ '</td>';
          data_obat += '<td class="biaya_tambahan" id="biaya_tambahan' + no +'" align="right" hidden>' +temp_biaya_tambahan+ '</td>';
          data_obat += '<td class="tuslah" id="tuslah' + no +'" align="right" hidden>' +temp_tuslah+ '</td>'; //tuslah
          data_obat += '<td class="embalase" id="embalase' + no + '" align="right" hidden>' +temp_embalase+ '</td>'; //embalase
          data_obat += '<td class="total" id="total' + no +'" align="right">' +temp_total+ '</td>';    
          data_obat += '</tr>';
        });
        $("#data_obat").html(data_obat);
      }
    });

    $.ajax({
      type: "GET",
      url: "{{ route('home/getDataPembayaranPanel_Detail') }}",
      data: {
        kode_transaksi: kode_transaksi
      },
      dataType: "json",
      success: function(data) {
        console.log(data);
        var temp_subtotal = data.data.subtotal;
        //membuat format rupiah//
        var reverse_temp_subtotal = data.data.subtotal.toString().split('').reverse().join(''),
            ribuan_temp_subtotal  = reverse_temp_subtotal.match(/\d{1,3}/g);
            total_temp_subtotal = ribuan_temp_subtotal.join(',').split('').reverse().join('');
        //End membuat format rupiah//

        var temp_pembulatan = data.data.pembulatan;
        //membuat format rupiah//
        var reverse_temp_pembulatan = data.data.pembulatan.toString().split('').reverse().join(''),
            ribuan_temp_pembulatan  = reverse_temp_pembulatan.match(/\d{1,3}/g);
            total_temp_pembulatan = ribuan_temp_pembulatan.join(',').split('').reverse().join('');
        //End membuat format rupiah//

        var temp_total_bayar = data.data.total_bayar;
        //membuat format rupiah//
        var reverse_temp_total_bayar = data.data.total_bayar.toString().split('').reverse().join(''),
            ribuan_temp_total_bayar  = reverse_temp_total_bayar.match(/\d{1,3}/g);
            total_temp_total_bayar = ribuan_temp_total_bayar.join(',').split('').reverse().join('');
        //End membuat format rupiah//
        
        $(".f_subtotal").text(total_temp_subtotal);
        
        //====Pembulatan==================================
          var total_sum_pembulatan = Math.ceil(temp_subtotal/500)*500;
          //membuat format rupiah dari total total_sum_pembulatan//
          var reverse = total_sum_pembulatan.toString().split('').reverse().join(''),
          ribuan  = reverse.match(/\d{1,3}/g);
          hasil_total_sum_pembulatan = ribuan.join(',').split('').reverse().join('');
          //end membuat format rupiah dari total total_sum_pembulatan//

          pembulatan = total_sum_pembulatan-temp_subtotal;
          //membuat format rupiah dari total pembulatan//
          var reverse = pembulatan.toString().split('').reverse().join(''),
          ribuan  = reverse.match(/\d{1,3}/g);
          hasil_pembulatan = ribuan.join(',').split('').reverse().join('');
          //end membuat format rupiah dari total pembulatan//
        //====End Pembulatan==============================
        
        $("#f_pembulatan").val(hasil_pembulatan);
        $(".f_total_bayar").text(hasil_total_sum_pembulatan);
      }
    });

    $('#modalPembayaranPanel').modal('show');
  });
  
  //====Pembulatan=======================
  $("input[name='f_pembulatan']").keyup(function(e){
        var f_subtotal = $(".f_subtotal").text();
        var f_pembulatan = ($(this).val());

        //menghilangka format rupiah tambah_diskon//
        var temp_f_subtotal = f_subtotal.replace(/[.](?=.*?\.)/g, '');
        var temp_f_subtotal_jadi = parseInt(temp_f_subtotal.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah//

        //menghilangka format rupiah tambah_diskon//
        var temp_f_pembulatan = f_pembulatan.replace(/[.](?=.*?\.)/g, '');
        var temp_f_pembulatan_jadi = parseInt(temp_f_pembulatan.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah//

        var f_hasil_pembulatan = temp_f_pembulatan_jadi + temp_f_subtotal_jadi;
        
        //membuat format rupiah//
        var reverse = f_hasil_pembulatan.toString().split('').reverse().join(''),
            ribuan  = reverse.match(/\d{1,3}/g);
            f_hasil_pembulatan_jadi = ribuan.join(',').split('').reverse().join('');
        //End membuat format rupiah//
      
        $(".f_total_bayar").text(f_hasil_pembulatan_jadi); 
  })
  //====End Pembulatan===================

  //==== Jumlah Bayar =================== 
  $(document).ready(function(){
    $("#f_jml_bayar").maskMoney({thousands:',', decimal:'.', precision:0});
  });

  $("input[name='f_jml_bayar']").keyup(function(e){
        //var f_subtotal = ($("input[name='f_subtotal']").val());
        var f_subtotal = $(".f_total_bayar").text();
        var f_jml_bayar = ($(this).val());

        //menghilangka format rupiah tambah_diskon//
        var temp_f_subtotal = f_subtotal.replace(/[.](?=.*?\.)/g, '');
        var temp_f_subtotal_jadi = parseInt(temp_f_subtotal.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah//

        //menghilangka format rupiah tambah_diskon//
        var temp_f_jml_bayar = f_jml_bayar.replace(/[.](?=.*?\.)/g, '');
        var temp_f_jml_bayar_jadi = parseInt(temp_f_jml_bayar.replace(/[^0-9.]/g,''));
        //End menghilangka format rupiah//

        var f_kembali = temp_f_jml_bayar_jadi - temp_f_subtotal_jadi;
        //membuat format rupiah//
        var reverse = f_kembali.toString().split('').reverse().join(''),
            ribuan  = reverse.match(/\d{1,3}/g);
            hasil_f_kembali = ribuan.join(',').split('').reverse().join('');
        //End membuat format rupiah//
                        
        $(".f_kembali").text(hasil_f_kembali); 
    })
    //==== End Jumlah Bayar =================== 

    //==== proses pembayaran panel ============//
    $("#button_form_insert").click(function() {
      let kode_transaksi_panel = $("#kode_transaksi").val();
      let pembulatan = $("#f_pembulatan").val();
      let total_bayar = $(".f_total_bayar").text();
      let cara_bayar = $("#c_bayar").val();
      let bank = $("#c_bayar_nama_bank").val();
      let jml_bayar = $("#f_jml_bayar").val();
      let kembali = $(".f_kembali").text();

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        type: "POST",
        url: "{{ route('home/store') }}",
        data: {
          kode_transaksi_panel: kode_transaksi_panel,
          pembulatan: pembulatan,
          total_bayar: total_bayar,
          cara_bayar: cara_bayar,
          bank: bank,
          jml_bayar: jml_bayar,
          kembali: kembali,
        },
        success: function(response) {
          if(response.res == true) {
            window.location.href = "{{ route('home') }}";
          }else{
            Swal.fire("Gagal!", "Pembayaran gagal disimpan.", "error");
          }
        }
      });

    });
    //========================================//
    
    var z =1;
    function retur_supp(z)
    {
      let kode_produk = $('#kode_produk'+ z +'').val();
      let nama_produk = $('#nama_produk'+ z +'').val();
      let id_cabang = $('#id_cabang'+ z +'').val();
      let nama_cabang = $('#nama_cabang'+ z +'').val();
      let kode_supplier = $('#kode_supplier'+ z +'').val();
      let qty = $('#qty'+ z +'').val();

    
      $("#update_kode_produk").val(kode_produk);
      $("#update_nama_produk").val(nama_produk);
      $("#update_id_apotek").val(id_cabang);
      $("#update_apotek").val(nama_cabang);
      $("#update_jml").val(qty);

      $('#modalRetur').modal('show');
    }

    $("#button_retur").click(function() {
      if ($("#update_keterangan").val() == ""){
        alert("Keterangan harus dipilih/diisi. Keterangan tidak boleh kosong");
        $("#update_keterangan").focus();
        return (false);
      }

      let kode_produk_update = $("#update_kode_produk").val();
      let id_cabang_update = $("#update_id_apotek").val();
      let keterangan = $("#update_keterangan").val();
     
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        type: "POST",
        url: "{{ route('home/retur') }}",
        data: {
          kode_produk_update: kode_produk_update,
          id_cabang_update: id_cabang_update,
          keterangan: keterangan,
        },
        success: function(response) {
          if (response.status === true) {
            window.location.href = "{{ route('home') }}";
          }else{
            alert('Gagal, Data tidak berhasil diretur...');
          }
        }
      });
    });

</script>

@stop


@extends('layouts.apotek.admin')

@section('title')
    <title>Dashboard</title>  
@endsection

@section('content')

  

<main id="main" class="main">
    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav hidden>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">
            @if(Auth::user()->type == '1') <!-- Super Admin -->
              <!-- Sales Card -->
              <div class="col-xxl-12 col-md-6">
                <div class="card info-card revenue-card">
                  <div class="card-body">
                    <h5 class="card-title">Pilih Lokasi</h5>
                    <select name="pilih_lokasi" id="pilih_lokasi" class="form-select">
                      <option value="">Semua Cabang/Lokasi</option>
                      @foreach ($data_cabang as $row)
                        <option value="{{ $row->kode_cabang }}" {{ old('kode_cabang') == $row->kode_cabang ? 'selected':'' }}>{{ $row->nama_cabang }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="col-xxl-6 col-md-6">
                <div class="card info-card revenue-card">

                  {{-- <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                      <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                      </li>

                      <li><a class="dropdown-item" href="#">Hari ini</a></li>
                      <li><a class="dropdown-item" href="#">Bulan ini</a></li>
                      <li><a class="dropdown-item" href="#">Tahun Ini</a></li>
                    </ul>
                  </div> --}}

                  <div class="card-body">
                    <h5 class="card-title">Penjualan Apotek <span>| Hari ini</span></h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-cart-plus-fill"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="jumlah_penjualan"></h6>
                        {{-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}

                      </div>
                    </div>
                  </div>

                </div>
              </div><!-- End Sales Card -->

              <!-- Revenue Card -->
              <div class="col-xxl-6 col-md-6">
                <div class="card info-card revenue-card">

                  {{-- <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                      <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                      </li>

                      <li><a class="dropdown-item" href="#">Hari ini</a></li>
                      <li><a class="dropdown-item" href="#">Bulan ini</a></li>
                      <li><a class="dropdown-item" href="#">Tahun ini</a></li>
                    </ul>
                  </div> --}}

                  <div class="card-body">
                    <h5 class="card-title">Pendapatan Apotek <span>| Hari ini</span></h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-cash-coin"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="total_penjualan"></h6>
                        {{-- <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}

                      </div>
                    </div>
                  </div>

                </div>
              </div><!-- End Revenue Card -->

              <!-- RETUR APOTEK -->
              <div class="col-xxl-6 col-md-6">
                <div class="card info-card customers-card">

                  {{-- <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                      <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                      </li>

                      <li><a class="dropdown-item" href="#">Hari ini</a></li>
                      <li><a class="dropdown-item" href="#">Bulan ini</a></li>
                      <li><a class="dropdown-item" href="#">Tahun Ini</a></li>
                    </ul>
                  </div> --}}

                  <div class="card-body">
                    <h5 class="card-title">Retur Penjualan Apotek <span>| Hari ini</span></h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-cart-plus-fill"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="jumlah_retur_penjualan"></h6>
                        {{-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}

                      </div>
                    </div>
                  </div>

                </div>
              </div><!-- End RETUR APOTEK -->

              <div class="col-xxl-6 col-md-6">
                <div class="card info-card customers-card">

                  {{-- <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                      <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                      </li>

                      <li><a class="dropdown-item" href="#">Hari ini</a></li>
                      <li><a class="dropdown-item" href="#">Bulan ini</a></li>
                      <li><a class="dropdown-item" href="#">Tahun ini</a></li>
                    </ul>
                  </div> --}}

                  <div class="card-body">
                    <h5 class="card-title">Retur Pengeluaran Apotek <span>| Hari ini</span></h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-cash-coin"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="total_retur_penjualan"></h6>
                        {{-- <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}

                      </div>
                    </div>
                  </div>

                </div>
              </div><!-- End Revenue Card -->

              <div class="col-xxl-12 col-md-12">
                <div class="card info-card customers-card">
                  <div class="card-body">
                    <h5 class="card-title">Pendapatan Karyawan <span>| Hari ini</span></h5>

                    <div class="table-responsive">
                      <table id="example_pendapatan" class="table table-bordered" style="width: 100%;">
                        <thead>
                            <tr style="background-color: #f5f5f5;">
                                <th>No</th>
                                <th hidden>id</th>
                                <th>Nama Karyawan</th>
                                <th>Pendapatan</th>
                                <th>Retur</th>
                                <th>Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody id="tabledata_staff">
                            
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #f5f5f5;">
                              <td colspan="2" align="center" style="font-weight: bold;">T o t a l</td>
                              <td class="f_pendapatan" align="right" style="font-weight: bold;">0</td>
                              <td class="f_retur" align="right" style="font-weight: bold;">0</td>
                              <td class="f_total_pendapatan" align="right" style="font-weight: bold;">0</td>
                            </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xxl-12 col-md-12">
                <div class="card info-card customers-card">
                  <div class="card-body">
                    <h5 class="card-title">Piutang Panel</h5>

                    <div class="table-responsive">
                      <table id="example_piutang" class="table table-bordered" style="width: 100%;">
                        <thead>
                            <tr style="background-color: #f5f5f5;">
                                <th>No</th>
                                <th>Kode Penjualan</th>
                                <th>Tgl Transaksi</th>
                                <th>Nama</th>
                                <th>Telepon</th>
                                <th>Termin</th>
                                <th>Tgl JT</th>
                                <th>Total</th>
                                <th>Harga Faktur</th>
                                <th>JT</th>
                                <th>[bayar]</th>
                            </tr>
                        </thead>
                        <tbody id="tabledata_piutang_panel">
                            
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #f5f5f5;">
                              <td colspan="7" align="center" style="font-weight: bold;">T o t a l</td>
                              <td class="f_piutang_panel" align="right" style="font-weight: bold;">0</td>
                              <td class="f_harga_faktur" align="right" style="font-weight: bold;">0</td>
                              <td colspan="2"></td>
                            </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Sales Card -->
              <div class="col-xxl-6 col-md-6">
                <div class="card info-card customers-card">

                  {{-- <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                      <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                      </li>

                      <li><a class="dropdown-item" href="#">Hari ini</a></li>
                      <li><a class="dropdown-item" href="#">Bulan ini</a></li>
                      <li><a class="dropdown-item" href="#">Tahun Ini</a></li>
                    </ul>
                  </div> --}}

                  <div class="card-body">
                    <h5 class="card-title">Jumlah SP yang dibayar <span>| Hari ini</span></h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-cart-dash-fill"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="jumlah_pembelian"></h6>
                        {{-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}

                      </div>
                    </div>
                  </div>

                </div>
              </div><!-- End Sales Card -->

              <!-- Revenue Card -->
              <div class="col-xxl-6 col-md-6">
                <div class="card info-card customers-card">

                  {{-- <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                      <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                      </li>

                      <li><a class="dropdown-item" href="#">Hari ini</a></li>
                      <li><a class="dropdown-item" href="#">Bulan ini</a></li>
                      <li><a class="dropdown-item" href="#">Tahun ini</a></li>
                    </ul>
                  </div> --}}

                  <div class="card-body">
                    <h5 class="card-title">Total Pengeluaran SP <span>| Hari ini</span></h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-cash-coin"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="total_pembelian"></h6>
                      </div>
                    </div>
                  </div>

                </div>
              </div><!-- End Revenue Card -->

              <!-- Customers Card -->
              <div class="col-xxl-6 col-md-6">

                <div class="card info-card revenue-card">

                  {{-- <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                      <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                      </li>

                      <li><a class="dropdown-item" href="#">Hari ini</a></li>
                      <li><a class="dropdown-item" href="#">Bulan ini</a></li>
                      <li><a class="dropdown-item" href="#">Tahun ini</a></li>
                    </ul>
                  </div> --}}

                  <div class="card-body">
                    <h5 class="card-title">Kunjungan Pasien <span>| Hari ini</span></h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-people"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="jumlah_kunjungan"></h6>
                        {{-- <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span> --}}

                      </div>
                    </div>

                  </div>
                </div>

              </div><!-- End Customers Card -->

              <div class="col-xxl-6 col-md-6">

                <div class="card info-card revenue-card">

                  {{-- <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                      <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                      </li>

                      <li><a class="dropdown-item" href="#">Hari ini</a></li>
                      <li><a class="dropdown-item" href="#">Bulan ini</a></li>
                      <li><a class="dropdown-item" href="#">Tahun ini</a></li>
                    </ul>
                  </div> --}}

                  <div class="card-body">
                    <h5 class="card-title">Pendapatan Klinik <span>| Hari ini</span></h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-cash-coin"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="pendapatan_klinik"></h6>
                        {{-- <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span> --}}

                      </div>
                    </div>

                  </div>
                </div>

              </div><!-- End Customers Card -->

              
              <div class="col-xxl-6 col-md-6">

                <div class="card info-card sales-card">
                  <div class="card-body">
                    <h5 class="card-title">Total Pendapatan <span>| Hari ini</span></h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-cash-coin"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="total_pendapatan"></h6>
                      </div>
                    </div>

                  </div>
                </div>

              </div><!-- End Customers Card -->
              
              <div class="col-xxl-6 col-md-6">
                <div class="card info-card sales-card">
                  <div class="card-body">
                    <h5 class="card-title">Pendapatan Panel <span>| Hari ini</span></h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-cash-coin"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="pendapatan_panel"></h6>
                      </div>
                    </div>

                  </div>
                </div>
              </div><!-- End Customers Card -->
              
              <!-- data tabel Produk Kadaluarsa -->
              <div class="col-xxl-12 col-md-12">
                <div class="card info-card customers-card">
                  <div class="card-body">
                    <h5 class="card-title">Produk Hampir Kadaluarsa</h5>
                    
                    <div class="table-responsive">
                      <table id="datatables_paging_1" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                          <tr>
                              <th>No</th>
                              <th>Kode Barang</th>
                              <th>Nama Barang</th>
                              <th hidden>id_cabang</th>
                              <th>Apotek</th>
                              <th hidden>Kode Distributor</th>
                              <th>Distributor</th>
                              <th>Tgl Kadaluarsa</th>
                              <th>Sisa Stok</th>
                              <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no = 1; ?>
                          @forelse($data_barang_kadaluarsa as $val)
                            <tr>
                              <td>
                                {{ $no }}
                              </td>
                              <td class="kode_produk">
                                <input class="form-control" type="text" name="kode_produk[]" id="kode_produk{{ $no }}" value="{{ $val->kode_produk }}" hidden/>
                                {{ $val->kode_produk }}
                              </td>
                              <td class="nama_produk">
                                <input class="form-control" type="text" name="nama_produk[]" id="nama_produk{{ $no }}" value="{{ $val->nama_produk }}" hidden/>
                                {{ $val->nama_produk }}
                              </td>
                              <td class="id_cabang" hidden>
                                <input class="form-control" type="text" name="id_cabang[]" id="id_cabang{{ $no }}" value="{{ $val->kode_cabang }}" hidden/>
                                {{ $val->kode_cabang }}
                              </td>
                              <td class="nama_cabang">
                                <input class="form-control" type="text" name="nama_cabang[]" id="nama_cabang{{ $no }}" value="{{ $val->nama_cabang }}" hidden/>
                                {{ $val->nama_cabang }}
                              </td>
                              <td class="kode_supplier" hidden>
                                <input class="form-control" type="text" name="kode_supplier[]" id="kode_supplier{{ $no }}" value="{{ $val->id_supplier }}" hidden/>
                                {{ $val->id_supplier }}
                              </td>
                              <td>
                                {{ $val->nama_supplier }}
                              </td>
                              <td>
                                {{ $val->tgl_kadaluarsa }}
                              </td>
                              <td>
                                <input class="form-control" type="text" name="qty[]" id="qty{{ $no }}" value="{{ $val->qty }} {{ $val->nama_unit }}" hidden/>
                                {{ $val->qty }} {{ $val->nama_unit }}
                              </td>
                              <td>
                                <button type="button" name="button_transfer[]" id="button_transfer{{ $no }}" class="btn btn-warning btn-sm" onclick="retur_supp( {{ $no }} );">Retur</button>
                              </td>
                            </tr>
                            <?php $no++; ?>
                            @empty
                            <tr>
                              <td colspan="6" class="text-center">Tidak ada data untuk saat ini</td>
                            </tr>
                          @endforelse
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End data tabel Produk Kadaluarsa -->

            @elseif(Auth::user()->type == '2') <!-- Admin -->
                <!-- Sales Card -->
                <div class="col-xxl-4 col-md-4">
                  <div class="card info-card revenue-card">
  
                    <div class="card-body">
                      <h5 class="card-title">Penjualan Apotek <span>| Hari ini</span></h5>
  
                      <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                          <i class="bi bi-cart-plus-fill"></i>
                        </div>
                        <div class="ps-3">
                          <h6 class="jumlah_penjualan"></h6>
                          {{-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}
  
                        </div>
                      </div>
                    </div>
  
                  </div>
                </div><!-- End Sales Card -->
  
                <!-- RETUR APOTEK -->
                <div class="col-xxl-4 col-md-4">
                  <div class="card info-card customers-card">
  
                    <div class="card-body">
                      <h5 class="card-title">Retur Penjualan Apotek <span>| Hari ini</span></h5>
  
                      <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                          <i class="bi bi-cart-plus-fill"></i>
                        </div>
                        <div class="ps-3">
                          <h6 class="jumlah_retur_penjualan"></h6>
                          {{-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}
  
                        </div>
                      </div>
                    </div>
  
                  </div>
                </div><!-- End RETUR APOTEK -->
  
                <!-- Sales Card -->
                <div class="col-xxl-4 col-md-4">
                  <div class="card info-card customers-card">

                    <div class="card-body">
                      <h5 class="card-title">Jumlah SP yang dibayar <span>| Hari ini</span></h5>
  
                      <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                          <i class="bi bi-cart-dash-fill"></i>
                        </div>
                        <div class="ps-3">
                          <h6 class="jumlah_pembelian"></h6>
                          {{-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}
  
                        </div>
                      </div>
                    </div>
  
                  </div>
                </div><!-- End Sales Card -->
                
                <div class="col-xxl-12 col-md-12">
                  <div class="card info-card revenue-card">
                    <div class="card-body">
                      <h5 class="card-title">Kunjungan Pasien <span>| Hari ini</span></h5>
  
                      <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                          <i class="bi bi-people"></i>
                        </div>
                        <div class="ps-3">
                          <h6 class="jumlah_kunjungan"></h6>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- data tabel Produk Kadaluarsa -->
              <div class="col-xxl-12 col-md-12">
                <div class="card info-card customers-card">
                  <div class="card-body">
                    <h5 class="card-title">Produk Hampir Kadaluarsa</h5>
                    
                    <div class="table-responsive">
                      <table id="datatables_paging_1" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                          <tr>
                              <th>No</th>
                              <th>Kode Barang</th>
                              <th>Nama Barang</th>
                              <th hidden>id_cabang</th>
                              <th>Apotek</th>
                              <th hidden>Kode Distributor</th>
                              <th>Distributor</th>
                              <th>Tgl Kadaluarsa</th>
                              <th>Sisa Stok</th>
                              <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no = 1; ?>
                          @forelse($data_barang_kadaluarsa as $val)
                            <tr>
                              <td>
                                {{ $no }}
                              </td>
                              <td class="kode_produk">
                                <input class="form-control" type="text" name="kode_produk[]" id="kode_produk{{ $no }}" value="{{ $val->kode_produk }}" hidden/>
                                {{ $val->kode_produk }}
                              </td>
                              <td class="nama_produk">
                                <input class="form-control" type="text" name="nama_produk[]" id="nama_produk{{ $no }}" value="{{ $val->nama_produk }}" hidden/>
                                {{ $val->nama_produk }}
                              </td>
                              <td class="id_cabang" hidden>
                                <input class="form-control" type="text" name="id_cabang[]" id="id_cabang{{ $no }}" value="{{ $val->kode_cabang }}" hidden/>
                                {{ $val->kode_cabang }}
                              </td>
                              <td class="nama_cabang">
                                <input class="form-control" type="text" name="nama_cabang[]" id="nama_cabang{{ $no }}" value="{{ $val->nama_cabang }}" hidden/>
                                {{ $val->nama_cabang }}
                              </td>
                              <td class="kode_supplier" hidden>
                                <input class="form-control" type="text" name="kode_supplier[]" id="kode_supplier{{ $no }}" value="{{ $val->id_supplier }}" hidden/>
                                {{ $val->id_supplier }}
                              </td>
                              <td>
                                {{ $val->nama_supplier }}
                              </td>
                              <td>
                                {{ $val->tgl_kadaluarsa }}
                              </td>
                              <td>
                                <input class="form-control" type="text" name="qty[]" id="qty{{ $no }}" value="{{ $val->qty }} {{ $val->nama_unit }}" hidden/>
                                {{ $val->qty }} {{ $val->nama_unit }}
                              </td>
                              <td>
                                <button type="button" name="button_transfer[]" id="button_transfer{{ $no }}" class="btn btn-warning btn-sm" onclick="retur_supp( {{ $no }} );">Retur</button>
                              </td>
                            </tr>
                            <?php $no++; ?>
                            @empty
                            <tr>
                              <td colspan="6" class="text-center">Tidak ada data untuk saat ini</td>
                            </tr>
                          @endforelse
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End data tabel Produk Kadaluarsa -->
                
            @elseif(Auth::user()->type == '3') <!-- Dokter -->
              <div class="col-xxl-6 col-md-6">
                <div class="card info-card sales-card">
                  <div class="card-body">
                    <h5 class="card-title">Jumlah Pasien <span>| Hari ini</span></h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-people"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="jumlah_kunjungan"></h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xxl-6 col-md-6">
                <div class="card info-card revenue-card">
                  <div class="card-body">
                    <h5 class="card-title">Jumlah Pasien Selesai Periksa <span>| Hari ini</span></h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-people"></i>
                      </div>
                      <div class="ps-3">
                        <h6 class="jumlah_periksa"></h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endif
            





            <!-- Reports -->
            <div class="col-12" hidden>
              <div class="card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Hari ini</a></li>
                    <li><a class="dropdown-item" href="#">Bulan ini</a></li>
                    <li><a class="dropdown-item" href="#">Tahun Ini</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Laporan <span>Hari ini</span></h5>

                  <!-- Line Chart -->
                  <div id="reportsChart"></div>

                  <script>
                    document.addEventListener("DOMContentLoaded", () => {
                      new ApexCharts(document.querySelector("#reportsChart"), {
                        series: [{
                          name: 'Sales',
                          data: [31, 40, 28, 51, 42, 82, 56],
                        }, {
                          name: 'Revenue',
                          data: [11, 32, 45, 32, 34, 52, 41]
                        }, {
                          name: 'Customers',
                          data: [15, 11, 32, 18, 9, 24, 11]
                        }],
                        chart: {
                          height: 350,
                          type: 'area',
                          toolbar: {
                            show: false
                          },
                        },
                        markers: {
                          size: 4
                        },
                        colors: ['#4154f1', '#2eca6a', '#ff771d'],
                        fill: {
                          type: "gradient",
                          gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.3,
                            opacityTo: 0.4,
                            stops: [0, 90, 100]
                          }
                        },
                        dataLabels: {
                          enabled: false
                        },
                        stroke: {
                          curve: 'smooth',
                          width: 2
                        },
                        xaxis: {
                          type: 'datetime',
                          categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
                        },
                        tooltip: {
                          x: {
                            format: 'dd/MM/yy HH:mm'
                          },
                        }
                      }).render();
                    });
                  </script>
                  <!-- End Line Chart -->

                </div>

              </div>
            </div><!-- End Reports -->

            <!-- Recent Sales -->
            <div class="col-12" hidden>
              <div class="card recent-sales overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Recent Sales <span>| Today</span></h5>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th scope="row"><a href="#">#2457</a></th>
                        <td>Brandon Jacob</td>
                        <td><a href="#" class="text-primary">At praesentium minu</a></td>
                        <td>$64</td>
                        <td><span class="badge bg-success">Approved</span></td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#">#2147</a></th>
                        <td>Bridie Kessler</td>
                        <td><a href="#" class="text-primary">Blanditiis dolor omnis similique</a></td>
                        <td>$47</td>
                        <td><span class="badge bg-warning">Pending</span></td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#">#2049</a></th>
                        <td>Ashleigh Langosh</td>
                        <td><a href="#" class="text-primary">At recusandae consectetur</a></td>
                        <td>$147</td>
                        <td><span class="badge bg-success">Approved</span></td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#">#2644</a></th>
                        <td>Angus Grady</td>
                        <td><a href="#" class="text-primar">Ut voluptatem id earum et</a></td>
                        <td>$67</td>
                        <td><span class="badge bg-danger">Rejected</span></td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#">#2644</a></th>
                        <td>Raheem Lehner</td>
                        <td><a href="#" class="text-primary">Sunt similique distinctio</a></td>
                        <td>$165</td>
                        <td><span class="badge bg-success">Approved</span></td>
                      </tr>
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Recent Sales -->

            <!-- Top Selling -->
            <div class="col-12" hidden>
              <div class="card top-selling overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body pb-0">
                  <h5 class="card-title">Top Selling <span>| Today</span></h5>

                  <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">Preview</th>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Sold</th>
                        <th scope="col">Revenue</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-1.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Ut inventore ipsa voluptas nulla</a></td>
                        <td>$64</td>
                        <td class="fw-bold">124</td>
                        <td>$5,828</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-2.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Exercitationem similique doloremque</a></td>
                        <td>$46</td>
                        <td class="fw-bold">98</td>
                        <td>$4,508</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-3.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Doloribus nisi exercitationem</a></td>
                        <td>$59</td>
                        <td class="fw-bold">74</td>
                        <td>$4,366</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-4.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Officiis quaerat sint rerum error</a></td>
                        <td>$32</td>
                        <td class="fw-bold">63</td>
                        <td>$2,016</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-5.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Sit unde debitis delectus repellendus</a></td>
                        <td>$79</td>
                        <td class="fw-bold">41</td>
                        <td>$3,239</td>
                      </tr>
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Top Selling -->

          </div>
        </div><!-- End Left side columns -->









        <!-- Right side columns -->
        {{-- <div class="col-lg-4">

          <!-- Recent Activity -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body">
              <h5 class="card-title">Recent Activity <span>| Today</span></h5>

              <div class="activity">

                <div class="activity-item d-flex">
                  <div class="activite-label">32 min</div>
                  <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                  <div class="activity-content">
                    Quia quae rerum <a href="#" class="fw-bold text-dark">explicabo officiis</a> beatae
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">56 min</div>
                  <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                  <div class="activity-content">
                    Voluptatem blanditiis blanditiis eveniet
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">2 hrs</div>
                  <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                  <div class="activity-content">
                    Voluptates corrupti molestias voluptatem
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">1 day</div>
                  <i class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
                  <div class="activity-content">
                    Tempore autem saepe <a href="#" class="fw-bold text-dark">occaecati voluptatem</a> tempore
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">2 days</div>
                  <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                  <div class="activity-content">
                    Est sit eum reiciendis exercitationem
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">4 weeks</div>
                  <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                  <div class="activity-content">
                    Dicta dolorem harum nulla eius. Ut quidem quidem sit quas
                  </div>
                </div><!-- End activity item-->

              </div>

            </div>
          </div><!-- End Recent Activity -->

          <!-- Budget Report -->
          <div class="card" hidden>
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0">
              <h5 class="card-title">Budget Report <span>| This Month</span></h5>

              <div id="budgetChart" style="min-height: 400px;" class="echart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  var budgetChart = echarts.init(document.querySelector("#budgetChart")).setOption({
                    legend: {
                      data: ['Allocated Budget', 'Actual Spending']
                    },
                    radar: {
                      // shape: 'circle',
                      indicator: [{
                          name: 'Sales',
                          max: 6500
                        },
                        {
                          name: 'Administration',
                          max: 16000
                        },
                        {
                          name: 'Information Technology',
                          max: 30000
                        },
                        {
                          name: 'Customer Support',
                          max: 38000
                        },
                        {
                          name: 'Development',
                          max: 52000
                        },
                        {
                          name: 'Marketing',
                          max: 25000
                        }
                      ]
                    },
                    series: [{
                      name: 'Budget vs spending',
                      type: 'radar',
                      data: [{
                          value: [4200, 3000, 20000, 35000, 50000, 18000],
                          name: 'Allocated Budget'
                        },
                        {
                          value: [5000, 14000, 28000, 26000, 42000, 21000],
                          name: 'Actual Spending'
                        }
                      ]
                    }]
                  });
                });
              </script>

            </div>
          </div><!-- End Budget Report -->

          <!-- Website Traffic -->
          <div class="card" hidden>
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0">
              <h5 class="card-title">Website Traffic <span>| Today</span></h5>

              <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  echarts.init(document.querySelector("#trafficChart")).setOption({
                    tooltip: {
                      trigger: 'item'
                    },
                    legend: {
                      top: '5%',
                      left: 'center'
                    },
                    series: [{
                      name: 'Access From',
                      type: 'pie',
                      radius: ['40%', '70%'],
                      avoidLabelOverlap: false,
                      label: {
                        show: false,
                        position: 'center'
                      },
                      emphasis: {
                        label: {
                          show: true,
                          fontSize: '18',
                          fontWeight: 'bold'
                        }
                      },
                      labelLine: {
                        show: false
                      },
                      data: [{
                          value: 1048,
                          name: 'Search Engine'
                        },
                        {
                          value: 735,
                          name: 'Direct'
                        },
                        {
                          value: 580,
                          name: 'Email'
                        },
                        {
                          value: 484,
                          name: 'Union Ads'
                        },
                        {
                          value: 300,
                          name: 'Video Ads'
                        }
                      ]
                    }]
                  });
                });
              </script>

            </div>
          </div><!-- End Website Traffic -->

          <!-- News & Updates Traffic -->
          <div class="card" hidden>
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0">
              <h5 class="card-title">News &amp; Updates <span>| Today</span></h5>

              <div class="news">
                <div class="post-item clearfix">
                  <img src="assets/img/news-1.jpg" alt="">
                  <h4><a href="#">Nihil blanditiis at in nihil autem</a></h4>
                  <p>Sit recusandae non aspernatur laboriosam. Quia enim eligendi sed ut harum...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-2.jpg" alt="">
                  <h4><a href="#">Quidem autem et impedit</a></h4>
                  <p>Illo nemo neque maiores vitae officiis cum eum turos elan dries werona nande...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-3.jpg" alt="">
                  <h4><a href="#">Id quia et et ut maxime similique occaecati ut</a></h4>
                  <p>Fugiat voluptas vero eaque accusantium eos. Consequuntur sed ipsam et totam...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-4.jpg" alt="">
                  <h4><a href="#">Laborum corporis quo dara net para</a></h4>
                  <p>Qui enim quia optio. Eligendi aut asperiores enim repellendusvel rerum cuder...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-5.jpg" alt="">
                  <h4><a href="#">Et dolores corrupti quae illo quod dolor</a></h4>
                  <p>Odit ut eveniet modi reiciendis. Atque cupiditate libero beatae dignissimos eius...</p>
                </div>

              </div><!-- End sidebar recent posts-->

            </div>
          </div><!-- End News & Updates -->

        </div><!-- End Right side columns --> --}}

      </div>
    </section>

    {{-- Modal untuk bayar panel --}}
    <div class="modal fade" id="modalPembayaranPanel" tabindex="-1">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Pembayaran Panel</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <br>
                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" align="right">No Transaksi</label>
                    <div class="col-sm-3">
                      <input type="text" name="kode_transaksi" id="kode_transaksi" class="form-control" value="" required readonly>
                    </div>
                    
                    <label class="col-sm-2 col-form-label" align="right">Jenis Transaksi</label>
                    <div class="col-sm-3">
                      <input type="text" name="jenis_transaksi" id="jenis_transaksi" class="form-control" required readonly>
                    </div>
                  </div>
                  
                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" align="right"></label>
                    <div class="col-sm-3">
                      
                    </div>
  
                    <label class="col-sm-2 col-form-label" align="right">Nama Pembeli</label>
                    <div class="col-sm-3">
                      <input type="text" name="nama_pembeli" id="nama_pembeli" class="form-control" value="" required readonly>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" align="right"></label>
                    <div class="col-sm-3">
                      
                    </div>
  
                    <label class="col-sm-2 col-form-label" align="right">No Tlp</label>
                    <div class="col-sm-3">
                      <input type="text" name="no_tlp" id="no_tlp" class="form-control" value="" required readonly>
                    </div>
                  </div>
  
                  <hr style="border:0; height: 1px; background-color: #D3D3D3; ">
  
                  <div class="row mb-3">
                    <div class="col-sm-12">
                      <div class="table-responsive">
                        <table id="datatabel_data_obat" class="table table-striped table-bordered" style="width: 100%; height: 28px; font-size: 14px;">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th>Kode Produk</th>
                              <th>Nama Produk</th>
                              <th hidden>Stok</th>
                              <th>Harga</th>
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
                              <th hidden>Biaya Tambahan</th>
                              <th hidden>Tuslah</th>
                              <th hidden>Embalase</th>
                              <th>Subtotal</th>
                              <th style="text-align: center;" hidden>Action</th>
                            </tr>
                          </thead>
                          <tbody id="data_obat" class="data_obat">
          
                          </tbody>
                          <tfoot>
                            <tr>
                              <td colspan="9"></td>
                              <td align="right"><b>Subtotal:</b></td>
                              <td width="130px" class="f_subtotal" id="f_subtotal" align="right" style="font-weight: bold;">
                                0
                              </td>
                            </tr>
                            <tr>
                              <td colspan="9"></td>
                              <td align="right"><b>Pembulatan:</b></td>
                              {{-- <td width="130px" class="f_pembulatan" id="f_pembulatan" align="right" style="font-weight: bold;">
                                0
                              </td> --}}
                              <td colspan="2">
                                <input type="text"
                                    style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;"
                                    class="form-control" name="f_pembulatan" id="f_pembulatan" value="0"
                                    />
                              </td>
                            </tr>
                            <tr>
                              <td colspan="9"></td>
                              <td align="right"><b>Total Bayar:</b></td>
                              <td width="130px" class="f_total_bayar" id="f_total_bayar" align="right" style="font-weight: bold;">
                                0
                              </td>
                            </tr>
                            <tr>
                              <td colspan="9"></td>
                              <td align="right"><b>Cara Bayar:</b></td>
                              <td width="130px" class="f_cara_bayar" id="f_cara_bayar" align="right" style="font-weight: bold;">
                                  <select name="c_bayar" id="c_bayar" class="form-select" style="height: 30px; font-size: 13px;" required>
                                    <option value="">Pilih...</option>
                                    <option value="Tunai">Tunai</option>
                                    <option value="Debit">Debit</option>
                                  </select>
                              </td>
                            </tr>
                            <tr>
                              <td colspan="9"></td>
                              <td align="right"><b>Bank:</b></td>
                              <td width="130px" class="c_bayar_bank" id="c_bayar_bank" align="right" style="font-weight: bold;">
                                <select name="c_bayar_nama_bank" id="c_bayar_nama_bank" class="form-select"
                                style="height: 30px; font-size: 13px;">
                                  <option value="">Pilih...</option>
                                  <option value="BCA">BCA</option>
                                  <option value="BNI">BNI</option>
                                  <option value="MANDIRI">MANDIRI</option>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td colspan="9"></td>
                              <td align="right"><b>Jml Bayar:</b></td>
                              <td width="130px" align="right" style="font-weight: bold;">
                                <input type="text"
                                  style="height: 20px; font-size: 13px; font-weight: bold; text-align: right;"
                                  class="form-control" name="f_jml_bayar" id="f_jml_bayar" value="0"
                                  required />
                              </td>
                            </tr>
                            <tr>
                              <td colspan="9"></td>
                              <td align="right"><b>Kembali:</b></td>
                              <td width="130px" class="f_kembali" id="f_kembali" align="right" style="font-weight: bold;">
                                0
                              </td>
                            </tr>
                                
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>

                  <hr style="border:0; height: 1px; background-color: #D3D3D3; ">
  
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" id="button_form_insert" data-dismiss="modal"><i class="bi bi-save"></i> Bayar</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
          </div>
        </div>
      </div>
    </div>
    {{-- End Modal untuk bayar panel --}}
    
    <div class="modal fade" id="modalRetur" tabindex="-1">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Retur Supplier</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="col-md-12">
              <label for="inputName5" class="form-label">Kode Produk</label>
              <input type="text" class="form-control" name="update_kode_produk" id="update_kode_produk" readonly required>
            </div>
            <br>
            <div class="col-md-12">
              <label for="inputEmail5" class="form-label">Nama Produk</label>
              <input type="email" class="form-control" name="update_nama_produk" id="update_nama_produk" readonly required>
            </div>
            <br>
            <div class="col-md-12">
              <label for="inputAddress5" class="form-label">Apotek</label>
              <input type="hidden" class="form-control" name="update_id_apotek" id="update_id_apotek" readonly required>
              <input type="text" class="form-control" name="update_apotek" id="update_apotek" readonly required>
            </div>
            <br>
            <div class="col-md-12">
              <label for="inputAddress5" class="form-label">Jml Retur</label>
              <input type="text" class="form-control" name="update_jml" id="update_jml" readonly required>
            </div>
            <br>
            <div class="col-12">
              <label for="inputState" class="form-label">Keterangan</label>
              <select name="update_keterangan" id="update_keterangan" class="form-select" required>
                <option value="">Pilih...</option>
                <option value="pemutihan">Pemutihan</option>
                <option value="retur">Retur</option>
                {{-- <option value="lain-lain">Lain-lain</option> --}}
              </select>
            </div>  
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-warning" id="button_retur" data-dismiss="modal"><i class="bi bi-save"></i> Retur</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
          </div>
        </div>
      </div>
    </div>
</main><!-- End #main -->

@endsection


