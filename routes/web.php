    <?php

    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;

    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\CatalogController;
    use App\Http\Controllers\CartController;
    use App\Http\Controllers\CheckoutController;
    use App\Http\Controllers\WishlistController;
    use App\Http\Controllers\OrderController;
    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\Auth\LoginController; // ✅ DIPERBAIKI (ditambah Auth\)
    use App\Http\Controllers\Admin\ReportController;

    use App\Http\Controllers\Auth\GoogleController;

    use App\Http\Controllers\Admin\ProductController as AdminProductController;
    use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
    use App\Http\Controllers\Admin\OrderController as AdminOrderController;
    use App\Http\Controllers\Admin\DashboardController; 
    use App\Http\Controllers\Admin\CategoryController;
    use App\Http\Controllers\Admin\ProductController;
    use App\Http\Controllers\PaymentController;

    use App\Http\Middleware\AdminMiddleware;
    use App\Http\Controllers\MidtransNotificationController;

    /*
    |--------------------------------------------------------------------------
    | AUTH ROUTES
    |--------------------------------------------------------------------------
    */
    Auth::routes();

    /*
    |--------------------------------------------------------------------------
    | HALAMAN PUBLIK (TANPA LOGIN)
    |--------------------------------------------------------------------------
    */
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/products', [CatalogController::class, 'index'])
        ->name('catalog.index');

    Route::get('/products/{slug}', [CatalogController::class, 'show'])
        ->name('catalog.show');

    /*
    |--------------------------------------------------------------------------
    | GOOGLE AUTH
    |--------------------------------------------------------------------------
    */
    Route::controller(GoogleController::class)->group(function () {
        Route::get('/auth/google', 'redirect')->name('auth.google');
        Route::get('/auth/google/callback', 'callback')->name('auth.google.callback');
    });

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER (WAJIB LOGIN)
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->group(function () {

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
        Route::put('/profile/email', [ProfileController::class, 'updateEmail'])->name('profile.email.update');
        // Tambahkan ini di dalam middleware auth:
        Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
        Route::get('/home', fn() => redirect('/'));

        Route::post('/email/verification-notification', [ProfileController::class, 'sendVerification'])
            ->name('verification.send');

        Route::delete('/profile/google/unlink', [ProfileController::class, 'unlinkGoogle'])
            ->name('profile.google.unlink');

        // Cart
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
        Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');

        // Wishlist
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])
            ->name('wishlist.toggle');

        // Checkout
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

        // Orders (Customer)
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    });

    Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
        
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Produk CRUD
        Route::resource('products', AdminProductController::class);

        // Kategori CRUD
        Route::resource('categories', AdminCategoryController::class);

        // Manajemen Pesanan
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
            ->name('orders.updateStatus');
    });

    Route::middleware(['auth', AdminMiddleware::class])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('/reports/sales', [ReportController::class, 'sales'])
                ->name('reports.sales');

            Route::get('/reports/export-sales', [ReportController::class, 'exportSales'])->name('reports.export-sales');
        });

    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        // Kategori
        Route::resource('categories', CategoryController::class)->except(['show']);

        // Produk
        Route::resource('products', ProductController::class);

        Route::get('/user', [AdminController::class, 'index'])->name('users.index');
        Route::delete('/user/{id}', [AdminController::class, 'destroy'])->name('users.destroy');
    });

    Route::middleware('auth')->group(function () {

        // Payment Routes
        Route::get('/orders/{order}/pay', [PaymentController::class, 'show'])
            ->name('orders.pay');
        Route::get('/orders/{order}/success', [PaymentController::class, 'success'])
            ->name('orders.success');
        Route::get('/orders/{order}/pending', [PaymentController::class, 'pending'])
            ->name('orders.pending');

        Route::patch('/admin/orders/{id}/update-status', [OrderController::class, 'updateStatus'])
            ->name('admin.orders.update-status');
    });

    /*
    |--------------------------------------------------------------------------
    | MIDTRANS WEBHOOK
    |--------------------------------------------------------------------------
    */
    Route::post('midtrans/notification', [MidtransNotificationController::class, 'handle'])
        ->name('midtrans.notification');

    // Login Route dengan Throttle
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
    // Tambahkan ini di baris paling bawah web.php
    Route::get('/home', function () {
        return redirect('/');
    });