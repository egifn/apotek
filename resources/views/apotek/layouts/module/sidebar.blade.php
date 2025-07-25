<!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="{{ route('home') }}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>


      <!-- Menu Master Data -->
      @if(Auth::user()->type == '1') <!-- Super Admin -->
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-folder-fill"></i><span>Master Data</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('apotek.index') }}">
                <i class="bi bi-circle"></i><span>Apotek/Toko</span>
              </a>
            </li>
            <li>
              <a href="{{ route('pengguna.index') }}">
                <i class="bi bi-circle"></i><span>Pengguna</span>
              </a>
            </li>
            <li>
              <a href="{{ route('pegawai.index') }}">
                <i class="bi bi-circle"></i><span>Pegawai</span>
              </a>
            </li>
            <li>
              <a href="{{ route('supplier.index') }}">
                <i class="bi bi-circle"></i><span>Supplier</span>
              </a>
            </li>
            <li>
              <a href="{{ route('produk.index') }}">
                <i class="bi bi-circle"></i><span>Produk</span>
              </a>
            </li>
            <li>
              <a href="{{ route('barcode.index') }}">
                <i class="bi bi-circle"></i><span>Barcode</span>
              </a>
            </li>
            <li>
              <a href="{{ route('jenis.index') }}">
                <i class="bi bi-circle"></i><span>Jenis Obat</span>
              </a>
            </li>
            <li>
              <a href="{{ route('kemasan.index') }}">
                <i class="bi bi-circle"></i><span>Satuan Obat</span>
              </a>
            </li>
            <li>
              <a href="{{ route('tuslah.index') }}">
                <i class="bi bi-circle"></i><span>Tuslah</span>
              </a>
            </li>
            <li>
              <a href="{{ route('poli.index') }}">
                <i class="bi bi-circle"></i><span>Poli</span>
              </a>
            </li>
            <li>
              <a href="{{ route('jasa.index') }}">
                <i class="bi bi-circle"></i><span>Pelayanan</span>
              </a>
            </li>
            <li hidden>
              <a href="#">
                <i class="bi bi-circle"></i><span>Diagnosa</span>
              </a>
            </li>
          </ul>
        </li>
      {{-- @elseif(Auth::user()->type == '3') <!-- Dokter -->
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-folder-fill"></i><span>Master Data</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="#">
                <i class="bi bi-circle"></i><span>Diagnosa</span>
              </a>
            </li>
          </ul>
        </li> --}}
      @endif

      <!-- scan barcode -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('barcode_scan.index') }}">
          <i class="bi bi-upc-scan"></i>
          <span>Scan Barcode</span>
        </a>
      </li>

      <!-- Menu Apotek dan Klinik -->
      @if(Auth::user()->type == '1') <!-- Super Admin dan Admin -->
        <li class="nav-heading">Apotek</li>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-cart-fill"></i><span>Transaksi</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('penjualan.index') }}">
                <i class="bi bi-circle"></i><span>Penjualan</span>
              </a>
            </li>
            <li>
              <a href="{{ route('pembelian.index') }}">
                <i class="bi bi-circle"></i><span>Pembelian</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('pendapatan.index') }}">
            <i class="bi bi-wallet2"></i>
            <span>Pendapatan Apotek</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('hutang_pembelian.index') }}">
            <i class="bi bi-cash-coin"></i>
            <span>Bayar Pembelian</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#retur-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-cart-x-fill"></i><span>Retur</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="retur-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('retur_penjualan.index') }}">
                <i class="bi bi-circle"></i><span>Penjualan</span>
              </a>
            </li>
            <li>
              <a href="{{ route('retur_pembelian.index') }}">
                <i class="bi bi-circle"></i><span>Pembelian</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('mutasi.index') }}">
            <i class="bi bi-card-checklist"></i>
            <span>Mutasi Barang</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('kartu_stok.index') }}">
            <i class="bi bi-card-checklist"></i>
            <span>Kartu Stok</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('stok_opname.index') }}">
            <i class="bi bi-clipboard-check"></i>
            <span>Stok Opname</span>
          </a>
        </li>

        <li class="nav-heading">Klinik</li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('pendaftaran.index') }}">
            <i class="bi bi-file-person"></i>
            <span>Pendaftaran</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('pelayanan.index') }}">
            <i class="bi bi-building"></i>
            <span>Kunjungan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('pemeriksaan.index') }}">
            <i class="bi bi-door-open-fill"></i>
            <span>Pemeriksaan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('rekam_medis.index') }}">
            <i class="bi bi-book"></i>
            <span>Rekam Medis</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="">
            <i class="bi bi-envelope-fill"></i>
            <span>Surat Sehat & Sakit</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('farmasi.index') }}">
            <i class="bi bi-eyedropper"></i>
            <span>Farmasi</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('kasir.index') }}">
            <i class="bi bi-cash-coin"></i>
            <span>Kasir</span>
          </a>
        </li>
      @elseif(Auth::user()->type == '2') <!-- Admin -->
        <li class="nav-heading">Apotek</li>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-cart-fill"></i><span>Transaksi</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('penjualan.index') }}">
                <i class="bi bi-circle"></i><span>Penjualan</span>
              </a>
            </li>
            <li>
              <a href="{{ route('pembelian.index') }}">
                <i class="bi bi-circle"></i><span>Pembelian</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#retur-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-cart-x-fill"></i><span>Retur</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="retur-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('retur_penjualan.index') }}">
                <i class="bi bi-circle"></i><span>Penjualan</span>
              </a>
            </li>
            <li>
              <a href="{{ route('retur_pembelian.index') }}">
                <i class="bi bi-circle"></i><span>Pembelian</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('kartu_stok.index') }}">
            <i class="bi bi-card-checklist"></i>
            <span>Kartu Stok</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('stok_opname.index') }}">
            <i class="bi bi-clipboard-check"></i>
            <span>Stok Opname</span>
          </a>
        </li>

        <li class="nav-heading">Klinik</li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('pendaftaran.index') }}">
            <i class="bi bi-file-person"></i>
            <span>Pendaftaran</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('pelayanan.index') }}">
            <i class="bi bi-building"></i>
            <span>Kunjungan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('farmasi.index') }}">
            <i class="bi bi-eyedropper"></i>
            <span>Farmasi</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('kasir.index') }}">
            <i class="bi bi-cash-coin"></i>
            <span>Kasir</span>
          </a>
        </li>
      @elseif(Auth::user()->type == '3') <!-- Dokter -->
        <li class="nav-heading">Klinik</li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('pemeriksaan.index') }}">
            <i class="bi bi-door-open-fill"></i>
            <span>Pemeriksaan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('rekam_medis.index') }}">
            <i class="bi bi-book"></i>
            <span>Rekam Medis</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="#">
            <i class="bi bi-envelope-fill"></i>
            <span>Surat Sehat & Sakit</span>
          </a>
        </li>
      @endif

      
      @if(Auth::user()->type == '1') <!-- Super Admin dan Admin -->
        <li class="nav-heading">Pages</li>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-layout-text-window-reverse"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('laporan_penjualan.index') }}">
                <i class="bi bi-circle"></i><span>Transaksi Penjualan per Obat</span>
              </a>
            </li>
            <li>
              <a href="{{ route('laporan_penjualan_Pertransaksi.index') }}">
                <i class="bi bi-circle"></i><span>Transaksi Penjualan per Transaksi</span>
              </a>
            </li>
            
            <li>
              <a href="{{ route('laporan_penjualan_panel.index') }}">
                <i class="bi bi-circle"></i><span>Transaksi Penjualan Panel per Obat</span>
              </a>
            </li>
            <li>
              <a href="{{ route('laporan_panel_pertransaksi.index') }}">
                <i class="bi bi-circle"></i><span>Transaksi Penjualan Panel per Transaksi</span>
              </a>
            </li>
            <li>
              <a href="{{ route('laporan_panel_piutang.index') }}">
                <i class="bi bi-circle"></i><span>Piutang Penjualan Panel</span>
              </a>
            </li>
            
            <li>
              <a href="{{ route('laporan_pembelian.index') }}">
                <i class="bi bi-circle"></i><span>Transaksi Pembelian</span>
              </a>
            </li>
            <li>
              <a href="{{ route('laporan_penerimaan.index') }}">
                <i class="bi bi-circle"></i><span>Penerimaan Barang</span>
              </a>
            </li>
            <li hidden>
              <a href="">
                <i class="bi bi-circle"></i><span>Retur Penjualan</span>
              </a>
            </li>
            <li hidden>
              <a href="">
                <i class="bi bi-circle"></i><span>Retur Pembelian</span>
              </a>
            </li>
            <li>
              <a href="{{ route('laporan_retur_k.index') }}">
                <i class="bi bi-circle"></i><span>Retur Kadaluarsa</span>
              </a>
            </li>
            <li>
              <a href="{{ route('laporan_narkotika_psikotropika.index') }}">
                <i class="bi bi-circle"></i><span>Narkotika dan Psikotropika</span>
              </a>
            </li>
            <li>
              <a href="{{ route('kartu_stok.index') }}">
                <i class="bi bi-circle"></i><span>Kartu Stok</span>
              </a>
            </li>
            <li>
              <a href="{{ route('laporan_stok.index') }}">
                <i class="bi bi-circle"></i><span>Stok</span>
              </a>
            </li>
            <li>
              <a href="{{ route('laporan_stok_opname.index') }}">
                <i class="bi bi-circle"></i><span>Stok Opname</span>
              </a>
            </li>
            <li>
              <a href="{{ route('laporan_klinik_obat_keluar.index') }}">
                <i class="bi bi-circle"></i><span>Pendapatan Obat Klinik</span>
              </a>
            </li>
            <li>
              <a href="{{ route('laporan_klinik_pelayanan.index') }}">
                <i class="bi bi-circle"></i><span>Pendapatan Tindakan Klinik</span>
              </a>
            </li>
            <li hidden>
              <a href="#">
                <i class="bi bi-circle"></i><span>Pendapatan Klinik</span>
              </a>
            </li>
          </ul>
        </li><!-- End Tables Nav -->
      @elseif(Auth::user()->type == '2') <!-- Admin -->
        <li class="nav-heading">Pages</li>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-layout-text-window-reverse"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="{{ route('laporan_stok.index') }}">
                <i class="bi bi-circle"></i><span>Stok</span>
              </a>
            </li>
          </ul>
        </li><!-- End Tables Nav -->
      @endif

      

      <li class="nav-heading" hidden>Pages</li>

      <li class="nav-item" hidden>
        <a class="nav-link collapsed" href="pages-register.html">
          <i class="bi bi-card-list"></i>
          <span>Title</span>
        </a>
      </li><!-- End Register Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->

