<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

// Route::get('/', 'HomeController@index');
Route::get('/akses-ditolak', 'HomeController@akses_ditolak')->name('akses_ditolak');

Route::get('/dashboard-master', 'HomeController@dashboard_master')->name('dashboard_master');
Route::get('/dashboard-kasir', 'HomeController@dashboard_kasir')->name('dashboard_kasir');
Route::get('/data-dashboard', 'HomeController@getData')->name('dashboard_data');

// ------------------------------------- CoffeShop Routes ----------------------------------------- //
Route::prefix('coffeshop')->name('coffeshop.')->middleware(['user.type:1'])->group(function () {
    Route::get('/dashboard', 'Coffeshop\DashboardController@index')->name('dashboard');
    
    
    // Master Data
    Route::prefix('master')->name('master.')->group(function () {
        // Route::resource('products', 'Coffeshop\ProductController');
        // ------------------------------------- Pembelian Routes ----------------------------------------- //
        Route::prefix('pembelian')->name('pembelian.')->group(function () {
            Route::get('/', 'Coffeshop\PembelianController@index')->name('index');
            Route::get('/data', 'Coffeshop\PembelianController@getTransaksiData')->name('data');
            Route::get('/create', 'Coffeshop\PembelianController@create')->name('create');
            Route::get('/create/getSupplier', 'Coffeshop\PembelianController@getSupplier')->name('getSupplier');
            Route::get('/create/getProduk', 'Coffeshop\PembelianController@getProduk')->name('getProduk');
            Route::post('/in', 'Coffeshop\PembelianController@store')->name('store');
            Route::get('/pembelian/detail/{kode}', 'Coffeshop\PembelianController@getTransaksiDataDetail')->name('detail');
            Route::post('/pembelian/terima/{kode}', 'Coffeshop\PembelianController@terimaBarang')->name('accept');
            // Route::post('/up', 'Coffeshop\BranchController@update')->name('update');
            // Route::post('/des', 'Coffeshop\BranchController@destroy')->name('destroy');
        });
        
        // Branch
        Route::prefix('branches')->name('branches.')->group(function () {
            Route::get('/', 'Coffeshop\BranchController@index')->name('index');
            Route::get('/data', 'Coffeshop\BranchController@getBranchesData')->name('data');
            Route::post('/in', 'Coffeshop\BranchController@store')->name('store');
            Route::post('/up', 'Coffeshop\BranchController@update')->name('update');
            Route::post('/des', 'Coffeshop\BranchController@destroy')->name('destroy');
        });

        // Categories
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', 'Coffeshop\CategoryController@index')->name('index');
            Route::get('/data', 'Coffeshop\CategoryController@getCategoriesData')->name('data');
            Route::post('/store', 'Coffeshop\CategoryController@store')->name('store');
            Route::post('/update', 'Coffeshop\CategoryController@update')->name('update');
            Route::post('/delete', 'Coffeshop\CategoryController@delete')->name('delete');
        });

        // Units
        Route::prefix('units')->name('units.')->group(function () {
            Route::get('/', 'Coffeshop\UnitController@index')->name('index');
            Route::get('/data', 'Coffeshop\UnitController@getUnitsData')->name('data');
            Route::post('/store', 'Coffeshop\UnitController@store')->name('store');
            Route::post('/update', 'Coffeshop\UnitController@update')->name('update');
            Route::post('/delete', 'Coffeshop\UnitController@delete')->name('delete');
        });

        // Inggridients
        Route::prefix('ingredients')->name('ingredients.')->group(function () {
            Route::get('/', 'Coffeshop\IngredientController@index')->name('index');
            Route::get('/data', 'Coffeshop\IngredientController@getIngredientsData')->name('data');
            Route::post('/store', 'Coffeshop\IngredientController@store')->name('store');
            Route::post('/update', 'Coffeshop\IngredientController@update')->name('update');
            Route::post('/delete', 'Coffeshop\IngredientController@delete')->name('delete');
            Route::get('/units', 'Coffeshop\IngredientController@getUnits')->name('units');
        });

         // Stocks
        Route::prefix('stocks')->name('stocks.')->group(function () {
            Route::get('/', 'Coffeshop\StockController@index')->name('index');
            Route::get('/data', 'Coffeshop\StockController@getStocksData')->name('data');
            Route::post('/update', 'Coffeshop\StockController@update')->name('update');
            Route::post('/tambahStok', 'Coffeshop\StockController@tambahStok')->name('tambahStok');
            // Route::post('/store', 'Coffeshop\StockController@store')->name('store');
            // Route::post('/delete', 'Coffeshop\StockController@delete')->name('delete');
        });

        Route::prefix('products')->name('products.')->group(function () {
            // Produk
            Route::get('/', 'Coffeshop\ProductController@index')->name('index');
            Route::get('/data', 'Coffeshop\ProductController@getProductsData')->name('data');
            Route::post('/store', 'Coffeshop\ProductController@store')->name('store');
            Route::post('/update', 'Coffeshop\ProductController@update')->name('update');
            Route::post('/delete', 'Coffeshop\ProductController@delete')->name('delete');
            Route::post('/delete-compositions', 'Coffeshop\ProductController@deleteCompositions')->name('delete-compositions');
            // Data pendukung
            Route::get('/categories-data', 'Coffeshop\ProductController@getCategories')->name('categories');
            Route::get('/ingredients', 'Coffeshop\ProductController@getIngredients')->name('ingredients');
            Route::get('/compositions', 'Coffeshop\ProductController@getCompositions')->name('compositions');
        });
        
        Route::prefix('pegawai')->name('pegawai.')->group(function () {
            Route::get('/', 'Coffeshop\PegawaiController@index')->name('index');
            Route::get('/data', 'Coffeshop\PegawaiController@getData')->name('data');
            Route::post('/store', 'Coffeshop\PegawaiController@store')->name('store');
            Route::post('/destroy', 'Coffeshop\PegawaiController@destroy')->name('destroy');
        });
       
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/', 'Coffeshop\UserController@index')->name('index');
            Route::get('/data', 'Coffeshop\UserController@getData')->name('data');
            Route::get('/datapegawai', 'Coffeshop\UserController@getDataPegawai')->name('data_pegawai');
            Route::post('/store', 'Coffeshop\UserController@store')->name('store');
            Route::post('/destroy', 'Coffeshop\UserController@destroy')->name('destroy');
        });
    });
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', 'Coffeshop\ReportController@index')->name('reports');
        Route::get('/sales/purchase', 'Coffeshop\ReportController@getPurchaseReport')->name('purchase');
        Route::get('/sales/sales', 'Coffeshop\ReportController@getSalesReport')->name('sales');
        Route::get('/sales/stock', 'Coffeshop\ReportController@getStockReport')->name('stock');
        Route::get('/branches','Coffeshop\ReportController@getBranches')->name('branches');
        // laporan penjualan
        Route::get('/laporan_penjualan','Coffeshop\LaporanPenjualanController@index')->name('laporan_penjualan');
        Route::get('/laporan_penjualan/penjualan','Coffeshop\LaporanPenjualanController@getDataPenjualan')->name('laporan_data_penjualan');
        // laporan pembelian
        Route::get('/laporan_pembelian','Coffeshop\LaporanPembelianController@index')->name('laporan_pembelian');
        Route::get('/laporan_pembelian/pembelian','Coffeshop\LaporanPembelianController@getDataPembelian')->name('laporan_data_pembelian');
    });

});
// ------------------------------------- Barbershop Routes ----------------------------------------- //
Route::prefix('barbershop')->name('barbershop.')->group(function () {
    Route::get('/dashboard', 'Barbershop\DashboardController@index')->name('dashboard');

    // Master Data
    Route::prefix('master')->name('master.')->group(function () {
        // Branch
        Route::prefix('branches')->name('branches.')->group(function () {
            Route::get('/', 'Barbershop\BranchController@index')->name('index');
            Route::get('/data', 'Barbershop\BranchController@getBranchesData')->name('data');
            Route::post('/in', 'Barbershop\BranchController@store')->name('store');
            Route::post('/up', 'Barbershop\BranchController@update')->name('update');
            Route::post('/des', 'Barbershop\BranchController@destroy')->name('destroy');
        });

        Route::prefix('barbers')->name('barbers.')->group(function () {
            Route::get('/', 'Barbershop\BarberController@index')->name('index');
            Route::get('/data', 'Barbershop\BarberController@getData')->name('data');
            Route::post('/in', 'Barbershop\BarberController@store')->name('store');
            Route::post('/up', 'Barbershop\BarberController@update')->name('update');
            Route::post('/des', 'Barbershop\BarberController@destroy')->name('destroy');
        });

        Route::prefix('services')->name('services.')->group(function () {
            Route::get('/', 'Barbershop\ServiceController@index')->name('index');
            Route::get('/data', 'Barbershop\ServiceController@getServicesData')->name('data');
            Route::post('/in', 'Barbershop\ServiceController@store')->name('store');
            Route::post('/up', 'Barbershop\ServiceController@update')->name('update');
            Route::post('/des', 'Barbershop\ServiceController@destroy')->name('destroy');
        });

        Route::prefix('schedules')->name('schedules.')->group(function () {
            Route::get('/', 'Barbershop\ScheduleController@index')->name('index');
            Route::get('/data', 'Barbershop\ScheduleController@getSchedulesData')->name('data');
            Route::post('/in', 'Barbershop\ScheduleController@store')->name('store');
            Route::post('/up', 'Barbershop\ScheduleController@update')->name('update');
            Route::post('/des', 'Barbershop\ScheduleController@destroy')->name('destroy');
        });

        Route::prefix('bookings')->name('bookings.')->group(function () {
            Route::get('/', 'Barbershop\BookingController@index')->name('index');
            Route::get('/data', 'Barbershop\BookingController@getBookingsData')->name('data');
            Route::post('/in', 'Barbershop\BookingController@store')->name('store');
            Route::post('/up', 'Barbershop\BookingController@updateStatus')->name('update');
        });
        Route::prefix('attendances')->name('attendances.')->group(function () {
            Route::get('/', 'Barbershop\AttendanceController@index')->name('index');
            Route::get('/data', 'Barbershop\AttendanceController@getAttendancesData')->name('data');
            Route::post('/in', 'Barbershop\AttendanceController@store')->name('store');
            Route::post('/up', 'Barbershop\AttendanceController@update')->name('update');
        });
        
    });
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', 'Barbershop\SalesReportController@index')->name('sales');
        Route::get('/sales/data', 'Barbershop\SalesReportController@getData')->name('sales.data');
        Route::get('/sales/export','Barbershop\SalesReportExportController@export')->name('sales.export');
    });
});
// ------------------------------------- Senam Routes ----------------------------------------- //
Route::prefix('senam')->name('senam.')->group(function () {
    Route::get('/dashboard', 'senam\DashboardController@index')->name('dashboard');

    // Master Data
    Route::prefix('master')->name('master.')->group(function () {
        // Branch
        Route::prefix('branches')->name('branches.')->group(function () {
            Route::get('/', 'senam\BranchController@index')->name('index');
            Route::get('/data', 'senam\BranchController@getBranchesData')->name('data');
            Route::post('/in', 'senam\BranchController@store')->name('store');
            Route::post('/up', 'senam\BranchController@update')->name('update');
            Route::post('/des', 'senam\BranchController@destroy')->name('destroy');
        });
        
        Route::prefix('class-schedule')->name('class-schedule.')->group(function () {
            Route::get('/', 'Senam\ClassScheduleController@index')->name('index');
            Route::get('/data', 'Senam\ClassScheduleController@getData')->name('data');
            Route::post('/store', 'Senam\ClassScheduleController@store')->name('store');
            Route::post('/update', 'Senam\ClassScheduleController@update')->name('update');
            Route::post('/destroy', 'Senam\ClassScheduleController@destroy')->name('destroy');
        });

        Route::prefix('class-types')->name('class-types.')->group(function () {
            Route::get('/', 'Senam\ClassTypeController@index')->name('index');
            Route::get('/data', 'Senam\ClassTypeController@getData')->name('data');
            Route::post('/store', 'Senam\ClassTypeController@store')->name('store');
            Route::post('/update', 'Senam\ClassTypeController@update')->name('update');
            Route::post('/destroy', 'Senam\ClassTypeController@destroy')->name('destroy');
        });

        Route::prefix('instructors')->name('instructors.')->group(function () {
            Route::get('/', 'Senam\InstructorController@index')->name('index');
            Route::get('/data', 'Senam\InstructorController@getData')->name('data');
            Route::post('/store', 'Senam\InstructorController@store')->name('store');
            Route::post('/update', 'Senam\InstructorController@update')->name('update');
            Route::post('/destroy', 'Senam\InstructorController@destroy')->name('destroy');
        });

        Route::prefix('members')->name('members.')->group(function () {
            Route::get('/', 'Senam\MemberController@index')->name('index');
            Route::get('/data', 'Senam\MemberController@getData')->name('data');
            Route::post('/store', 'Senam\MemberController@store')->name('store');
            Route::post('/update', 'Senam\MemberController@update')->name('update');
            Route::post('/destroy', 'Senam\MemberController@destroy')->name('destroy');
            Route::post('/add-quota', 'Senam\MemberController@addQuota')->name('add-quota');
            Route::get('/quota-history', 'Senam\MemberController@getQuotaHistory')->name('quota-history');
        });

        Route::prefix('pegawai')->name('pegawai.')->group(function () {
            Route::get('/', 'Senam\PegawaiController@index')->name('index');
            Route::get('/data', 'Senam\PegawaiController@getData')->name('data');
            Route::post('/store', 'Senam\PegawaiController@store')->name('store');
            Route::post('/destroy', 'Senam\PegawaiController@destroy')->name('destroy');
        });
       
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/', 'Senam\UserController@index')->name('index');
            Route::get('/data', 'Senam\UserController@getData')->name('data');
            Route::get('/datapegawai', 'Senam\UserController@getDataPegawai')->name('data_pegawai');
            Route::post('/store', 'Senam\UserController@store')->name('store');
            Route::post('/destroy', 'Senam\UserController@destroy')->name('destroy');
        });

        Route::prefix('equipment')->name('equipment.')->group(function () {
            Route::get('/', 'Senam\EquipmentController@index')->name('index');
            Route::get('/data', 'Senam\EquipmentController@getData')->name('data');
            Route::post('/store', 'Senam\EquipmentController@store')->name('store');
            Route::post('/update', 'Senam\EquipmentController@update')->name('update');
            Route::post('/destroy', 'Senam\EquipmentController@destroy')->name('destroy');
        });    
        
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', 'Senam\ReportController@index')->name('index');

        Route::get('/report/report', 'Senam\ReportController@getSalesReport')->name('sales');
        Route::get('/report/quota', 'Senam\ReportController@getQuotaReport')->name('quota');
        Route::get('/report/instruktur', 'Senam\ReportController@getInstrukturReport')->name('instruktur');
        Route::get('/report/rent', 'Senam\ReportController@getRentReport')->name('rent');
        Route::get('/report/purchase', 'Senam\ReportController@getPurchaseReport')->name('purchase');

        // Data endpoints
        Route::get('/class-participation', 'Senam\ReportController@getClassParticipation')->name('class-participation');
        Route::get('/quota-usage', 'Senam\ReportController@getQuotaUsage')->name('quota-usage');
        Route::get('/instructor-sessions', 'Senam\ReportController@getInstructorSessions')->name('instructor-sessions');
        
        // Export endpoints
        Route::get('/export-participation', 'Senam\ReportController@exportParticipation')->name('export-participation');
        Route::get('/export-quota', 'Senam\ReportController@exportQuota')->name('export-quota');
        Route::get('/export-instructor', 'Senam\ReportController@exportInstructor')->name('export-instructor');
    });

});
// ------------------------------------- POS Routes ----------------------------------------- //
Route::prefix('pos')->group(function () {
    Route::get('/', 'PosController@index')->name('pos');
    Route::get('/products', 'PosController@getProducts')->name('pos.products');
    Route::get('/services', 'PosController@getServices')->name('pos.services');
    Route::post('/process', 'PosController@processTransaction')->name('pos.process');
    Route::get('/exercise-classes', 'PosController@getExerciseClasses')->name('pos.exercise-classes');
    Route::post('/check-member', 'PosController@checkMember')->name('pos.check-member');
    Route::post('/search-members', 'PosController@searchMembers')->name('pos.search-members');
    Route::post('/register-class', 'PosController@registerClass')->name('pos.register-class');
    Route::post('/topup-quota', 'PosController@topupQuota')->name('pos.topup-quota');
    Route::get('/categories', 'PosController@getCategories')->name('pos.categories');
    // Route::post('/search-members','PosController@searchMembers')->name('pos.search-members');
});


// ------------------------------------- Apotek Routes ----------------------------------------- //
Route::get('/dashboard-apotek', 'Apotek\HomeApotekController@index')->name('home');
Route::get('/dashboard-apotek/getDataJmlPenjualan', 'Apotek\HomeApotekController@getDataJmlPenjualan')->name('home/getDataJmlPenjualan');
Route::get('/dashboard-apotek/getDataTtlPenjualan', 'Apotek\HomeApotekController@getDataTtlPenjualan')->name('home/getDataTtlPenjualan');
Route::get('/dashboard-apotek/getDataJmlReturPenjualan', 'Apotek\HomeApotekController@getDataJmlReturPenjualan')->name('home/getDataJmlReturPenjualan');
Route::get('/dashboard-apotek/getDataTtlReturPenjualan', 'Apotek\HomeApotekController@getDataTtlReturPenjualan')->name('home/getDataTtlReturPenjualan');
Route::get('/dashboard-apotek/getDataPendapatanUser', 'Apotek\HomeApotekController@getDataPendapatanUser')->name('home/getDataPendapatanUser');
Route::get('/dashboard-apotek/getDataPiutangPanel', 'Apotek\HomeApotekController@getDataPiutangPanel')->name('home/getDataPiutangPanel');
Route::get('/dashboard-apotek/getDataPembayaranPanel', 'Apotek\HomeApotekController@getDataPembayaranPanel')->name('home/getDataPembayaranPanel');
Route::get('/dashboard-apotek/getDataPembayaranPanel_Detail', 'Apotek\HomeApotekController@getDataPembayaranPanel_Detail')->name('home/getDataPembayaranPanel_Detail');
Route::get('/dashboard-apotek/getDataJmlPembelian', 'Apotek\HomeApotekController@getDataJmlPembelian')->name('home/getDataJmlPembelian');
Route::get('/dashboard-apotek/getDataTtlPembelian', 'Apotek\HomeApotekController@getDataTtlPembelian')->name('home/getDataTtlPembelian');
Route::get('/dashboard-apotek/getDataJmlKunjungan', 'Apotek\HomeApotekController@getDataJmlKunjungan')->name('home/getDataJmlKunjungan');
Route::get('/dashboard-apotek/getDataJmlKunjunganSelesai', 'Apotek\HomeApotekController@getDataJmlKunjunganSelesai')->name('home/getDataJmlKunjunganSelesai');
Route::get('/dashboard-apotek/getDataPendapatanKunjungan', 'Apotek\HomeApotekController@getDataPendapatanKunjungan')->name('home/getDataPendapatanKunjungan');
Route::get('/dashboard-apotek/getDataTotalPendapatan', 'Apotek\HomeApotekController@getDataTotalPendapatan')->name('home/getDataTotalPendapatan');
Route::get('/dashboard-apotek/getDataTtlPenjualanPanel', 'Apotek\HomeApotekController@getDataTtlPenjualanPanel')->name('home/getDataTtlPenjualanPanel');
Route::get('/dashboard-apotek/getDataTerlaris', 'Apotek\HomeApotekController@getDataTerlaris')->name('home/getDataTerlaris');
Route::get('/dashboard-apotek/getDataHabis', 'Apotek\HomeApotekController@getDataHabis')->name('home/getDataHabis');
Route::post('/dashboard-apotek/store', 'Apotek\HomeApotekController@store')->name('home/store');
Route::post('/dashboard-apotek/retur', 'Apotek\HomeApotekController@retur')->name('home/retur');

/** ######## MASTER DATA ######## */
Route::resource('apotek', 'Apotek\Master_Data\ApotekController')->except(['show']);
Route::get('apotek/getDataApotek', 'Apotek\Master_Data\ApotekController@getDataApotek')->name('apotek/getDataApotek.getDataApotek');
Route::get('apotek/getDataApotekDetail', 'Apotek\Master_Data\ApotekController@getDataApotekDetail')->name('apotek/getDataApotekDetail.getDataApotekDetail');
Route::post('apotek/store', 'Apotek\Master_Data\ApotekController@store')->name('apotek/store.store');
Route::post('apotek/update', 'Apotek\Master_Data\ApotekController@update')->name('apotek/update.update');
Route::get('apotek/view', 'Apotek\Master_Data\ApotekController@view')->name('apotek/view.view');

Route::resource('pengguna', 'Apotek\Master_Data\PenggunaController')->except(['show']);
Route::get('pengguna/getDataPengguna', 'Apotek\Master_Data\PenggunaController@getDataPengguna')->name('pengguna/getDataPengguna.getDataPengguna');
Route::get('pengguna/getDataPenggunaModal', 'Apotek\Master_Data\PenggunaController@getDataPenggunaModal')->name('pengguna/getDataPenggunaModal.getDataPenggunaModal');
Route::post('pengguna/store', 'Apotek\Master_Data\PenggunaController@store')->name('pengguna/store.store');
Route::get('pengguna/getDataPenggunaDetail', 'Apotek\Master_Data\PenggunaController@getDataPenggunaDetail')->name('pengguna/getDataPenggunaDetail.getDataPenggunaDetail');
Route::post('pengguna/update', 'Apotek\Master_Data\PenggunaController@update')->name('pengguna/update.update');
Route::get('pengguna/view', 'Apotek\Master_Data\PenggunaController@view')->name('pengguna/view.view');

Route::resource('pegawai', 'Apotek\Master_Data\PegawaiController')->except(['show']);
Route::get('pegawai/getDataPegawai', 'Apotek\Master_Data\PegawaiController@getDataPegawai')->name('pegawai/getDataPegawai.getDataPegawai');
Route::post('pegawai/store', 'Apotek\Master_Data\PegawaiController@store')->name('pegawai/store.store');
Route::get('pegawai/getDataPegawaiDetail', 'Apotek\Master_Data\PegawaiController@getDataPegawaiDetail')->name('pegawai/getDataPegawaiDetail.getDataPegawaiDetail');
Route::post('pegawai/update', 'Apotek\Master_Data\PegawaiController@update')->name('pegawai/update.update');
Route::get('pegawai/view', 'Apotek\Master_Data\PegawaiController@view')->name('pegawai/view.view');

Route::resource('supplier', 'Apotek\Master_Data\SupplierController')->except(['show']);
Route::get('supplier/getDataSupplier', 'Apotek\Master_Data\SupplierController@getDataSupplier')->name('supplier/getDataSupplier.getDataSupplier');
Route::post('supplier/store', 'Apotek\Master_Data\SupplierController@store')->name('supplier/store.store');
Route::get('supplier/getDataSupplierDetail', 'Apotek\Master_Data\SupplierController@getDataSupplierDetail')->name('supplier/getDataSupplierDetail.getDataSupplierDetail');
Route::post('supplier/update', 'Apotek\Master_Data\supplierController@update')->name('supplier/update.update');
Route::get('supplier/view', 'Apotek\Master_Data\supplierController@view')->name('supplier/view.view');

Route::resource('produk', 'Apotek\Master_Data\ObatController')->except(['show']);
Route::get('produk/getDataProduk', 'Apotek\Master_Data\ObatController@getDataProduk')->name('produk/getDataProduk.getDataProduk');
Route::post('produk/store', 'Apotek\Master_Data\ObatController@store')->name('produk/store.store');
Route::get('produk/getDetailData', 'Apotek\Master_Data\ObatController@getDetailData')->name('produk/getDetailData.getDetailData');
Route::post('produk/update', 'Apotek\Master_Data\ObatController@update')->name('produk/update.update');
Route::get('produk/view', 'Apotek\Master_Data\ObatController@view')->name('produk/view.view');
//Route::get('produk/pdf', 'Apotek\Master_Data\ObatController@pdf')->name('produk/pdf.pdf');

Route::resource('jenis', 'Apotek\Master_Data\JenisController')->except(['show']);
Route::get('jenis/getDataJenis', 'Apotek\Master_Data\JenisController@getDataJenis')->name('jenis/getDataJenis.getDataJenis');
Route::post('jenis/store', 'Apotek\Master_Data\JenisController@store')->name('jenis/store.store');
Route::get('jenis/getDataJenisDetail', 'Apotek\Master_Data\JenisController@getDataJenisDetail')->name('jenis/getDataJenisDetail.getDataJenisDetail');
Route::post('jenis/update', 'Apotek\Master_Data\JenisController@update')->name('jenis/update.update');
Route::get('jenis/view', 'Apotek\Master_Data\JenisController@view')->name('jenis/view.view');

Route::resource('poli', 'Apotek\Master_Data\PoliController')->except(['show']);
Route::get('poli/getDataPoli', 'Apotek\Master_Data\PoliController@getDataPoli')->name('poli/getDataPoli.getDataPoli');
Route::post('poli/store', 'Apotek\Master_Data\PoliController@store')->name('poli/store.store');
Route::get('poli/getDataPoliDetail', 'Apotek\Master_Data\PoliController@getDataPoliDetail')->name('poli/getDataPoliDetail.getDataPoliDetail');
Route::post('poli/update', 'Apotek\Master_Data\PoliController@update')->name('poli/update.update');
Route::get('poli/view', 'Apotek\Master_Data\PoliController@view')->name('poli/view.view');

Route::resource('tuslah', 'Apotek\Master_Data\TuslahController')->except(['show']);
Route::get('tuslah/getDataTuslah', 'Apotek\Master_Data\TuslahController@getDataTuslah')->name('tuslah/getDataTuslah.getDataTuslah');
Route::post('tuslah/store', 'Apotek\Master_Data\TuslahController@store')->name('tuslah/store.store');
Route::get('tuslah/getDataTuslahDetail', 'Apotek\Master_Data\TuslahController@getDataTuslahDetail')->name('tuslah/getDataTuslahDetail.getDataTuslahDetail');
Route::post('tuslah/update', 'Apotek\Master_Data\TuslahController@update')->name('tuslah/update.update');
Route::get('tuslah/view', 'Apotek\Master_Data\TuslahController@view')->name('tuslah/view.view');

Route::resource('jasa', 'Apotek\Master_Data\JasaController')->except(['show']);
Route::get('jasa/getDataJasa', 'Apotek\Master_Data\JasaController@getDataJasa')->name('jasa/getDataJasa.getDataJasa');
Route::post('jasa/store', 'Apotek\Master_Data\JasaController@store')->name('jasa/store.store');
Route::get('jasa/getDataJasaDetail', 'Apotek\Master_Data\JasaController@getDataJasaDetail')->name('jasa/getDataJasaDetail.getDataJasaDetail');
Route::post('jasa/update', 'Apotek\Master_Data\JasaController@update')->name('jasa/update.update');
Route::get('jasa/view', 'Apotek\Master_Data\JasaController@view')->name('jasa/view.view');

Route::resource('kategori_prod', 'Apotek\Master_Data\KategoriProdController')->except(['show']);

Route::resource('kemasan', 'Apotek\Master_Data\KemasanController')->except(['show']);
Route::get('kemasan/getDataUnit', 'Apotek\Master_Data\KemasanController@getDataUnit')->name('getDataUnit');
Route::post('kemasan/store', 'Apotek\Master_Data\KemasanController@store')->name('kemasan/store');
Route::get('kemasan/getDataKemasanDetail', 'Apotek\Master_Data\KemasanController@getDataKemasanDetail')->name('kemasan/getDataKemasanDetail.getDataKemasanDetail');
Route::post('kemasan/update', 'Apotek\Master_Data\KemasanController@update')->name('kemasan/update.update');
Route::get('kemasan/view', 'Apotek\Master_Data\KemasanController@view')->name('kemasan/view.view');

Route::resource('barcode', 'Apotek\Master_Data\BuatBarcodeController')->except(['show']);
Route::get('barcode/{id}', 'Apotek\Master_Data\BuatBarcodeController@generate')->name('generate');
Route::get('barcode/pdf/{id}', 'Apotek\Master_Data\BuatBarcodeController@pdf')->name('qr_code.pdf');

Route::resource('barcode_scan', 'Apotek\Master_Data\BarcodeController')->except(['show']);
Route::get('/barcode_scan/get_product_details/{barcode}', 'Apotek\Master_Data\BarcodeController@getProdukDetail');
Route::post('barcode_scan/update', 'Apotek\Master_Data\BarcodeController@update')->name('barcode_scan/update.update');

/** ######## Barber/Senam/Coffee ##########*/
Route::resource('barberlayanan', 'Apotek\Master_Data\BarberLayananController')->except(['show']);
Route::get('barber/layanan/getDataJasa', 'Apotek\Master_Data\BarberLayananController@getDataJasa')->name('barber/layanan/getDataJasa.getDataJasa');
Route::post('barber/layanan/store', 'Apotek\Master_Data\BarberLayananController@store')->name('barber/layanan/store.store');
Route::get('barber/layanan/getDataJasaDetail', 'Apotek\Master_Data\BarberLayananController@getDataJasaDetail')->name('barber/layanan/getDataJasaDetail.getDataJasaDetail');
Route::post('barber/layanan/update', 'Apotek\Master_Data\BarberLayananController@update')->name('barber/layanan/update.update');
// Route::get('jasa/view', 'Apotek\Master_Data\JasaController@view')->name('jasa/view.view');

/** ######## END MASTER DATA ######## */

/** ######## TRANSAKSI ######## */
Route::get('pelayanan transaksi', 'Apotek\Transaksi\JasaController@create')->name('transaksi');

Route::resource('penjualan', 'Apotek\Transaksi\PenjualanController')->except(['show']);
Route::get('/transaksi_penjualan/cari', 'Apotek\Transaksi\PenjualanController@cari')->name('transaksi_penjualan/cari.cari');
Route::get('/transaksi_penjualan/getViewPenjualan', 'Apotek\Transaksi\PenjualanController@getViewPenjualan')->name('transaksi_penjualan/getViewPenjualan');
Route::get('/transaksi_penjualan/getViewPenjualanFooter', 'Apotek\Transaksi\PenjualanController@getViewPenjualanFooter')->name('transaksi_penjualan/getViewPenjualanFooter');
Route::get('/transaksi_penjualan/getDataPenjualan', 'Apotek\Transaksi\PenjualanController@getDataPenjualan')->name('transaksi_penjualan/getDataPenjualan');
Route::get('/transaksi_penjualan', 'Apotek\Transaksi\PenjualanController@create')->name('transaksi_penjualan.create');
Route::get('/penjualan/getProdukModal', 'Apotek\Transaksi\PenjualanController@getProdukModal')->name('getProdukModal');
Route::get('/penjualan/getProduk', 'Apotek\Transaksi\PenjualanController@getProduk')->name('getProduk');
Route::get('/penjualan/getProdukPilih/{kode}/{unit_varian}', 'Apotek\Transaksi\PenjualanController@getProdukPilih');
Route::post('/penjualan/store', 'Apotek\Transaksi\PenjualanController@store')->name('penjualan/store');
Route::get('/penjualan/pdf', 'Apotek\Transaksi\PenjualanController@pdf')->name('penjualan/pdf');

Route::resource('pembelian', 'Apotek\Transaksi\PembelianController')->except(['show']);
Route::get('/transaksi_pembelian/getViewPembelian', 'Apotek\Transaksi\PembelianController@getViewPembelian')->name('transaksi_pembelian/getViewPembelian');
Route::get('/transaksi_pembelian/getViewPenerimanPembelian', 'Apotek\Transaksi\PembelianController@getViewPenerimanPembelian')->name('transaksi_pembelian/getViewPenerimanPembelian');
Route::get('/transaksi_pembelian/getDataPembelian', 'Apotek\Transaksi\PembelianController@getDataPembelian')->name('transaksi_pembelian/getDataPembelian');
Route::get('/transaksi_pembelian/cari', 'Apotek\Transaksi\PembelianController@cari')->name('transaksi_pembelian/cari.cari');
Route::get('/transaksi_pembelian', 'Apotek\Transaksi\PembelianController@create')->name('transaksi_pembelian.create');
Route::get('/pembelian/getProdukModal', 'Apotek\Transaksi\PembelianController@getProdukModal')->name('pembelian/getProdukModal');
Route::get('/pembelian/getSupplier', 'Apotek\Transaksi\PembelianController@getSupplier')->name('pembelian/getSupplier');
Route::get('/pembelian/getProduk', 'Apotek\Transaksi\PembelianController@getProduk')->name('pembelian/getProduk');
Route::get('/pembelian/getTambahCart/{kode_produk}', 'Apotek\Transaksi\PembelianController@getTambahCart')->name('pembelian/getTambahCart');
Route::get('/pembelian/getProdukPilih/{kode}/{unit_varian}', 'Apotek\Transaksi\PembelianController@getProdukPilih');
Route::post('/pembelian/store', 'Apotek\Transaksi\PembelianController@store')->name('pembelian/store');
Route::get('/transaksi_pembelian/pdf', 'Apotek\Transaksi\PembelianController@pdf')->name('transaksi_pembelian/pdf.pdf');
Route::post('/pembelian/terima', 'Apotek\Transaksi\PembelianController@terima')->name('pembelian/terima');
/** ######## END TRANSAKSI ######## */

/** ######## PENDAPATAN APOTEK ######## */
Route::resource('pendapatan', 'Apotek\Pendapatan\PendapatanController')->except(['show']);
Route::get('/pendapatan/getDataPendapatan', 'Apotek\Pendapatan\PendapatanController@getDataPendapatan')->name('pendapatan/getDataPendapatan');
Route::get('/pendapatan/cari', 'Apotek\Pendapatan\PendapatanController@cari')->name('pendapatan/cari.cari');
/** ######## END PENDAPATAN APOTEK ######## */

/** ######## BAYAR PEMBELIAN ######## */
Route::resource('hutang_pembelian', 'Apotek\Transaksi\PembelianHutangController')->except(['show']);
Route::get('/hutang_pembelian/getDataHutangPembelian', 'Apotek\Transaksi\PembelianHutangController@getDataHutangPembelian')->name('hutang_pembelian/getDataHutangPembelian');
Route::get('/hutang_pembelian/cari', 'Apotek\Transaksi\PembelianHutangController@cari')->name('hutang_pembelian/cari.cari');
Route::get('/hutang_pembelian/getViewPembelian', 'Apotek\Transaksi\PembelianHutangController@getViewPembelian')->name('hutang_pembelian/getViewPembelian');
Route::post('/hutang_pembelian/store', 'Apotek\Transaksi\PembelianHutangController@store')->name('hutang_pembelian/store');
/** ######## END BAYAR PEMBELIAN ######## */

/** ######## RETURN ######## */
Route::resource('retur_penjualan', 'Apotek\Retur\RetPenjualanController')->except(['show']);
Route::get('/transaksi_retur_penjualan/getDataReturPenjualan', 'Apotek\Retur\RetPenjualanController@getDataReturPenjualan')->name('transaksi_retur_penjualan/getDataReturPenjualan');
Route::get('/transaksi_retur_penjualan/cari', 'Apotek\Retur\RetPenjualanController@cari')->name('transaksi_retur_penjualan/cari.cari');
Route::get('/transaksi_retur_penjualan', 'Apotek\Retur\RetPenjualanController@create')->name('transaksi_retur_penjualan.create');
Route::get('/transaksi_retur_penjualan/getPenjualanModal', 'Apotek\Retur\RetPenjualanController@getPenjualanModal')->name('transaksi_retur_penjualan/getPenjualanModal.getPenjualanModal');
Route::get('/transaksi_retur_penjualan/getPenjualandetail', 'Apotek\Retur\RetPenjualanController@getPenjualandetail')->name('transaksi_retur_penjualan/getPenjualandetail.getPenjualandetail');
Route::post('/transaksi_retur_penjualan/store', 'Apotek\Retur\RetPenjualanController@store')->name('transaksi_retur_penjualan/store');

Route::resource('retur_pembelian', 'Apotek\Retur\RetPembelianController')->except(['show']);
Route::get('/transaksi_retur_pembelian/getDataReturPembelian', 'Apotek\Retur\RetPembelianController@getDataReturPembelian')->name('transaksi_retur_pembelian/getDataReturPembelian');
Route::get('/transaksi_retur_pembelian/cari', 'Apotek\Retur\RetPembelianController@cari')->name('transaksi_retur_pembelian/cari.cari');
Route::get('/transaksi_retur_pembelian', 'Apotek\Retur\RetPembelianController@create')->name('transaksi_retur_pembelian.create');
Route::get('/transaksi_retur_pembelian/getPembelianModal', 'Apotek\Retur\RetPembelianController@getPembelianModal')->name('transaksi_retur_pembelian/getPembelianModal.getPembelianModal');
Route::get('/transaksi_retur_pembelian/getPembelianModalBatch', 'Apotek\Retur\RetPembelianController@getPembelianModalBatch')->name('transaksi_retur_pembelian/getPembelianModal.getPembelianModalBatch');
Route::get('/transaksi_retur_pembelian/getPembeliandetail', 'Apotek\Retur\RetPembelianController@getPembeliandetail')->name('transaksi_retur_pembelian/getPembeliandetail.getPembeliandetail');
Route::post('/transaksi_retur_pembelian/store', 'Apotek\Retur\RetPembelianController@store')->name('transaksi_retur_pembelian/store');
/** ######## End RETUR ######## */

/** ######## MUTASI ######## */
Route::resource('mutasi', 'Apotek\Mutasi\MutasiController')->except(['show']);
Route::get('/mutasi_create', 'Apotek\Mutasi\MutasiController@create')->name('mutasi_create.create');
Route::get('/mutasi/getProduk', 'Apotek\Mutasi\MutasiController@getProduk')->name('mutasi/getProduk');
Route::post('/mutasi/store', 'Apotek\Mutasi\MutasiController@store')->name('mutasi/store');
Route::get('/mutasi/getDataMutasi', 'Apotek\Mutasi\MutasiController@getDataMutasi')->name('mutasi/getDataMutasi');
Route::get('/mutasi/cari', 'Apotek\Mutasi\MutasiController@cari')->name('mutasi/cari.cari');
Route::get('/mutasi/pdf', 'Apotek\Mutasi\MutasiController@pdf')->name('mutasi/pdf.pdf');
/** ######## End MUTASI ######## */

/** ######## KARTU STOK ######## */
Route::resource('kartu_stok', 'Apotek\Klinik\KartuStokController')->except(['show']);
Route::get('kartu_stok/getDatakartuStok', 'Apotek\Klinik\KartuStokController@getDatakartuStok')->name('kartu_stok/getDatakartuStok.getDatakartuStok');
Route::get('kartu_stok/cari', 'Apotek\Klinik\KartuStokController@cari')->name('kartu_stok/cari.cari');
Route::get('kartu_stok/view', 'Apotek\Klinik\KartuStokController@view')->name('kartu_stok/view');
/** ######## END KARTU STOK ######## */

/** ######## STOK OPNAME ######## */
Route::resource('stok_opname', 'Apotek\Klinik\StokOpnameController')->except(['show']);
Route::post('stok_opname_store', 'Apotek\Klinik\StokOpnameController@store')->name('stok_opname_store.store');
/** ######## END REKAM MEDIS ######## */

/** ######## PENDAFTARAN ######## */
Route::resource('pendaftaran', 'Apotek\Klinik\PendaftaranController')->except(['show']);
Route::get('/pendaftaran/getDataPendaftaran', 'Apotek\Klinik\PendaftaranController@getDataPendaftaran')->name('getDataPendaftaran');
Route::get('/pendaftaran/cari', 'Apotek\Klinik\PendaftaranController@cari')->name('pendaftaran/cari.cari');
Route::get('city', 'Apotek\Klinik\PendaftaranController@getKota');
Route::get('district', 'Apotek\Klinik\PendaftaranController@getKecamatan');
Route::post('/pendaftaran/store', 'Apotek\Klinik\PendaftaranController@store')->name('pendaftaran/store');
/** ######## END PENDAFTARAN ######## */

/** ######## KUNJUNGAN ANTRIAN BEROBAT ######## */
Route::resource('pelayanan', 'Apotek\Transaksi\JasaController')->except(['show']);
Route::get('/pelayanan/getDataAntrian', 'Apotek\Transaksi\JasaController@getDataAntrian')->name('getDataAntrian');
Route::get('/pelayanan/cari', 'Apotek\Transaksi\JasaController@cari')->name('pelayanan/cari.cari');
Route::get('/pelayanan/actionGetPasien', 'Apotek\Transaksi\JasaController@actionGetPasien')->name('actionGetPasien');
Route::post('/pelayanan/store', 'Apotek\Transaksi\JasaController@store')->name('store');
/** ######## END ANTRIAN BEROBAT ######## */

/** ######## PEMERIKSAAN ######## */
Route::resource('pemeriksaan', 'Apotek\Klinik\PemeriksaanController')->except(['show']);
Route::get('/pemeriksaan/getDataAntrianPeriksa', 'Apotek\Klinik\PemeriksaanController@getDataAntrianPeriksa')->name('getDataAntrianPeriksa');
Route::get('/pemeriksaan/cari', 'Apotek\Klinik\PemeriksaanController@cari')->name('pemeriksaan/cari.cari');
Route::get('/pemeriksaan/getDataAntrianPeriksaDetail', 'Apotek\Klinik\PemeriksaanController@getDataAntrianPeriksaDetail')->name('getDataAntrianPeriksaDetail');
Route::get('/pemeriksaan/getProdukModal', 'Apotek\Klinik\PemeriksaanController@getProdukModal')->name('pemeriksaan/getProdukModal.getProdukModal');
Route::get('/pemeriksaan/getTindakanModal', 'Apotek\Klinik\PemeriksaanController@getTindakanModal')->name('pemeriksaan/getTindakanModal.getTindakanModal');
Route::get('/pemeriksaan/getSubKatDiagnosaModal', 'Apotek\Klinik\PemeriksaanController@getSubKatDiagnosaModal')->name('pemeriksaan/getSubKatDiagnosaModal.getSubKatDiagnosaModal');
Route::post('/pemeriksaan/store', 'Apotek\Klinik\PemeriksaanController@store')->name('pemeriksaan/store');
/** ######## END PEMERIKSAAN ######## */

/** ######## REKAM MEDIS ######## */
Route::resource('rekam_medis', 'Apotek\Klinik\RekamMedisController')->except(['show']);
Route::get('rekam_medis/getDataRm', 'Apotek\Klinik\RekamMedisController@getDataRm')->name('rekam_medis/getDataRm.getDataRm');
Route::get('rekam_medis/getViewDataRekamMedis', 'Apotek\Klinik\RekamMedisController@getViewDataRekamMedis')->name('rekam_medis/getViewDataRekamMedis.getViewDataRekamMedis');
Route::get('rekam_medis/getViewDataRekamMedisDiagnosa', 'Apotek\Klinik\RekamMedisController@getViewDataRekamMedisDiagnosa')->name('rekam_medis/getViewDataRekamMedisDiagnosa.getViewDataRekamMedisDiagnosa');
Route::get('rekam_medis/getViewDataRekamMedisResep', 'Apotek\Klinik\RekamMedisController@getViewDataRekamMedisResep')->name('rekam_medis/getViewDataRekamMedisResep.getViewDataRekamMedisResep');
/** ######## END REKAM MEDIS ######## */

/** ######## KASIR ######## */
Route::resource('kasir', 'Apotek\Kasir\KasirController')->except(['show']);
Route::get('kasir/getDataKasir', 'Apotek\Kasir\KasirController@getDataKasir')->name('kasir/getDataKasir.getDataKasir');
Route::get('kasir/cari', 'Apotek\Kasir\KasirController@cari')->name('kasir/cari.cari');
Route::get('kasir/getDataTindakan', 'Apotek\Kasir\KasirController@getDataTindakan')->name('kasir/getDataTindakan.getDataTindakan');
Route::get('kasir/getDataObat', 'Apotek\Kasir\KasirController@getDataObat')->name('kasir/getDataObat.getDataObat');
Route::post('kasir/store', 'Apotek\Kasir\KasirController@store')->name('kasir/store');
/** ######## END KASIR ######## */

/** ######## FARMASI ######## */
Route::resource('farmasi', 'Apotek\Farmasi\FarmasiController')->except(['show']);
Route::get('farmasi/getDataResepObat', 'Apotek\Farmasi\FarmasiController@getDataResepObat')->name('farmasi/getDataResepObat.getDataResepObat');
Route::get('farmasi/cari', 'Apotek\Farmasi\FarmasiController@cari')->name('farmasi/cari.cari');
Route::get('farmasi/getDataResepDetail', 'Apotek\Farmasi\FarmasiController@getDataResepDetail')->name('farmasi/getDataResepDetail.getDataResepDetail');
Route::post('farmasi/store', 'Apotek\Farmasi\FarmasiController@store')->name('farmasi/store.store');
/** ######## END FARMASI ######## */

/** ######## Laporan ######## */
Route::resource('laporan_penjualan', 'Apotek\Laporan\LapPenjualanController')->except(['show']);
Route::get('laporan_penjualan/getDataPenjualan', 'Apotek\Laporan\LapPenjualanController@getDataPenjualan')->name('laporan_penjualan/getDataPenjualan.getDataPenjualan');
Route::get('laporan_penjualan/cari', 'Apotek\Laporan\LapPenjualanController@cari')->name('laporan_penjualan/cari.cari');
Route::get('laporan_penjualan/view', 'Apotek\Laporan\LapPenjualanController@view')->name('laporan_penjualan/view.view');

Route::resource('laporan_penjualan_Pertransaksi', 'Apotek\Laporan\LapPenjualanPerTransaksiController')->except(['show']);
Route::get('laporan_penjualan_Pertransaksi/getDataPenjualanPertransaksi', 'Apotek\Laporan\LapPenjualanPerTransaksiController@getDataPenjualanPertransaksi')->name('laporan_penjualan_Pertransaksi/getDataPenjualanPertransaksi.getDataPenjualanPertransaksi');
Route::get('laporan_penjualan_Pertransaksi/cari', 'Apotek\Laporan\LapPenjualanPerTransaksiController@cari')->name('laporan_penjualan_Pertransaksi/cari.cari');
Route::get('laporan_penjualan_Pertransaksi/view', 'Apotek\Laporan\LapPenjualanPerTransaksiController@view')->name('laporan_penjualan_Pertransaksi/view.view');

Route::resource('laporan_penjualan_panel', 'Apotek\Laporan\LapPenjualanPanelController')->except(['show']);
Route::get('laporan_penjualan_panel/getDataPenjualan', 'Apotek\Laporan\LapPenjualanPanelController@getDataPenjualan')->name('laporan_penjualan_panel/getDataPenjualan.getDataPenjualan');
Route::get('laporan_penjualan_panel/view', 'Apotek\Laporan\LapPenjualanPanelController@view')->name('laporan_penjualan_panel/view.view');

Route::resource('laporan_panel_pertransaksi', 'Apotek\Laporan\LapPenjualanPanelPerTransaksiController')->except(['show']);
Route::get('laporan_panel_pertransaksi/getDataPenjualanPertransaksi', 'Apotek\Laporan\LapPenjualanPanelPerTransaksiController@getDataPenjualanPertransaksi')->name('laporan_panel_pertransaksi/getDataPenjualanPertransaksi');
Route::get('laporan_panel_pertransaksi/cari', 'Apotek\Laporan\LapPenjualanPanelPerTransaksiController@cari')->name('laporan_panel_pertransaksi/cari.cari');
Route::get('laporan_panel_pertransaksi/view', 'Apotek\Laporan\LapPenjualanPanelPerTransaksiController@view')->name('laporan_panel_pertransaksi/view.view');

Route::resource('laporan_panel_piutang', 'Apotek\Laporan\LapPenjualanPanelPiutangController')->except(['show']);
Route::get('laporan_panel_piutang/getDataPenjualanPertransaksi', 'Apotek\Laporan\LapPenjualanPanelPiutangController@getDataPenjualanPertransaksi')->name('laporan_panel_piutang/getDataPenjualanPertransaksi');
Route::get('laporan_panel_piutang/cari', 'Apotek\Laporan\LapPenjualanPanelPiutangController@cari')->name('laporan_panel_piutang/cari.cari');
Route::get('laporan_panel_piutang/view', 'Apotek\Laporan\LapPenjualanPanelPiutangController@view')->name('laporan_panel_piutang/view.view');

Route::resource('laporan_pembelian', 'Apotek\Laporan\LapPembelianController')->except(['show']);
Route::get('laporan_pembelian/getDataPembelian', 'Apotek\Laporan\LapPembelianController@getDataPembelian')->name('laporan_pembelian/getDataPembelian.getDataPembelian');
Route::get('laporan_pembelian/cari', 'Apotek\Laporan\LapPembelianController@cari')->name('laporan_pembelian/cari.cari');
Route::get('laporan_pembelian/view', 'Apotek\Laporan\LapPembelianController@view')->name('laporan_pembelian/view.view');

Route::resource('laporan_penerimaan', 'Apotek\Laporan\LapPenerimaanController')->except(['show']);
Route::get('laporan_penerimaan/getDataPenerimaan', 'Apotek\Laporan\LapPenerimaanController@getDataPenerimaan')->name('laporan_penerimaan/getDataPenerimaan.getDataPenerimaan');
Route::get('laporan_penerimaan/cari', 'Apotek\Laporan\LapPenerimaanController@cari')->name('laporan_penerimaan/cari.cari');
Route::get('laporan_penerimaan/view', 'Apotek\Laporan\LapPenerimaanController@view')->name('laporan_penerimaan/view.view');

Route::resource('laporan_narkotika_psikotropika', 'Apotek\Laporan\LapNarkotikaPsikotropikaController')->except(['show']);
Route::get('laporan_narkotika_psikotropika/getData', 'Apotek\Laporan\LapNarkotikaPsikotropikaController@getData')->name('laporan_narkotika_psikotropika/getData.getData');
Route::get('laporan_narkotika_psikotropika/cari', 'Apotek\Laporan\LapNarkotikaPsikotropikaController@cari')->name('laporan_narkotika_psikotropika/cari.cari');
Route::get('laporan_narkotika_psikotropika/view', 'Apotek\Laporan\LapNarkotikaPsikotropikaController@view')->name('laporan_narkotika_psikotropika/view.view');

Route::resource('laporan_stok', 'Apotek\Laporan\StokController')->except(['show']);
Route::get('laporan_stok/getDataStok', 'Apotek\Laporan\StokController@getDataStok')->name('laporan_stok/getDataStok.getDataStok');
Route::get('laporan_stok/view', 'Apotek\Laporan\StokController@view')->name('laporan_stok/view');

Route::resource('laporan_klinik_obat_keluar', 'Apotek\Laporan\LapKlinikObatController')->except(['show']);
Route::get('laporan_klinik_obat_keluar/getDataKlinikObat', 'Apotek\Laporan\LapKlinikObatController@getDataKlinikObat')->name('laporan_klinik_obat_keluar/getDataKlinikObat.getDataKlinikObat');
Route::get('laporan_klinik_obat_keluar/view', 'Apotek\Laporan\LapKlinikObatController@view')->name('laporan_klinik_obat_keluar/view.view');

Route::resource('laporan_klinik_pelayanan', 'Apotek\Laporan\LapKlinikPelayananController')->except(['show']);
Route::get('laporan_klinik_pelayanan/getDataKlinikPelayanan', 'Apotek\Laporan\LapKlinikPelayananController@getDataKlinikPelayanan')->name('laporan_klinik_pelayanan/getDataKlinikPelayanan.getDataKlinikPelayanan');
Route::get('laporan_klinik_pelayanan/view', 'Apotek\Laporan\LapKlinikPelayananController@view')->name('laporan_klinik_pelayanan/view.view');

Route::resource('laporan_retur_k', 'Apotek\Laporan\LapReturKadaluarsaController')->except(['show']);
Route::get('laporan_retur_k/getDataReturKadaluarsa', 'Apotek\Laporan\LapReturKadaluarsaController@getDataReturKadaluarsa')->name('laporan_retur_k/getDataReturKadaluarsa');
Route::get('laporan_retur_k/cari', 'Apotek\Laporan\LapReturKadaluarsaController@cari')->name('laporan_retur_k/cari.cari');
Route::get('laporan_retur_k/view', 'Apotek\Laporan\LapReturKadaluarsaController@view')->name('laporan_retur_k/view.view');

Route::resource('laporan_stok_opname', 'Apotek\Laporan\LapStokOpnameController')->except(['show']);
Route::get('laporan_stok_opname/getDataStokOpname', 'Apotek\Laporan\LapStokOpnameController@getDataStokOpname')->name('laporan_stok_opname/getDataStokOpname');
Route::get('laporan_stok_opname/cari', 'Apotek\Laporan\LapStokOpnameController@cari')->name('laporan_stok_opname/cari.cari');
Route::get('laporan_stok_opname/getViewOpname', 'Apotek\Laporan\LapStokOpnameController@getViewOpname')->name('laporan_stok_opname/getViewOpname');
Route::get('laporan_stok_opname/view', 'Apotek\Laporan\LapStokOpnameController@view')->name('laporan_stok_opname/view.view');

// Route::resource('produk','Apotek\Master_Data\ObatController')->except(['show']);
// Route::get('produk/getDataProduk','Apotek\Master_Data\ObatController@getDataProduk')->name('produk/getDataProduk.getDataProduk');
// Route::post('produk/store','Apotek\Master_Data\ObatController@store')->name('produk/store.store');
// Route::get('produk/getDetailData','Apotek\Master_Data\ObatController@getDetailData')->name('produk/getDetailData.getDetailData');
// Route::post('produk/update','Apotek\Master_Data\ObatController@update')->name('produk/update.update');
// Route::get('produk/view', 'Apotek\Master_Data\ObatController@view')->name('produk/view.view');
/** ######## END Laporan ######## */
