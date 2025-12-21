<div class="space-y-4">
    <div class="text-sm text-gray-700">
        Esta pantalla sirve para <span class="font-medium">detectar y corregir metas incompletas</span> (contenido/URL/SEO) por producto e idioma.
    </div>

    <div class="max-h-[60vh] overflow-auto pr-1 space-y-4">
        <div class="code-snippet">
            <div style="color: #9CA3AF;">// Filament Resource</div>
            <div>
                <span style="color: #EC4899;">Resource</span>
                <span style="color: #6B7280;">:</span>
                <span style="color: #8B5CF6;">MetasResource</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// Route</div>
            <div>
                <span style="color: #F59E0B;">admin/metas</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// Query (only incomplete records)</div>
            <div>
                <span style="color: #8B5CF6;">soft_product_lang</span>
                <span style="color: #6B7280;"> where any of:</span>
            </div>
            <div>
                <span style="color: #F59E0B;">description</span>
                <span style="color: #6B7280;">/</span>
                <span style="color: #F59E0B;">description_short</span>
                <span style="color: #6B7280;">/</span>
                <span style="color: #F59E0B;">meta_title</span>
                <span style="color: #6B7280;"> vacío</span>
            </div>

            <div style="color: #9CA3AF;" class="mt-4">// UI signals</div>
            <div>
                <span style="color: #6B7280;">IconColumn:</span>
                <span style="color: #8B5CF6;"> check / x</span>
                <span style="color: #6B7280;"> para Desc, Desc corta, URL, SEO</span>
            </div>
        </div>

        <div class="code-snippet">
            <div style="color: #9CA3AF;">// Edit</div>
            <div>
                <span style="color: #8B5CF6;">EditAction</span>
                <span style="color: #6B7280;"> abre formulario para completar campos faltantes.</span>
            </div>
        </div>
    </div>
</div>
