<?php
use Illuminate\Support\Facades\Route;
use App\Services\PrestaShopService;
use App\Models\EmailTemplate;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/email-templates/{template}/preview/{lang}', function (int $template, int $lang) {
    $emailTemplate = EmailTemplate::query()->findOrFail($template);
    $translation = $emailTemplate->langs()->where('id_lang', $lang)->first();

    $subject = $translation?->subject ?? '';
    $html = $translation?->html_content ?? '';

    return response(
        '<!doctype html><html><head><meta charset="utf-8"><title>'
        . e($subject)
        . '</title></head><body>'
        . $html
        . '</body></html>',
        200,
        ['Content-Type' => 'text/html; charset=UTF-8']
    );
})->middleware('auth')->name('email-templates.preview');

// Productos
Route::get('/products', function (PrestaShopService $prestashop) {
    $products = $prestashop->getProducts();
    return view('pages.products', compact('products'));
});

// Clientes
Route::get('/customers', function (PrestaShopService $prestashop) {
    $customers = $prestashop->getCustomers();
    return view('pages.customers', compact('customers'));
});
