<?php

use App\DataTables\UsersDataTable;
use App\Events\UserRegistered;
use App\Helpers\ImageFilter;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Gateways\InstamojoController;
use App\Http\Controllers\Gateways\MollieController;
use App\Http\Controllers\Gateways\PaypalController;
use App\Http\Controllers\Gateways\PaystackController;
use App\Http\Controllers\Gateways\RazorpayController;
use App\Http\Controllers\Gateways\StripeController;
use App\Http\Controllers\Gateways\TwoCheckoutController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Jobs\SendMail;
use App\Mail\PostPublished;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Intervention\Image\Drivers\Gd\Driver;
//use Intervention\Image\ImageManager;
//use Intervention\Image\Laravel\Facades\Image;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (UsersDataTable $dataTable) {
    // $users=User::paginate(10);
    // return view('dashboard',compact('users'));
    return view('dashboard');
    //return $dataTable->render('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::group(['middleware'=>'auth'],function(){
    Route::get('/posts/trash',[PostController::class,'trashed'])->name('posts.trashed');
    Route::get('/posts/{id}/restore',[PostController::class,'restore'])->name('posts.restore');
    Route::delete('/posts/{id}/force-delete',[PostController::class,'forceDelete'])->name('posts.forceDelete');
    
    Route::resource('posts',PostController::class);
});


Route::get('user-data',function(){
    //return Auth::user()->name;
    return auth()->user()->email;
});

Route::get('send-mail',function(){
    SendMail::dispatch();
    dd('mail has been send');
});

Route::get('user-register',function(){
    $email='pnonjida@gmail.com';
    event(new UserRegistered($email));
    dd('mail has been send');
});
//en,hi
Route::get('greeting/{locale}',function($locale){
    App::setLocale($locale);
    return view('greeting');
})->name('greeting');

Route::get('image',function(){
    // $img=Image::read('new.jpg');
    //$manager=new ImageManager(new Driver());
    //$img = ImageManager::make('new.jpg');
    //$img->filter(new ImageFilter(5));
     //$img->crop(400,400);
    // $img->blur(15);
    // $img->greyscale();
     //$img->save('new1.jpg',80);
    //return $img->response();
});



Route::get('shop',[CartController::class,'shop'])->name('shop');
Route::get('cart',[CartController::class,'cart'])->name('cart');
Route::get('add-to-cart/{product_id}',[CartController::class,'addToCart'])->name('add-to-cart');
Route::get('qty-increment/{rowId}',[CartController::class,'qtyIncrement'])->name('qty-increment');
Route::get('qty-decrement/{rowId}',[CartController::class,'qtyDecrement'])->name('qty-decrement');
Route::get('remove-product/{rowId}',[CartController::class,'removeProduct'])->name('remove-product');

Route::get('create-role',function(){
    // $role = Role::create(['name' => 'publisher']);
    // return $role;
    // $permission = Permission::create(['name' => 'edit articles']);
    // return $permission;

    $user=auth()->user();
    //$user->assignRole('writer');
    //$user->givePermissionTo('edit articles');
    //return $user->getRoleNames();
   
    if($user->can('edit articles')){
        return 'user has permission';
    }else{
        return 'user do not have permission';
    }
    
});

Route::get('posts',function(){
    //auth()->user()->assignRole('editor');
    $posts=Post::all();
    return view('components.post.post',compact('posts'));
});

Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('github.login');
 
Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();
 
    // $user->token
    $user = User::firstOrCreate([
        'email' => $user->email
    ],[
        'name' => $user->name,
        'password' => bcrypt(Str::random(24))
    ]);

    Auth::login($user, true);

    return redirect('/dashboard');
});


Route::post('paypal/payment',[PaypalController::class,'payment'])->name('paypal.payment');
Route::get('paypal/success',[PaypalController::class,'success'])->name('paypal.success');
Route::get('paypal/cancel',[PaypalController::class,'cancel'])->name('paypal.cancel');


Route::post('stripe/payment', [StripeController::class, 'payment'])->name('stripe.payment');
Route::get('stripe/success', [StripeController::class, 'success'])->name('stripe.success');
Route::get('stripe/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');

Route::post('razorpay/payment', [RazorpayController::class, 'payment'])->name('razorpay.payment');


Route::get('twocheckout/payment',[TwoCheckoutController::class, 'showFrom'])->name('twocheckout.payment');
Route::post('twocheckout/handle-payment',[TwoCheckoutController::class, 'handlePayment'])->name('twocheckout.handle-payment');
Route::get('twocheckout/success', [TwoCheckoutController::class, 'success'])->name('twocheckout.success');

Route::post('instamojo/payment', [InstamojoController::class, 'payment'])->name('instamojo.payment');
Route::get('instamojo/callback', [InstamojoController::class, 'callback'])->name('instamojo.callback');

Route::post('mollie/payment', [MollieController::class, 'payment'])->name('mollie.payment');
Route::get('mollie/success', [MollieController::class, 'success'])->name('mollie.success');


Route::get('paystack/redirect', [PaystackController::class, 'paystackRedirect'])->name('paystack.redirect');
Route::get('paystack/callback', [PaystackController::class, 'verifyTransaction'])->name('paystack.callback');

// SSLCOMMERZ Start
Route::post('/pay', [SslCommerzPaymentController::class, 'index'])->name('sslcommerz.pay');
Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);
//SSLCOMMERZ END